<?php
// 管理画面共通CSS
function coop_enqueue_admin_styles() {
  $admin_style_path = get_theme_file_path('/css/admin-style.css');

  wp_enqueue_style(
    'coop-admin-style',
    get_theme_file_uri('/css/admin-style.css'),
    array(),
    is_readable($admin_style_path) ? filemtime($admin_style_path) : null
  );
}
add_action('admin_enqueue_scripts', 'coop_enqueue_admin_styles');

// 投稿の新規追加・編集画面に専用CSSとJSを読み込む
function my_new_post_styles($hook_suffix) {
  if ($hook_suffix == 'post-new.php' || $hook_suffix == 'post.php') {
    $post_admin_style_path = get_theme_file_path('/css/admin-post-style.css');

    wp_enqueue_style(
      'coop-admin-post-style',
      get_theme_file_uri('/css/admin-post-style.css'),
      array('coop-admin-style'),
      is_readable($post_admin_style_path) ? filemtime($post_admin_style_path) : null
    );

    wp_enqueue_script(
      'admin-script-js',
      get_template_directory_uri() . '/js/admin-script.js',
      //array(),
      array('wp-dom-ready', 'wp-edit-post'),
      '1.0.0',
    );
  }
}
add_action('admin_enqueue_scripts', 'my_new_post_styles');

function my_front_style() {
  //wp_enqueue_style('my_front_style', home_url() . '/assets/css/style.css');
  //wp_enqueue_style('my_admin_style', get_template_directory_uri() . '/css/admin-style.css');
}
add_action('admin_head-post.php', 'my_front_style');

function my_front_widget() {
  wp_enqueue_style('my_front_widget', home_url() . '/assets/css/style.css');
}
add_action('load-widgets.php', 'my_front_widget');

// 新規投稿画面にJS読み込む
function enqueue_admin_scripts() {
  wp_enqueue_script(
    'admin-script-js',
    get_template_directory_uri() . '/js/admin-script.js',
    //array(),
    array('wp-dom-ready', 'wp-edit-post'),
    '1.0.0',
  );
}
add_action('enqueue_block_assets', 'enqueue_admin_scripts');
