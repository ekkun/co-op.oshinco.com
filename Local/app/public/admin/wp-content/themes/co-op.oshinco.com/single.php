<?php
// 投稿タイプ固有テンプレートを選択する。
$post_type_name = sanitize_key(get_post_type());
$template = get_template_directory() . '/templates/single/' . $post_type_name . '.php';

if (file_exists($template)) {
  require $template;
} else {
  echo '"' . esc_html($post_type_name) . '" のテンプレートが存在しません！';
}
