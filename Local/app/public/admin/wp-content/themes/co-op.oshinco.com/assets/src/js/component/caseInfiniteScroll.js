const decodeHtml = (value = '') => {
	const textarea = document.createElement('textarea');
	textarea.innerHTML = value;
	return textarea.value;
};

const stripHtml = (value = '') => {
	const element = document.createElement('div');
	element.innerHTML = value;
	return element.textContent || '';
};

const createCard = (post) => {
	const article = document.createElement('article');
	article.className = `coop-case-card post-${post.id}`;
	const media = post._embedded?.['wp:featuredmedia']?.[0];
	const image = media?.source_url;
	const title = decodeHtml(post.title?.rendered);
	const excerpt = stripHtml(post.excerpt?.rendered).trim();

	article.innerHTML = `
		<a class="coop-case-card__link" href="${post.link}">
			<figure class="coop-case-card__media">
				${image ? `<img src="${image}" alt="" loading="lazy">` : '<span class="coop-case-card__placeholder" aria-hidden="true"></span>'}
			</figure>
			<div class="coop-case-card__body">
				<h2 class="coop-case-card__title"><span class="coop-case-card__title-text"></span></h2>
				<div class="coop-case-card__excerpt"><p></p></div>
				<span class="coop-case-card__arrow" aria-hidden="true"></span>
			</div>
		</a>`;
	article.querySelector('.coop-case-card__title-text').textContent = title;
	article.querySelector('.coop-case-card__excerpt p').textContent = excerpt;
	return article;
};

export function initCaseInfiniteScroll(masonry) {
	const section = document.querySelector('.coop-cases[data-rest-url]');
	const infiniteScroll = section?.querySelector('.coop-infinite-scroll');
	const grid = section?.querySelector('.coop-cases__grid');
	if (!section || !infiniteScroll || !grid) return;

	let page = Number(section.dataset.currentPage || 1);
	const totalPages = Number(section.dataset.totalPages || 1);
	let loading = false;
	let observer;

	const disable = () => {
		observer?.disconnect();
		infiniteScroll.hidden = true;
	};

	if (page >= totalPages) {
		disable();
		return;
	}

	const loadNextPage = async () => {
		if (loading) return;
		loading = true;
		infiniteScroll.setAttribute('aria-busy', 'true');

		try {
			page += 1;
			const url = new URL(section.dataset.restUrl);
			url.searchParams.set('page', String(page));
			url.searchParams.set('per_page', '6');
			url.searchParams.set('orderby', 'date');
			url.searchParams.set('order', 'desc');
			url.searchParams.set('_embed', 'wp:featuredmedia');
			const response = await fetch(url.toString(), { headers: { Accept: 'application/json' } });
			if (!response.ok) throw new Error(`Case request failed: ${response.status}`);
			const posts = await response.json();
			const cards = posts.map(createCard);
			cards.forEach((card) => grid.appendChild(card));
			masonry?.append(cards);
			if (page >= totalPages) disable();
		} catch (error) {
			page -= 1;
			console.error(error);
		} finally {
			loading = false;
			infiniteScroll.setAttribute('aria-busy', 'false');
		}
	};

	observer = new IntersectionObserver((entries) => {
		if (entries.some((entry) => entry.isIntersecting)) void loadNextPage();
	}, { rootMargin: '300px 0px' });
	observer.observe(infiniteScroll);
}
