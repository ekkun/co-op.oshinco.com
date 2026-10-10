<?php

// ヘッダーメタ削除
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles', 10);

// フィードリンク削除
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);

// 投稿（post）を管理画面メニューから非表示にする
function hide_posts_menu() {
  remove_menu_page('edit.php');
}
add_action('admin_menu', 'hide_posts_menu');

// 投稿（post）をフロントエンドのクエリから除外する
function exclude_posts_from_query($query) {
  if (is_admin() || !$query->is_main_query()) return;

  if ($query->is_home()) {
    // 旧「投稿」はポートフォリオへ移行し、トップではポートフォリオだけを見せる。
    $query->set('post_type', array('portfolio'));
  }

  if (
    $query->is_home()
    || $query->is_post_type_archive('portfolio')
    || $query->is_tax(array('portfolio_category', 'portfolio_tags'))
  ) {
    // ポートフォリオは公開日時の新しい順で表示する。
    $query->set('orderby', 'date');
    $query->set('order', 'DESC');
  }

  if (
    $query->is_home()
    || $query->is_post_type_archive(array('portfolio', 'news'))
    || $query->is_tax(array('portfolio_category', 'portfolio_tags', 'news_category', 'news_tags'))
  ) {
    $query->set('posts_per_page', 6);
  }
}
add_action('pre_get_posts', 'exclude_posts_from_query');

// カテゴリー、タグ機能を無効化
function remove_post_function() {
  unregister_taxonomy_for_object_type('category', 'post');
  unregister_taxonomy_for_object_type('post_tag', 'post');
}
add_action('init', 'remove_post_function');


// 省略時設定
function new_excerpt_more($more) {
  return '...';
}
add_filter('excerpt_more', 'new_excerpt_more');

// テーマセットアップ
if (!function_exists('coop_theme_setup')) :
  function coop_theme_setup() {
    // 自動フィード
    add_theme_support('automatic-feed-links');
    // アイキャッチ有効化
    add_theme_support('post-thumbnails');
    // set_post_thumbnail_size(88, 88, true);
    // add_image_size('post-image', 973, 9999);
    // add_image_size('post-thumb', 508, 9999);
    // ポストフォーマット
    add_theme_support('post-formats', array('gallery', 'image', 'video'));
    // カスタムロゴ
    add_theme_support('custom-logo', array(
      'height'      => 240,
      'width'       => 320,
      'flex-height' => true,
      'flex-width'  => true,
    ));
    // タイトルタグ
    add_theme_support('title-tag');
    // ブロックエディタ幅広サポート
    add_theme_support('align-wide');
    // ナビメニュー登録
    register_nav_menu('primary', __('Primary Menu', 'co-op-oshinco'));
  }
  add_action('after_setup_theme', 'coop_theme_setup');
endif;

// ウィジェットエリア。
function coop_sidebar_registration() {
  register_sidebar(array(
    'name'          => __('Sidebar', 'co-op-oshinco'),
    'id'            => 'sidebar',
    'description'   => __('Widgets shown in the fixed sidebar.', 'co-op-oshinco'),
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
    'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-content clear">',
    'after_widget'  => '</div></div>',
  ));
}
add_action('widgets_init', 'coop_sidebar_registration');

// Flickr公開フィード（404と固定フォトフッターで共用）。
function coop_get_flickr_feed_urls() {
  return array(
    'https://www.flickr.com/services/feeds/photos_public.gne?id=56004767@N00&lang=en-us&format=atom',
    'https://www.flickr.com/services/feeds/photos_public.gne?id=39138472@N08&lang=en-us&format=atom',
    'https://www.flickr.com/services/feeds/photos_public.gne?id=31655482@N00&lang=en-us&format=atom',
  );
}

/**
 * Flickr公開Atomフィードから写真を返す。
 *
 * @param string $feed_url Flickr公開フィードURL。
 * @return array<int, array{image: string, link: string, title: string}>
 */
function coop_get_flickr_feed_photos($feed_url) {
  require_once ABSPATH . WPINC . '/feed.php';

  $feed = fetch_feed($feed_url);
  if (is_wp_error($feed)) return array();

  $photos = array();
  foreach ($feed->get_items(0, 60) as $item) {
    $enclosure = $item->get_enclosure();
    $image_url = $enclosure ? $enclosure->get_link() : '';

    if (!$image_url) {
      $media = $item->get_item_tags('http://search.yahoo.com/mrss/', 'content');
      $image_url = isset($media[0]['attribs']['']['url']) ? $media[0]['attribs']['']['url'] : '';
    }

    if (!$image_url) continue;

    $photos[] = array(
      'image' => $image_url,
      'link'  => $item->get_permalink(),
      'title' => wp_strip_all_tags($item->get_title()),
    );
  }

  return $photos;
}

/**
 * 3つのFlickrフィードをまとめ、ランダムな写真を返す。
 *
 * @param int $limit 最大件数。
 * @return array<int, array{image: string, link: string, title: string}>
 */
