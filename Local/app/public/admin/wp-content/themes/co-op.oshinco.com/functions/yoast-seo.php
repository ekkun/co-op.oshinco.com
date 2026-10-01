<?php
// Yoast SEO コメント非表示
add_filter('wpseo_debug_markers', '__return_false');

// 対象の投稿タイプとタクソノミーをまとめた配列
$target_types = array(
  'post_types' => array('page', 'news'),
  'taxonomies' => array(
    'news_category',
    'news_tag',
  )
);

// Yoast SEO カスタム投稿タイプのアーカイブページの og:type を変更
function my_custom_og_type_for_custom_post_type_archive($type) {
  global $target_types;
  if (is_post_type_archive($target_types['post_types'])) {
    $type = 'article';
  }
  return $type;
}
add_filter('wpseo_opengraph_type', 'my_custom_og_type_for_custom_post_type_archive');

// Yoast SEO から rel="next/prev" の除去
function my_disable_yoast_rel_next_prev($link) {
  global $target_types;
  if (is_post_type_archive($target_types['post_types']) || is_tax($target_types['taxonomies'])) {
    return false;
  }
  return $link;
}
add_filter('wpseo_next_rel_link', 'my_disable_yoast_rel_next_prev');
add_filter('wpseo_prev_rel_link', 'my_disable_yoast_rel_next_prev');

// Yoast SEO ページ送り 2 ページ目以降に noindex を付与
function my_add_noindex_to_paged_archives($robots) {
  if (is_paged()) {
    return 'noindex, follow';
  }
  return $robots;
}
add_filter('wpseo_robots', 'my_add_noindex_to_paged_archives');
