const TICK_INTERVAL = 4000;

export const initNewsTicker = () => {
	const ticker = document.querySelector('[data-news-ticker]');
	if (!ticker) return;

	const items = Array.from(ticker.querySelectorAll('.coop-news__link'));
	if (items.length < 2) return;

	let currentIndex = 0;
	let timer = null;

	const showItem = (nextIndex) => {
		const current = items[currentIndex];
		const next = items[nextIndex];

		current.classList.remove('is-active');
		current.classList.add('is-leaving');
		current.setAttribute('aria-hidden', 'true');
		current.tabIndex = -1;

		next.classList.remove('is-leaving');
		next.removeAttribute('aria-hidden');
		next.removeAttribute('tabindex');

		requestAnimationFrame(() => {
			next.classList.add('is-active');
		});

		window.setTimeout(() => current.classList.remove('is-leaving'), 500);
		currentIndex = nextIndex;
	};

	const start = () => {
		if (timer || document.hidden) return;
		timer = window.setInterval(() => {
			showItem((currentIndex + 1) % items.length);
		}, TICK_INTERVAL);
	};

	const stop = () => {
		if (!timer) return;
		window.clearInterval(timer);
		timer = null;
	};

	ticker.addEventListener('mouseenter', stop);
	ticker.addEventListener('mouseleave', start);
	ticker.addEventListener('focusin', stop);
	ticker.addEventListener('focusout', start);
	document.addEventListener('visibilitychange', () => {
		if (document.hidden) stop();
		else start();
	});

	start();
};
