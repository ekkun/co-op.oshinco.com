<?php
// 不要なスタイルを削除する関数
function dequeue_unnecessary_styles() {
  // ブロックエディタ関連のスタイルを削除
  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('core-block-supports');
  //wp_dequeue_style('core-block-supports-duotone-inline-css');

  // グローバルスタイルを削除
  wp_dequeue_style('global-styles');

  // 不要なカスタムCSSを削除
  wp_dequeue_style('classic-theme-styles');
  //wp_dequeue_style('fsb-flexible-spacer-style');
  wp_dequeue_style('akismet-widget-style');
  //wp_deregister_style('toc-screen', plugins_url('/screen.min.css', __FILE__));
  wp_deregister_style('flexible-table-block', plugins_url('/build/style-index.css', __FILE__));
}

// スクリプト削除をフック
add_action('wp_enqueue_scripts', 'dequeue_unnecessary_styles', 9999);
add_action('wp_footer', 'dequeue_unnecessary_styles');
