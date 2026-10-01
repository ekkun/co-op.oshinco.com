<?php
/**
 * メインインデックス / ホームテンプレート
 * co-op.oshinco.com テーマ
 */

// フロントページ（固定ページをホームに設定している場合）は page.php が使われる
// このファイルはブログインデックスページ用
$template = dirname(__FILE__) . '/templates/archive/post.php';

if (file_exists($template)) {
  require_once $template;
} else {
  // fallback: fukasawa スタイルのシンプル表示
  get_header();
  if (have_posts()) :
    while (have_posts()) : the_post();
      get_template_part('content');
    endwhile;
  endif;
  get_footer();
}
