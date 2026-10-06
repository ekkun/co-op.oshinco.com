<?php
// テーマ側でブロックスタイルを管理するため、コアブロックCSSの個別出力を無効化する。
add_filter('should_load_separate_core_block_assets', '__return_false', 100);
add_filter('should_load_block_assets_on_demand', '__return_false', 100);

// global-styles-inline-css は出力せず、必要なパレット色は要素の style 属性へ反映する。
function coop_remove_global_styles() {
  remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
  remove_action('wp_footer', 'wp_enqueue_global_styles', 1);
}
add_action('after_setup_theme', 'coop_remove_global_styles', 100);

function coop_apply_palette_colors_inline($block_content) {
  if (!str_contains($block_content, 'has-inline-color') || !class_exists('WP_HTML_Tag_Processor')) {
    return $block_content;
  }

  static $palette = null;
  if ($palette === null) {
    $palette = array();
    $theme_json_path = get_theme_file_path('/theme.json');
    $theme_json = is_readable($theme_json_path)
      ? json_decode(file_get_contents($theme_json_path), true)
      : array();

    foreach (($theme_json['settings']['color']['palette'] ?? array()) as $color) {
      if (!empty($color['slug']) && !empty($color['color'])) {
        $palette[$color['slug']] = $color['color'];
      }
    }
  }

  $processor = new WP_HTML_Tag_Processor($block_content);
  while ($processor->next_tag()) {
    $classes = (string) $processor->get_attribute('class');
    if (!str_contains($classes, 'has-inline-color')) {
      continue;
    }

    if (!preg_match_all('/(?:^|\s)has-([a-z0-9-]+)-color(?=\s|$)/', $classes, $matches)) {
      continue;
    }

    $color = null;
    foreach ($matches[1] as $slug) {
      if (isset($palette[$slug])) {
        $color = $palette[$slug];
        break;
      }
    }
    if (!$color) {
      continue;
    }

    $style = trim((string) $processor->get_attribute('style'));
    $style = preg_replace('/(?:^|;)\s*color\s*:[^;]*/i', '', $style);
    $style = trim($style, " ;\t\n\r\0\x0B");
    $processor->set_attribute('style', ($style ? $style . '; ' : '') . 'color: ' . $color);
  }

  return $processor->get_updated_html();
}
add_filter('render_block', 'coop_apply_palette_colors_inline', 10, 1);

// 不要なスタイルを削除する関数
function dequeue_unnecessary_styles() {
  // ブロックエディタ関連のスタイルを削除
  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('core-block-supports');
  //wp_dequeue_style('core-block-supports-duotone-inline-css');

  // グローバルスタイルを削除
  wp_dequeue_style('global-styles');
  wp_deregister_style('global-styles');

  // wp-block-***-inline-css を含むコアブロックのスタイルを削除
  global $wp_styles;
  if ($wp_styles instanceof WP_Styles) {
    foreach (array_keys($wp_styles->registered) as $handle) {
      if (str_starts_with($handle, 'wp-block-')) {
        wp_dequeue_style($handle);
      }
    }
  }

  // 不要なカスタムCSSを削除
  wp_dequeue_style('classic-theme-styles');
  //wp_dequeue_style('fsb-flexible-spacer-style');
  wp_dequeue_style('akismet-widget-style');
  //wp_deregister_style('toc-screen', plugins_url('/screen.min.css', __FILE__));
  wp_deregister_style('flexible-table-block', plugins_url('/build/style-index.css', __FILE__));
}

// スクリプト削除をフック
add_action('wp_enqueue_scripts', 'dequeue_unnecessary_styles', 9999);
add_action('wp_print_styles', 'dequeue_unnecessary_styles', 9999);
add_action('wp_footer', 'dequeue_unnecessary_styles', 0);
