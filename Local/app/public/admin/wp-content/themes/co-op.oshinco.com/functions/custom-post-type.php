<?php

// カスタム投稿
function my_custom_post_types()
{
  // #1 NEWS
  require_once ( dirname(__FILE__) . '/custom-post-type/news.php' );
  // #2 CASE（実績）
  require_once ( dirname(__FILE__) . '/custom-post-type/case.php' );
}
add_action( 'init', 'my_custom_post_types' );

// #1 NEWS
require_once ( dirname(__FILE__) . '/custom-post-type/add_custom_column_id_news.php' );

?>
