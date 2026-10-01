/**
 * Adjust Viewport
 */
import debounce from './debounce';

let locked = false;

const adjustViewport = () => {
  if (locked) return;

  const triggerWidth = 375;
  const viewport = document.querySelector('meta[name="viewport"]');
  if (!viewport) return;

  const w = document.documentElement.clientWidth || window.innerWidth;
  //const value = w < triggerWidth ? `width=${triggerWidth}, target-densitydpi=device-dpi` : 'width=device-width, initial-scale=1';
  const value = w < triggerWidth ? `width=${triggerWidth}, initial-scale=1` : 'width=device-width, initial-scale=1';

  if (viewport.getAttribute('content') === value) return;

  locked = true;
  viewport.setAttribute('content', value);

  // 直後に発火する resize 連鎖を捨てる
  setTimeout(() => {
    locked = false;
  }, 400);
};

const debounced = debounce(adjustViewport, 150);
window.addEventListener('resize', debounced, { passive: true });
window.addEventListener('orientationchange', debounced, false);

adjustViewport();
