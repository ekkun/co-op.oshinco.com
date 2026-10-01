<?php
// taxonomy 固有テンプレートがあれば優先し、なければ共通一覧へフォールバック。
$taxonomy = sanitize_key(get_query_var('taxonomy'));
$template = $taxonomy ? get_template_directory() . '/templates/taxonomy/' . $taxonomy . '.php' : '';

require ($template && file_exists($template))
  ? $template
  : get_template_directory() . '/templates/archive/default.php';
