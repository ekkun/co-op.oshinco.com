const BLUR_CLASS = 'coop-text-blur';
const EXCLUDED_SELECTOR = `script, style, noscript, template, svg, canvas, textarea, option, .${BLUR_CLASS}`;

const wrapTextNodes = (root) => {
	if (!(root instanceof Element) || root.matches(EXCLUDED_SELECTOR)) return;

	const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
		acceptNode(node) {
			if (!node.textContent.trim()) return NodeFilter.FILTER_REJECT;
			if (node.parentElement?.closest(EXCLUDED_SELECTOR)) return NodeFilter.FILTER_REJECT;
			return NodeFilter.FILTER_ACCEPT;
		},
	});
	const textNodes = [];
	while (walker.nextNode()) textNodes.push(walker.currentNode);

	textNodes.forEach((textNode) => {
		const wrapper = document.createElement('span');
		wrapper.className = BLUR_CLASS;
		textNode.replaceWith(wrapper);
		wrapper.append(textNode);
	});
};

export const initBlurText = () => {
	document.body.classList.add('has-coop-text-blur');
	wrapTextNodes(document.body);

	document.addEventListener('pointerover', (event) => {
		if (!(event.target instanceof Element)) return;
		const interactive = event.target.closest('.wp-block-button__link, .coop-logo, .inquiry .mw_wp_form .submit');
		if (!interactive || (event.relatedTarget instanceof Node && interactive.contains(event.relatedTarget))) return;

		const rect = interactive.getBoundingClientRect();
		interactive.style.setProperty('--coop-hover-x', `${event.clientX - rect.left}px`);
		interactive.style.setProperty('--coop-hover-y', `${event.clientY - rect.top}px`);
	});

	new MutationObserver((mutations) => {
		mutations.forEach(({ addedNodes }) => {
			addedNodes.forEach((node) => {
				if (node.nodeType === Node.TEXT_NODE && node.textContent.trim()) {
					const parent = node.parentElement;
					if (parent && !parent.closest(EXCLUDED_SELECTOR)) {
						const wrapper = document.createElement('span');
						wrapper.className = BLUR_CLASS;
						node.replaceWith(wrapper);
						wrapper.append(node);
					}
				} else if (node instanceof Element) {
					wrapTextNodes(node);
				}
			});
		});
	}).observe(document.body, { childList: true, subtree: true });
};
