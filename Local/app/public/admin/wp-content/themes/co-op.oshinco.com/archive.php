<?php
$post_type_object = get_post_type_object(get_post_type());
$post_type_name = $post_type_object ? esc_html($post_type_object->name) : '';
$post_type_label = $post_type_object ? esc_html($post_type_object->label) : '';
if (!$post_type_name) {
  $post_type_name = esc_html(get_query_var('post_type'));
}
$template = dirname(__FILE__) . '/templates/archive/' . $post_type_name . '.php';

// カテゴリー
if (is_category()) {
  $template = dirname(__FILE__) . '/templates/archive/category.php';
}

// タグ
if (is_tag()) {
  $template = dirname(__FILE__) . '/templates/archive/tag.php';
}

if (file_exists($template)) {
  require_once $template;
} else {
  echo '"' . $post_type_name . '" テンプレートが存在しません！';
}
