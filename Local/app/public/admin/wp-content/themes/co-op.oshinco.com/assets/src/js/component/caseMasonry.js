import Masonry from 'masonry-layout';
import imagesLoaded from 'imagesloaded';

export function initCaseMasonry() {
	const grid = document.querySelector('.coop-cases__grid');
	if (!grid) return null;

	const instance = new Masonry(grid, {
		itemSelector: '.coop-case-card',
		columnWidth: '.coop-cases__sizer',
		gutter: 24,
		percentPosition: true,
		transitionDuration: '0.35s',
	});
	imagesLoaded(grid).on('progress', () => instance.layout());

	return {
		append(elements) {
			instance.appended(elements);
			imagesLoaded(elements).on('progress', () => instance.layout());
		},
		layout() { instance.layout(); },
	};
}
