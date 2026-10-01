((wp) => {
  const { domReady } = wp;

  /**
   *  Update iframe classes
   *  @see https://github.com/WordPress/gutenberg/issues/17854
   *  @see https://github.com/WordPress/gutenberg/issues/56831
   *  @see https://github.com/WordPress/gutenberg/issues/55947#issuecomment-1801105188
   */
  domReady(() => {
    setTimeout(() => {
      const editorBodyElement = document.querySelector('.editor-styles-wrapper');

      const isIframeEditorContext = !!editorBodyElement;
      if (isIframeEditorContext) {
        setTimeout(addBodyClassesToIframe, 1000);
      }

      function addBodyClassesToIframe() {
        const rootContainer = document.querySelector('.is-root-container');
        if (rootContainer) {
          //rootContainer.classList.add('l-entry');
        }
      }
    }, 3000);
  });
})(window.wp);

(() => {
  setTimeout(() => {
    const editorBodyElement = document.querySelector('.editor-styles-wrapper');

    const isIframeEditorContext = !!editorBodyElement;
    if (isIframeEditorContext) {
      setTimeout(addBodyClassesToIframe, 1000);
    }

    function addBodyClassesToIframe() {
      const rootContainer = document.querySelector('.is-root-container');
      if (rootContainer) {
        //rootContainer.classList.add('l-entry');
      }
    }
  }, 3000);
})();
