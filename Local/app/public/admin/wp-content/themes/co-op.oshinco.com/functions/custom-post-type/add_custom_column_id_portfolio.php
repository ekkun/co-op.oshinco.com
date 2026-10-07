<?php
/**
 * 管理画面のポートフォリオ一覧にサムネイル列を追加する。
 */

function add_custom_column_id_portfolio_thumb($column_name, $post_id)
{
  if ('thumbnail' !== $column_name) {
    return;
  }

  $thumb = get_the_post_thumbnail($post_id, array(100, 100), 'thumbnail');
  echo ($thumb) ? $thumb : '─';
}
add_action('manage_portfolio_posts_custom_column', 'add_custom_column_id_portfolio_thumb', 10, 2);

function add_custom_column_portfolio_thumb($columns)
{
  $columns['thumbnail'] = 'サムネイル';
  return $columns;
}
add_filter('manage_edit-portfolio_columns', 'add_custom_column_portfolio_thumb');
