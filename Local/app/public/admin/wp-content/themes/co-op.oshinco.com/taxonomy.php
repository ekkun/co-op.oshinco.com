<?php
// タクソノミー情報取得
$labels = get_taxonomy($taxonomy);
$taxonomy_name = esc_html($labels->name);
$taxonomy_label = esc_html($labels->label);
$template = dirname(__FILE__) . '/templates/taxonomy/' . $taxonomy_name . '.php';

// クエリ・オブジェクト取得
$queried_object = get_queried_object();
//var_dump($queried_object);

$term_id = $queried_object->term_id;
$term_name = $queried_object->name;
$term_slug = $queried_object->slug;
$term_description = $queried_object->description;
$term_taxonomy = $queried_object->taxonomy;
$term_parent = $queried_object->parent;

if (file_exists($template)) {
  require_once $template;
} else {
  echo '"' . $taxonomy_name . '" テンプレートが存在しません！';
}
