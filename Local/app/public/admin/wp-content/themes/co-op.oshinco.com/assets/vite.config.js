import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import { resolve } from 'node:path';

const assetsRoot = import.meta.dirname;
const themeRoot = resolve(assetsRoot, '..');

export default defineConfig({
  root: themeRoot,
  plugins: [tailwindcss()],
  build: {
    outDir: assetsRoot,
    emptyOutDir: false,
    sourcemap: true,
    rollupOptions: {
      input: resolve(assetsRoot, 'src/js/main.js'),
      output: {
        entryFileNames: 'js/main.js',
        chunkFileNames: 'js/[name]-[hash].js',
        assetFileNames(assetInfo) {
          const sourceName = assetInfo.names?.[0] ?? assetInfo.name ?? '';

          if (sourceName.endsWith('.css')) return 'css/style.css';
          if (/\.(woff2?|ttf|otf|eot)$/i.test(sourceName)) return 'fonts/[name]-[hash][extname]';
          if (/\.(png|jpe?g|gif|svg|webp|avif)$/i.test(sourceName)) return 'images/[name][extname]';

          return 'misc/[name]-[hash][extname]';
        },
      },
    },
  },
});
