<?php
// 検索機能を無効化
/*function disable_search() {
  if (is_search() && !is_admin()) {
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    get_template_part('404');
    exit();
  }
}
add_action('template_redirect', 'disable_search');*/

// 全角半角英語、片仮名平仮名検索
function zenkaku_hankaku_kensaku($where, $obj) {
  if ($obj->is_search) {
    $where = str_replace(".post_title", ".post_title COLLATE utf8_unicode_ci", $where);
    $where = str_replace(".post_content", ".post_content COLLATE utf8_unicode_ci", $where);
  }
  return $where;
}
add_filter('posts_where', 'zenkaku_hankaku_kensaku', 10, 2);
