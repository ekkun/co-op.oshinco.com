<?php
// カスタム投稿タイプ: 実績 (case)

$labels = array(
  'name'               => '実績',
  'singular_name'      => '実績',
  'menu_name'          => '実績',
  'all_items'          => '実績一覧',
  'add_new'            => '新規追加',
  'add_new_item'       => '新規実績を追加',
  'edit_item'          => '実績の編集',
  'new_item'           => '新規実績',
  'view_item'          => '実績を表示',
  'search_items'       => '実績を検索',
  'not_found'          => '実績が見つかりませんでした。',
  'not_found_in_trash' => 'ゴミ箱内に実績が見つかりませんでした。',
  'parent_item_colon'  => '親実績',
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
  'has_archive'         => true,
  'hierarchical'        => false,
  'rewrite'             => array('slug' => 'case', 'with_front' => true, 'feeds' => false, 'pages' => true),
  'query_var'           => true,
  'can_export'          => true,
  'menu_position'       => 5,
  'menu_icon'           => 'dashicons-portfolio',
  'supports'            => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'page-attributes'),
  'show_in_rest'        => true,
  'taxonomies'          => array('case_category', 'case_tags'),
);
register_post_type('case', $args);

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
  'rewrite'           => array('slug' => 'case/category', 'with_front' => false, 'hierarchical' => true),
  'sort'              => true,
  'show_in_rest'      => true,
);
register_taxonomy('case_category', array('case'), $args);

// リライトルール
add_rewrite_rule('case/category/([^/]+)/?$', 'index.php?case_category=$matches[1]', 'top');
add_rewrite_rule('case/category/([^/]+)/page/([0-9]+)/?$', 'index.php?case_category=$matches[1]&paged=$matches[2]', 'top');

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
  'rewrite'           => array('slug' => 'case/tags', 'with_front' => false, 'hierarchical' => true),
  'sort'              => true,
  'show_in_rest'      => true,
);
register_taxonomy('case_tags', array('case'), $args);

// リライトルール
add_rewrite_rule('case/tags/([^/]+)/?$', 'index.php?case_tags=$matches[1]', 'top');
add_rewrite_rule('case/tags/([^/]+)/page/([0-9]+)/?$', 'index.php?case_tags=$matches[1]&paged=$matches[2]', 'top');
