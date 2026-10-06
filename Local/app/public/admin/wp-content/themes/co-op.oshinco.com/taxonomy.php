<?php
$taxonomy = sanitize_key(get_query_var('taxonomy'));

if (str_starts_with($taxonomy, 'news_')) {
  require get_template_directory() . '/templates/archive/news.php';
  return;
}

if (str_starts_with($taxonomy, 'case_')) {
  require get_template_directory() . '/templates/archive/case.php';
  return;
}

require get_template_directory() . '/templates/archive/default.php';
