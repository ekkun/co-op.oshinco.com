/*
 * main.js
 */

import '../css/tailwind.css';
import '../css/fonts.css';
import '@splidejs/splide/css';
import '../scss/style.scss';
import Splide from '@splidejs/splide';
import { addIcons } from 'ionicons';
import { defineCustomElement as defineIonIcon } from 'ionicons/components/ion-icon.js';
import { arrowBack, arrowForward, chevronBack, chevronForward } from 'ionicons/icons';
import { initGalleries } from './component/gallery';
import { initCaseInfiniteScroll } from './component/caseInfiniteScroll';
import { initCaseMasonry } from './component/caseMasonry';
import { initArchiveInfiniteScroll } from './component/archiveInfiniteScroll';
import { initNewsTicker } from './component/newsTicker';
import { initCodeBlocks } from './component/codeBlocks';
import { initBlurText } from './component/blurText';

addIcons({
	'arrow-round-back': arrowBack,
	'arrow-round-forward': arrowForward,
	'chevron-back': chevronBack,
	'chevron-forward': chevronForward,
});
defineIonIcon();
initGalleries(Splide);
const caseMasonry = initCaseMasonry();
initCaseInfiniteScroll(caseMasonry);
initArchiveInfiniteScroll(caseMasonry);
initNewsTicker();
initCodeBlocks();
initBlurText();

const menuToggle = document.querySelector('.coop-menu-toggle');
const navigation = document.querySelector('.coop-navigation');

if (menuToggle && navigation) {
	const closeMenu = () => {
		menuToggle.setAttribute('aria-expanded', 'false');
		menuToggle.querySelector('.screen-reader-text').textContent = 'メニューを開く';
		document.body.classList.remove('is-coop-menu-open');
	};
	menuToggle.addEventListener('click', () => {
		const willOpen = menuToggle.getAttribute('aria-expanded') !== 'true';
		menuToggle.setAttribute('aria-expanded', String(willOpen));
		menuToggle.querySelector('.screen-reader-text').textContent = willOpen ? 'メニューを閉じる' : 'メニューを開く';
		document.body.classList.toggle('is-coop-menu-open', willOpen);
	});
	navigation.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));
	document.addEventListener('keydown', (event) => { if (event.key === 'Escape') { closeMenu(); menuToggle.focus(); } });
}
