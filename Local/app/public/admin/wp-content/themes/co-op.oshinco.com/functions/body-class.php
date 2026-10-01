<?php
// BODY にタグ CLASS 付与
function add_slug_class_name($classes) {
  // if ( is_singular() ) {
  //   $page = get_post( get_the_ID() );
  //   $classes[] = $page->post_name;
  // }
  // if ( is_home() || is_front_page() ) {
  //   $classes[] = 'is-preload';
  // }
  if (in_array(get_post_type(), array('news')) | is_post_type_archive('news') | is_tax('news_category')) {
    $classes[] = 'news';
  }
  if (is_singular()) {
    $post_type = get_query_var('post_type');
    if (is_page()) {
      $post_type = 'page';
    }
    if ($post_type && is_post_type_hierarchical($post_type)) {
      global $post;
      $classes[] = esc_attr($post->post_name);
      if ($post->ancestors) {
        $root = $post->ancestors[count($post->ancestors) - 1];
        $root_post = get_post($root);
        $classes[] = esc_attr($root_post->post_name);
      }
    }
    $page = get_post(get_the_ID());
    $classes[] = $page->post_name;
  }
  return $classes;
}
add_filter('body_class', 'add_slug_class_name');

// 不要な CLASS 削除
function remove_body_class($wp_classes, $extra_classes) {
  $wp_classes = preg_grep("/single-|page-|template|logged-in|\d/", $wp_classes, PREG_GREP_INVERT);
  return array_merge($wp_classes, (array) $extra_classes);
}
add_filter('body_class', 'remove_body_class', 10, 2);
