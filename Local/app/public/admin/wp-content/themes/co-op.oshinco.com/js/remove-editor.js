wp.domReady(() => {
  // サンプル
  // wp.blocks.unregisterBlockStyle( 'ブロック名', 'スタイル名' );

  // 画像
  wp.blocks.unregisterBlockStyle('core/image', 'default');
  wp.blocks.unregisterBlockStyle('core/image', 'rounded');

  // 引用
  wp.blocks.unregisterBlockStyle('core/quote', 'default');
  wp.blocks.unregisterBlockStyle('core/quote', 'plain');
  wp.blocks.unregisterBlockStyle('core/quote', 'large');

  // ボタン
  //wp.blocks.unregisterBlockStyle('core/button', 'fill');
  //wp.blocks.unregisterBlockStyle('core/button', 'outline');

  // 抜粋
  wp.blocks.unregisterBlockStyle('core/pullquote', 'default');
  wp.blocks.unregisterBlockStyle('core/pullquote', 'solid-color');

  // グループ
  //wp.blocks.unregisterBlockVariation('core/group', 'group-flex');
  wp.blocks.unregisterBlockVariation('core/group', 'group-row');
  wp.blocks.unregisterBlockVariation('core/group', 'group-stack');
  wp.blocks.unregisterBlockVariation('core/group', 'group-grid');
  wp.blocks.unregisterBlockVariation('core/group', 'layout');

  // 区切り
  wp.blocks.unregisterBlockStyle('core/separator', 'default');
  wp.blocks.unregisterBlockStyle('core/separator', 'wide');
  wp.blocks.unregisterBlockStyle('core/separator', 'dots');

  // テーブル
  wp.blocks.unregisterBlockStyle('core/table', 'regular');
  wp.blocks.unregisterBlockStyle('core/table', 'stripes');

  // SNS
  wp.blocks.unregisterBlockStyle('core/social-links', 'default');
  wp.blocks.unregisterBlockStyle('core/social-links', 'logos-only');
  wp.blocks.unregisterBlockStyle('core/social-links', 'pill-shape');

  // "core/group" の "layout" パネルを削除
  wp.blocks.registerBlockVariation('core/group', {
    name: 'no-layout',
    title: 'No Layout',
    scope: ['block'],
    attributes: {
      layout: undefined,
    },
    isDefault: false,
    innerBlocks: [],
    icon: null,
  });

  // core/list ブロックの設定項目（リストのスタイル、初期値、順序を逆にする）を強制非表示
  wp.data.subscribe(() => {
    const selectedBlock = wp.data.select('core/block-editor').getSelectedBlock();
    if (selectedBlock && selectedBlock.name === 'core/list') {
      // レンダリングを待つため少し遅延させる
      setTimeout(() => {
        const inspector = document.querySelector('.block-editor-block-inspector, .edit-post-sidebar');
        if (inspector) {
          const labels = inspector.querySelectorAll('label, legend');
          labels.forEach(label => {
            const text = label.textContent || '';
            if (
              text.includes('初期値') ||
              text.includes('順序を逆にする') ||
              text.includes('リストのスタイル') ||
              text.includes('Start value') ||
              text.includes('Reverse list numbering') ||
              text.includes('List style')
            ) {
              const wrapper = label.closest('.components-base-control, .components-toggle-control, .components-panel__body, .components-item-group');
              if (wrapper) {
                wrapper.style.display = 'none';
              }
            }
          });
        }
      }, 50);
    }
  });
});
