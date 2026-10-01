<?php

/**
 * ACFフィールドを配列で一括取得
 *
 * @param array $fields フィールド名とデフォルト値のペア
 * @param int|null $post_id 投稿ID（省略時は現在の投稿）
 * @return array フィールド値の配列
 */
function get_acf_fields(array $fields, $post_id = null) {
  $post_id = $post_id ?: get_the_ID(); // 現在の投稿IDを使用
  $data = [];
  foreach ($fields as $field => $default) {
    $data[$field] = get_field($field, $post_id) ?: $default;
  }
  return $data;
}

// https://deep-space.blue/web/2963
// https://envydesign.jp/blog/2024/03/acf-pro_custom_block/#2blockjsonwo_shiu_fang_fa
function register_acf_blocks() {
  register_block_type(dirname(__DIR__) . '/blocks/accordion');
}
add_action('init', 'register_acf_blocks');

// カテゴリー追加
/*function add_acf_block_category($categories) {
  $new_category = [
    'slug' => 'acf-block',
    'title' => 'ACFブロック',
  ];
  array_splice($categories, 1, 0, [$new_category]);
  return $categories;
}
add_filter('block_categories_all', 'add_acf_block_category');*/
