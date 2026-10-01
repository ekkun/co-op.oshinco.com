<?php
// カスタムカラーピッカーを無効
add_theme_support('disable-custom-colors');

// 使わないブロックを登録する
function my_theme_enqueue_block_remove_block() {
  wp_enqueue_script('remove-block', get_template_directory_uri() . '/js/remove-block.js', array(), false, true);
}
add_action('enqueue_block_editor_assets', 'my_theme_enqueue_block_remove_block');

// ブロックスタイルを無効化する
function my_theme_enqueue_block_remove_editor() {
  wp_enqueue_script('remove-editor', get_template_directory_uri() . '/js/remove-editor.js', array('wp-blocks', 'wp-dom', 'wp-dom-ready', 'wp-edit-post'), wp_get_theme()->get('Version'), true);
}
add_action('enqueue_block_editor_assets', 'my_theme_enqueue_block_remove_editor');

// Openverse 無効化
function my_theme_enable_openverse_media_category($settings) {
  $settings['enableOpenverseMediaCategory'] = false;
  return $settings;
}
add_filter('block_editor_settings_all', 'my_theme_enable_openverse_media_category', 10);

// WordPressのブロックパターンを削除する
add_filter('should_load_remote_block_patterns', function () {
  return false;
});
function my_unregister_block_pattern() {
  $patterns = [
    'core/query-standard-posts',
    'core/query-medium-posts',
    'core/query-small-posts',
    'core/query-grid-posts',
    'core/query-large-title-posts',
    'core/query-offset-posts',
    //'core/social-links-shared-background-color',
  ];
  foreach ($patterns as $pattern) {
    unregister_block_pattern($pattern);
  }
}
add_action('init', 'my_unregister_block_pattern');

// WordPressのブロックパターンカテゴリを削除する
function my_unregister_block_pattern_category() {
  $categories = [
    'buttons',
    'columns',
    'featured',
    'footer',
    'gallery',
    'header',
    'text',
    'query',
  ];
  foreach ($categories as $category) {
    unregister_block_pattern_category($category);
  }
}
add_action('init', 'my_unregister_block_pattern_category');
