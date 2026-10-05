<?php
global $post;
$slug = sanitize_key($post->post_name);
$template = dirname(__FILE__) . '/templates/page/' . $slug . '.php';

if (file_exists($template)) {
  require_once $template;
} else {
  require get_template_directory() . '/templates/single/default.php';
}
