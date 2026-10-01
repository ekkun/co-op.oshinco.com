<?php
global $post;
$slug = sanitize_key($post->post_name);
$template = dirname(__FILE__) . '/templates/page/' . $slug . '.php';

if (file_exists($template)) {
  require_once $template;
} else {
  // 固定ページも Fukasawa の読みやすい1カラム本文レイアウトを使う。
  require get_template_directory() . '/templates/single/default.php';
}