function coop_get_flickr_photos($limit = 35) {
  static $photos = null;

  if (null === $photos) {
    $photos = array();
    foreach (coop_get_flickr_feed_urls() as $feed_url) {
      $photos = array_merge($photos, coop_get_flickr_feed_photos($feed_url));
    }
    shuffle($photos);
  }

  return array_slice($photos, 0, $limit);
}

// Viteで生成したフロントエンドJSを読み込む。
if (!function_exists('coop_enqueue_scripts')) :
  function coop_enqueue_scripts() {
    wp_enqueue_script('coop_app', get_template_directory_uri() . '/assets/js/main.js', array(), filemtime(get_template_directory() . '/assets/js/main.js'), true);
    if (is_singular()) wp_enqueue_script('comment-reply');
  }
  add_action('wp_enqueue_scripts', 'coop_enqueue_scripts');
endif;

// ViteのentryはES moduleとして読み込む。
function coop_vite_script_tag($tag, $handle, $src) {
  if ('coop_app' !== $handle) return $tag;
  return '<script type="module" src="' . esc_url($src) . '"></script>' . "\n";
}
add_filter('script_loader_tag', 'coop_vite_script_tag', 10, 3);

// Viteで生成したフロントエンドCSSを読み込む。
if (!function_exists('coop_enqueue_styles')) :
  function coop_enqueue_styles() {
    if (!is_admin()) {
      wp_enqueue_style('coop_app', get_theme_file_uri('/assets/css/style.css'), array(), filemtime(get_template_directory() . '/assets/css/style.css'));
    }
  }
  add_action('wp_enqueue_scripts', 'coop_enqueue_styles');
endif;

// 添付画像を標準ギャラリーと同じSplideマークアップで出力する。
function coop_attachment_gallery($size = 'thumbnail') {
  $images = get_posts(array(
    'numberposts'    => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_parent'    => get_the_ID(),
    'post_type'      => 'attachment',
    'post_status'    => 'inherit',
    'post_mime_type' => 'image',
  ));

  if (!$images) return;

  echo '<div class="wp-block-gallery">';
  foreach ($images as $image) {
    echo '<figure class="wp-block-image">' . wp_get_attachment_image($image->ID, $size) . '</figure>';
  }
  echo '</div>';
}

// ブロックエディター内で使用するスタイルを登録する。
if (!function_exists('coop_register_block_editor_style')) :
  function coop_register_block_editor_style() {
    add_editor_style('assets/css/style.css');
  }
  add_action('after_setup_theme', 'coop_register_block_editor_style');
endif;

// フロント用CSSはブロックエディター画面でのみ読み込む。
if (!function_exists('coop_enqueue_block_editor_style')) :
  function coop_enqueue_block_editor_style() {
    wp_enqueue_style('coop-block-editor-styles', get_theme_file_uri('/assets/css/style.css'), array(), filemtime(get_template_directory() . '/assets/css/style.css'), 'all');
  }
  add_action('enqueue_block_editor_assets', 'coop_enqueue_block_editor_style', 1);
endif;

// JS の no-js → js クラス切り替え
if (!function_exists('coop_html_js_class')) {
  function coop_html_js_class() {
    echo '<script>document.documentElement.className = document.documentElement.className.replace("no-js","js");</script>' . "\n";
  }
  add_action('wp_head', 'coop_html_js_class', 1);
}


// THUMBNAIL / TRIMMING
// add_image_size('mainvisual', 960, 540, true);      // MAIN VISUAL
// add_image_size('list', 640, 360, true);            // LIST
// add_image_size('list_small', 320, 180, true);      // LIST Small
// add_image_size('square', 600, 600, true);          // SQUARE
// add_image_size('square_small', 300, 300, true);    // SQUARE Small
// add_image_size('gallery', 1920, 1080, true);       // GALLERY

// 階層の深いタームオブジェクトを返す関数
/*function get_deepest_term($terms, $mytaxonomy, $myterm = null) {
  global $post;
  if ($myterm) {
    //$myterm が指定されていれば値からタームオブジェクトを生成
    $my_pref_term =  get_term_by('name', $myterm, $mytaxonomy);
    //タームオブジェクトが取得できて且つそのタームが現在の投稿に属していれば
    if ($my_pref_term && is_object_in_term($post->ID, $mytaxonomy, $my_pref_term->term_id)) {
      //優先的にそのタームを返す
      return $deepest =  $my_pref_term;
    }
  }
  //配列の要素が１つの場合その要素を最も深いタームとする
  if (count($terms) == 1) {
    $deepest = $terms[key($terms)];
  } else {
    $deepest = $terms[key($terms)];
    //祖先オブジェクトの最大数の初期化
    $max = 0;
    //それぞれのタームについて調査
    for ($i = 0; $i < count($terms); $i++) {
      //上の階層から順番に取得した祖先オブジェクトの ID の配列
      $ancestors = array_reverse(get_ancestors($terms[$i]->term_id, $terms[$i]->taxonomy));
      //祖先オブジェクトの数
      $ancestors_count = count($ancestors);
      //祖先オブジェクトの数を比較して最大数より大きければ
      if ($ancestors_count > $max) {
        //祖先オブジェクトの最大数を更新
        $max = $ancestors_count;
        //その要素を最も深いタームとする
        $deepest = $terms[$i];
      }
    }
  }
  return $deepest;
}*/

