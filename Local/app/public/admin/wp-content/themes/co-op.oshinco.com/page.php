<?php
global $post;
$slug = esc_html($post->post_name);
$contents = esc_html($post->post_content);
$template = dirname(__FILE__) . '/templates/page/' . $slug . '.php';

if (file_exists($template)) {
  require_once $template;
} else {
  //echo '"'. $slug .'" テンプレートが存在しません！';
  require_once dirname(__FILE__) . '/templates/page/default.php';
}
