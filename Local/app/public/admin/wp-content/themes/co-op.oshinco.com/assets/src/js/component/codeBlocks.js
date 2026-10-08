export function initCodeBlocks() {
	document.querySelectorAll('.wp-block-code > code:not([data-line-numbers])').forEach((code) => {
		const lines = code.textContent.replace(/\r\n?/g, '\n').split('\n');
		const fragment = document.createDocumentFragment();

		lines.forEach((line) => {
			const row = document.createElement('span');
			row.className = 'wp-block-code__line';
			row.textContent = line || '\u200b';
			fragment.appendChild(row);
		});

		code.replaceChildren(fragment);
		code.dataset.lineNumbers = 'true';
	});
}
