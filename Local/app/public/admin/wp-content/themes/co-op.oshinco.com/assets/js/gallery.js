(function () {
  'use strict';

  function upgradeGallery(gallery, index) {
    var items = Array.prototype.slice.call(gallery.children).filter(function (item) {
      return item.matches('.wp-block-image, figure');
    });

    if (!items.length || gallery.dataset.splideReady) return;

    gallery.dataset.splideReady = 'true';
    gallery.classList.add('splide', 'coop-gallery');
    gallery.setAttribute('aria-label', gallery.getAttribute('aria-label') || '画像ギャラリー ' + (index + 1));

    var track = document.createElement('div');
    var list = document.createElement('div');
    track.className = 'splide__track';
    list.className = 'splide__list';

    items.forEach(function (item) {
      item.classList.add('splide__slide');
      list.appendChild(item);
    });

    track.appendChild(list);
    gallery.appendChild(track);

    new Splide(gallery, {
      type: items.length > 1 ? 'loop' : 'slide',
      perPage: 1,
      gap: '1rem',
      pagination: true,
      arrows: items.length > 1,
      keyboard: 'global',
      reducedMotion: { speed: 0, rewindSpeed: 0 },
    }).mount();
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.wp-block-gallery').forEach(upgradeGallery);
  });
}());
