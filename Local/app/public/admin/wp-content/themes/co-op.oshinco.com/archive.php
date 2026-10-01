<?php
// 投稿タイプ固有テンプレートを選択する。
$post_type_name = get_query_var('post_type');
$post_type_name = is_array($post_type_name) ? reset($post_type_name) : $post_type_name;
$post_type_name = $post_type_name ? sanitize_key($post_type_name) : '';

// 日付・著者など投稿タイプを持たないアーカイブは共通一覧を使う。
if (!$post_type_name) {
  require get_template_directory() . '/templates/archive/default.php';
  return;
}

$template = get_template_directory() . '/templates/archive/' . $post_type_name . '.php';

if (file_exists($template)) {
  require $template;
} else {
  echo '"' . esc_html($post_type_name) . '" のテンプレートが存在しません！';
}