// CLASS追加 for IMG
function image_class_filter($class) {
  return $class . ' is-responsive-image';
}
add_filter('get_image_tag_class', 'image_class_filter');

// CLASS追加 for previous_post_link() & next_post_link()
/*function add_prev_post_link_class($output) {
  return str_replace('<a href=', '<a class="nav-links__prev nav-links__a" href=', $output);
}
add_filter('previous_post_link', 'add_prev_post_link_class');
function add_next_post_link_class($output) {
  return str_replace('<a href=', '<a class="nav-links__next nav-links__a" href=', $output);
}
add_filter('next_post_link', 'add_next_post_link_class');*/

// デフォルトjQueryを無効化（wp_dequeue_script はエンキュー後に呼ぶ必要があるため wp_enqueue_scripts フックで）
// function delete_jquery() {
//   if (!is_admin()) {
//     wp_dequeue_script('jquery');
//     wp_deregister_script('jquery');
//   }
// }
// add_action('wp_enqueue_scripts', 'delete_jquery');

// SVG, webpアップロード
function add_file_types_to_uploads($file_types) {
  $new_filetypes = array();
  $new_filetypes['svg'] = 'image/svg+xml';
  $new_filetypes['webp'] = 'image/webp';
  $file_types = array_merge($file_types, $new_filetypes);
  return $file_types;
}
add_action('upload_mimes', 'add_file_types_to_uploads');

// SVG, webp表示
add_filter('manage_media_columns', function ($columns) {
  echo '<style>
      .media-icon img[src$=".svg"],
      .media-icon img[src$=".webp"] { width: 100%; }
  </style>';
  return $columns;
});

// 自動整形の無効化
/*
function disabled_wpautop($content)
{
  global $post;
  $post_type = get_post_type($post->ID);
  $arr_types = array('others');
  if (in_array($post_type, $arr_types)) {
    remove_filter('the_content', 'wpautop');
    remove_filter('the_excerpt', 'wpautop');
  }
  return $content;
}
add_filter('the_content', 'disabled_wpautop', 1);
*/

// 記事IDを指定して抜粋を取得
/*function my_get_the_excerpt($post_id = null, $num_words = 55) {
  $post = $post_id ? get_post($post_id) : get_post(get_the_ID());
  $text = get_the_excerpt($post);
  if (!$text) {
    $text = get_post_field('post_content', $post);
  }
  $generated_excerpt = wp_trim_words($text, $num_words);
  return apply_filters('get_the_excerpt', $generated_excerpt, $post);
}*/

// 自動補完リダイレクト無効
function disable_redirect_canonical($redirect_url) {
  if (is_404()) {
    return false;
  }
  return $redirect_url;
}
add_filter('redirect_canonical', 'disable_redirect_canonical');

// 個別の添付ファイルページは公開せず、親投稿またはトップページへ転送する。
function coop_redirect_attachment_pages() {
  if (!is_attachment()) {
    return;
  }

  $attachment = get_queried_object();
  $redirect_url = !empty($attachment->post_parent)
    ? get_permalink($attachment->post_parent)
    : home_url('/');

  wp_safe_redirect($redirect_url, 301);
  exit;
}
add_action('template_redirect', 'coop_redirect_attachment_pages');

// Get_terms() 特定の投稿タイプのみcountの対象にする
/*function get_terms_clauses($clauses, $taxonomy, $args) {
  if (!empty($args['post_type'])) {
    global $wpdb;
    $post_types = array();
    if ($args['post_type']) {
      foreach ($args['post_type'] as $cpt) {
        $post_types[] = "'" . $cpt . "'";
      }
    }
    if (!empty($post_types)) {
      $clauses['fields'] = 'DISTINCT ' . str_replace('tt.*', 'tt.term_taxonomy_id, tt.term_id, tt.taxonomy, tt.description, tt.parent', $clauses['fields']) . ', COUNT(t.term_id) AS count';
      $clauses['join'] .= ' INNER JOIN ' . $wpdb->term_relationships . ' AS r ON r.term_taxonomy_id = tt.term_taxonomy_id INNER JOIN ' . $wpdb->posts . ' AS p ON p.ID = r.object_id';
      $clauses['where'] .= ' AND p.post_status = "publish" AND p.post_type IN (' . implode(',', $post_types) . ')';
      $clauses['orderby'] = 'GROUP BY t.term_id ' . $clauses['orderby'];
    }
  }
  return $clauses;
}
add_filter('terms_clauses', 'get_terms_clauses', 10, 3);*/

// iframeの遅延読み込みを無効
function disable_post_content_iframe_lazy_loading($default, $tag_name, $context) {
  if ('iframe' === $tag_name && 'the_content' === $context) {
    return false;
  }
  return $default;
}
add_filter('wp_lazy_loading_enabled', 'disable_post_content_iframe_lazy_loading', 10, 3);
