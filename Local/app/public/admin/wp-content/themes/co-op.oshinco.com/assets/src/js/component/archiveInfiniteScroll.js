export function initArchiveInfiniteScroll(masonry) {
  const section = document.querySelector('[data-archive-infinite-scroll]');
  const infiniteScroll = section?.querySelector('.coop-infinite-scroll');
  const containerSelector = section?.dataset.containerSelector;
  const itemSelector = section?.dataset.itemSelector;
  const container = containerSelector ? section.querySelector(containerSelector) : null;

  if (!section || !infiniteScroll || !container || !itemSelector) return;

  let nextPage = section.dataset.nextPage;
  let loading = false;
  let observer;

  const disable = () => {
    observer?.disconnect();
    infiniteScroll.hidden = true;
  };

  if (!nextPage) {
    disable();
    return;
  }

  const loadNextPage = async () => {
    if (loading) return;
    loading = true;
    infiniteScroll.setAttribute('aria-busy', 'true');

    try {
      const response = await fetch(nextPage, { headers: { Accept: 'text/html' } });
      if (!response.ok) throw new Error(`Archive request failed: ${response.status}`);

      const html = await response.text();
      const documentFragment = new DOMParser().parseFromString(html, 'text/html');
      const nextSection = documentFragment.querySelector('[data-archive-infinite-scroll]');
      const nextContainer = nextSection?.querySelector(containerSelector);
      const items = [...(nextContainer?.querySelectorAll(itemSelector) || [])]
        .map((item) => document.importNode(item, true));

      items.forEach((item) => container.appendChild(item));
      if (section.classList.contains('coop-cases')) masonry?.append(items);

      nextPage = nextSection?.dataset.nextPage || '';
      if (!nextPage) disable();
    } catch (error) {
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
