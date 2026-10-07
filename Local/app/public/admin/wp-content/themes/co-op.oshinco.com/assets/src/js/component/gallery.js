export function initGalleries(Splide) {
	const upgradeGallery = (gallery, index) => {
		const items = Array.from(gallery.children).filter((item) => item.matches('.wp-block-image, figure'));
		if (!items.length || gallery.dataset.splideReady) return;

		gallery.dataset.splideReady = 'true';
		gallery.classList.add('splide', 'coop-gallery');
		gallery.setAttribute('aria-label', gallery.getAttribute('aria-label') || `画像ギャラリー ${index + 1}`);

		const track = document.createElement('div');
		const list = document.createElement('div');
		track.className = 'splide__track';
		list.className = 'splide__list';
		items.forEach((item) => {
			item.classList.add('splide__slide');
			list.appendChild(item);
		});
		track.appendChild(list);
		gallery.appendChild(track);

		const splide = new Splide(gallery, {
			type: items.length > 1 ? 'loop' : 'slide',
			autoWidth: true,
			focus: 'center',
			gap: '1rem',
			padding: { left: '32%', right: '32%' },
			pagination: true,
			arrows: items.length > 1,
			keyboard: 'global',
			updateOnMove: true,
			breakpoints: {
				767: { padding: { left: '20%', right: '20%' } },
			},
			reducedMotion: { speed: 0, rewindSpeed: 0 },
		});

		splide.on('mounted', () => {
			gallery.querySelectorAll('.splide__arrow').forEach((button) => {
				const icon = document.createElement('ion-icon');
				const isPrevious = button.classList.contains('splide__arrow--prev');
				icon.className = 'coop-gallery__arrow-icon';
				icon.setAttribute('name', isPrevious ? 'chevron-back' : 'chevron-forward');
				icon.setAttribute('aria-hidden', 'true');
				button.replaceChildren(icon);
			});
		});

		splide.mount();
	};

	document.addEventListener('DOMContentLoaded', () => {
		document.querySelectorAll('.wp-block-gallery').forEach(upgradeGallery);
	});
}
