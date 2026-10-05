/*
 * main.js
 */

import '../css/tailwind.css';
import '../css/fonts.css';
import '../scss/style.scss';
import '@splidejs/splide/css';
import Splide from '@splidejs/splide';
import { defineCustomElement as defineIonInfiniteScroll } from '@ionic/core/components/ion-infinite-scroll.js';
import { defineCustomElement as defineIonInfiniteScrollContent } from '@ionic/core/components/ion-infinite-scroll-content.js';
import '@ionic/core/css/core.css';
import './component/adjustViewport';
import { initGalleries } from './component/gallery';
import { initCaseInfiniteScroll } from './component/caseInfiniteScroll';
import { initCaseMasonry } from './component/caseMasonry';

defineIonInfiniteScroll();
defineIonInfiniteScrollContent();
initGalleries(Splide);
const caseMasonry = initCaseMasonry();
initCaseInfiniteScroll(caseMasonry);

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
