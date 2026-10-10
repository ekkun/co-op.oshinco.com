export function initGalleries(Splide) {
	const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
	let activeImage = null;
	let modalItems = [];
	let modalIndex = 0;

	const modal = document.createElement('dialog');
	modal.className = 'coop-gallery-modal';
	modal.setAttribute('aria-label', '画像の拡大表示');
	modal.innerHTML = `
		<div class="coop-gallery-modal__inner">
			<button class="coop-gallery-modal__close" type="button" aria-label="拡大画像を閉じる">×</button>
			<button class="coop-gallery-modal__nav coop-gallery-modal__nav--previous" type="button" aria-label="前の画像"><ion-icon class="coop-gallery__arrow-icon" name="chevron-back" aria-hidden="true"></ion-icon></button>
			<figure class="coop-gallery-modal__figure">
				<img class="coop-gallery-modal__image" alt="">
				<figcaption class="coop-gallery-modal__caption"></figcaption>
			</figure>
			<button class="coop-gallery-modal__nav coop-gallery-modal__nav--next" type="button" aria-label="次の画像"><ion-icon class="coop-gallery__arrow-icon" name="chevron-forward" aria-hidden="true"></ion-icon></button>
		</div>
	`;
	document.body.appendChild(modal);

	const modalImage = modal.querySelector('.coop-gallery-modal__image');
	const modalCaption = modal.querySelector('.coop-gallery-modal__caption');
	const closeButton = modal.querySelector('.coop-gallery-modal__close');
	const previousButton = modal.querySelector('.coop-gallery-modal__nav--previous');
	const nextButton = modal.querySelector('.coop-gallery-modal__nav--next');
	const transitionName = 'coop-gallery-image';

	const runTransition = (update) => {
		if (!document.startViewTransition || reducedMotion.matches) {
			update();
			return Promise.resolve();
		}
		return document.startViewTransition(update).finished;
	};

	const closeModal = () => {
		if (!modal.open) return;
		modalImage.style.viewTransitionName = transitionName;
		runTransition(() => {
			modal.close();
			modalImage.style.viewTransitionName = '';
			if (activeImage?.isConnected) activeImage.style.viewTransitionName = transitionName;
		}).finally(() => {
			if (activeImage?.isConnected) {
				activeImage.style.viewTransitionName = '';
				activeImage.focus?.({ preventScroll: true });
			}
			activeImage = null;
		});
	};

	const getModalItem = (image) => {
		const link = image.closest('a');
		const linkedImage = link?.href && /\.(?:avif|gif|jpe?g|png|svg|webp)(?:\?.*)?$/i.test(link.href) ? link.href : '';
		return {
			src: linkedImage || image.currentSrc || image.src,
			alt: image.alt || '',
			caption: image.closest('figure')?.querySelector('figcaption')?.textContent?.trim() || image.alt || '',
		};
	};

	const renderModalItem = (index) => {
		modalIndex = (index + modalItems.length) % modalItems.length;
		const item = modalItems[modalIndex];
		modalImage.src = item.src;
		modalImage.alt = item.alt;
		modalCaption.textContent = item.caption;
		modalCaption.hidden = !item.caption;
	};

	const moveModal = (direction) => {
		if (modalItems.length < 2) return;
		modalImage.style.viewTransitionName = transitionName;
		runTransition(() => renderModalItem(modalIndex + direction)).finally(() => {
			modalImage.style.viewTransitionName = '';
		});
	};

	closeButton.addEventListener('click', closeModal);
	previousButton.addEventListener('click', () => moveModal(-1));
	nextButton.addEventListener('click', () => moveModal(1));
	modal.addEventListener('click', (event) => {
		if (event.target === modal) closeModal();
	});
	modal.addEventListener('cancel', (event) => {
		event.preventDefault();
		closeModal();
	});
	modal.addEventListener('keydown', (event) => {
		if (event.key === 'ArrowLeft') {
			event.preventDefault();
			moveModal(-1);
		}
		if (event.key === 'ArrowRight') {
			event.preventDefault();
			moveModal(1);
		}
	});

	const openModal = (image) => {
		activeImage = image;
		const gallery = image.closest('.coop-gallery');
		const originals = Array.from(gallery.querySelectorAll('.splide__slide:not(.splide__slide--clone) .coop-gallery__zoom-image'));
		modalItems = originals.map(getModalItem);
		const clickedItem = getModalItem(image);
		modalIndex = Math.max(0, modalItems.findIndex((item) => item.src === clickedItem.src));
		image.style.viewTransitionName = transitionName;

		runTransition(() => {
			renderModalItem(modalIndex);
			previousButton.hidden = modalItems.length < 2;
			nextButton.hidden = modalItems.length < 2;
			image.style.viewTransitionName = '';
			modalImage.style.viewTransitionName = transitionName;
			modal.showModal();
		}).finally(() => {
			modalImage.style.viewTransitionName = '';
			closeButton.focus({ preventScroll: true });
		});
	};

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
			const image = item.querySelector('img');
			if (image) {
				image.classList.add('coop-gallery__zoom-image');
				image.setAttribute('tabindex', '0');
				image.setAttribute('role', 'button');
				image.setAttribute('aria-label', `${image.alt || '画像'}を拡大表示`);
			}
			list.appendChild(item);
		});
		track.appendChild(list);
		gallery.appendChild(track);
		gallery.addEventListener('click', (event) => {
			const image = event.target.closest?.('.coop-gallery__zoom-image');
			if (!image) return;
			event.preventDefault();
			openModal(image);
		});
		gallery.addEventListener('keydown', (event) => {
			const image = event.target.closest?.('.coop-gallery__zoom-image');
			if (!image || (event.key !== 'Enter' && event.key !== ' ')) return;
			event.preventDefault();
			openModal(image);
		});

		const splide = new Splide(gallery, {
			type: items.length > 1 ? 'loop' : 'slide',
			rewind: items.length <= 1,
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
