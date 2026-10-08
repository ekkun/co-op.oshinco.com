import { readdir, stat } from 'node:fs/promises';
import { resolve } from 'node:path';
import { build } from 'vite';

const sourceRoot = resolve(import.meta.dirname, '../src');

let building = false;
let rebuildRequested = false;

async function createSnapshot() {
  const entries = await readdir(sourceRoot, {
    recursive: true,
    withFileTypes: true,
  });
  const files = entries.filter((entry) => entry.isFile());
  const metadata = await Promise.all(files.map(async (entry) => {
    const file = resolve(entry.parentPath, entry.name);
    const fileStat = await stat(file);
    return `${file}:${fileStat.mtimeMs}:${fileStat.size}`;
  }));

  return metadata.sort().join('\n');
}

async function runBuild() {
  if (building) {
    rebuildRequested = true;
    return;
  }

  building = true;

  try {
    await build({ mode: 'development' });
  } catch (error) {
    console.error(error);
  } finally {
    building = false;

    if (rebuildRequested) {
      rebuildRequested = false;
      await runBuild();
    }
  }
}

await runBuild();

let previousSnapshot = await createSnapshot();
let checking = false;

const pollingTimer = setInterval(async () => {
  if (checking) return;
  checking = true;

  try {
    const currentSnapshot = await createSnapshot();

    if (currentSnapshot !== previousSnapshot) {
      previousSnapshot = currentSnapshot;
      console.log('\nSource changed.');
      await runBuild();
    }
  } catch (error) {
    console.error(error);
  } finally {
    checking = false;
  }
}, 300);

console.log(`\nWatching ${sourceRoot}`);

function stop() {
  clearInterval(pollingTimer);
  process.exit(0);
}

process.on('SIGINT', stop);
process.on('SIGTERM', stop);
