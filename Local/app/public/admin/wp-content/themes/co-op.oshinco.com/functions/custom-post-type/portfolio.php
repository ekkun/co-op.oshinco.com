<?php
// カスタム投稿タイプ: ポートフォリオ (portfolio)

$labels = array(
  'name'               => 'ポートフォリオ',
  'singular_name'      => 'ポートフォリオ',
  'menu_name'          => 'ポートフォリオ',
  'all_items'          => 'ポートフォリオ一覧',
  'add_new'            => '新規追加',
  'add_new_item'       => '新規ポートフォリオを追加',
  'edit_item'          => 'ポートフォリオの編集',
  'new_item'           => '新規ポートフォリオ',
  'view_item'          => 'ポートフォリオを表示',
  'search_items'       => 'ポートフォリオを検索',
  'not_found'          => 'ポートフォリオが見つかりませんでした。',
  'not_found_in_trash' => 'ゴミ箱内にポートフォリオが見つかりませんでした。',
  'parent_item_colon'  => '親ポートフォリオ',
  'featured_image'        => 'アイキャッチ',
  'set_featured_image'    => 'アイキャッチを設定',
  'remove_featured_image' => 'アイキャッチを削除',
  'use_featured_image'    => 'アイキャッチとして使用する',
);
$args = array(
  'labels'              => $labels,
  'public'              => true,
  'publicly_queryable'  => true,
  'exclude_from_search' => false,
  'show_ui'             => true,
  'show_in_nav_menus'   => true,
  'has_archive'         => 'portfolio',
  'hierarchical'        => false,
  'rewrite'             => array('slug' => 'portfolio/-', 'with_front' => false, 'feeds' => false, 'pages' => true),
  'query_var'           => true,
  'can_export'          => true,
  'menu_position'       => 5,
  'menu_icon'           => 'dashicons-edit-page',
  'supports'            => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'page-attributes'),
  'show_in_rest'        => true,
  'taxonomies'          => array('portfolio_category', 'portfolio_tags'),
);
register_post_type('portfolio', $args);

// 個別ポートフォリオ: /portfolio/-/{post_id}/
add_rewrite_rule('portfolio/-/([0-9]+)/?$', 'index.php?post_type=portfolio&p=$matches[1]', 'top');

// カテゴリー
$labels = array(
  'name'                       => 'カテゴリー',
  'singular_name'              => 'カテゴリー',
  'menu_name'                  => 'カテゴリー',
  'all_items'                  => 'すべてのカテゴリー',
  'edit_item'                  => 'カテゴリーの編集',
  'view_item'                  => 'カテゴリーを表示',
  'update_item'                => 'カテゴリーを更新',
  'add_new_item'               => '新規カテゴリーを追加',
  'new_item_name'              => '新規カテゴリー名',
  'parent_item'                => '親カテゴリー',
  'parent_item_colon'          => '親カテゴリー：',
  'search_items'               => 'カテゴリーを検索',
  'popular_items'              => '人気のカテゴリー',
  'separate_items_with_commas' => 'カテゴリーが複数ある場合はコンマで区切ってください',
  'add_or_remove_items'        => 'カテゴリーの追加もしくは削除',
  'choose_from_most_used'      => 'よく使われているカテゴリーから選択',
  'not_found'                  => 'カテゴリーが見つかりませんでした。',
);
$args = array(
  'labels'            => $labels,
  'show_ui'           => true,
  'show_in_nav_menus' => false,
  'show_tagcloud'     => false,
  'show_admin_column' => true,
  'hierarchical'      => true,
  'query_var'         => true,
  'rewrite'           => array('slug' => 'portfolio/category', 'with_front' => false, 'hierarchical' => true),
  'sort'              => true,
  'show_in_rest'      => true,
);
register_taxonomy('portfolio_category', array('portfolio'), $args);

// リライトルール
add_rewrite_rule('portfolio/category/([^/]+)/?$', 'index.php?portfolio_category=$matches[1]', 'top');
add_rewrite_rule('portfolio/category/([^/]+)/page/([0-9]+)/?$', 'index.php?portfolio_category=$matches[1]&paged=$matches[2]', 'top');

// タグ
$labels = array(
  'name'                       => 'タグ',
  'singular_name'              => 'タグ',
  'menu_name'                  => 'タグ',
  'all_items'                  => 'すべてのタグ',
  'edit_item'                  => 'タグの編集',
  'view_item'                  => 'タグを表示',
  'update_item'                => 'タグを更新',
  'add_new_item'               => '新規タグを追加',
  'new_item_name'              => '新規タグ名',
  'parent_item'                => '親タグ',
  'parent_item_colon'          => '親タグ：',
  'search_items'               => 'タグを検索',
  'popular_items'              => '人気のタグ',
  'separate_items_with_commas' => 'タグが複数ある場合はコンマで区切ってください',
  'add_or_remove_items'        => 'タグの追加もしくは削除',
  'choose_from_most_used'      => 'よく使われているタグから選択',
  'not_found'                  => 'タグが見つかりませんでした。',
);
$args = array(
  'labels'            => $labels,
  'show_ui'           => true,
  'show_in_nav_menus' => false,
  'show_tagcloud'     => false,
  'show_admin_column' => true,
  'hierarchical'      => false,
  'query_var'         => true,
  'rewrite'           => array('slug' => 'portfolio/tags', 'with_front' => false, 'hierarchical' => true),
  'sort'              => true,
  'show_in_rest'      => true,
);
register_taxonomy('portfolio_tags', array('portfolio'), $args);

// リライトルール
add_rewrite_rule('portfolio/tags/([^/]+)/?$', 'index.php?portfolio_tags=$matches[1]', 'top');
add_rewrite_rule('portfolio/tags/([^/]+)/page/([0-9]+)/?$', 'index.php?portfolio_tags=$matches[1]&paged=$matches[2]', 'top');
