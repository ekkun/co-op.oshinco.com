<?php
/**
 * 日本語の投稿タイトルから、外部APIを使わずに英字スラッグを生成する。
 */

/**
 * Slug Automator のAI生成処理を、このテーマでは無効にする。
 */
function coop_disable_slug_automator_ai_hooks()
{
  global $wp_filter;

  $hooks = array(
    'wp_insert_post_data',
    'admin_notices',
    'enqueue_block_editor_assets',
    'wp_abilities_api_categories_init',
    'wp_abilities_api_init',
  );

  foreach ($hooks as $hook_name) {
    if (empty($wp_filter[$hook_name]) || empty($wp_filter[$hook_name]->callbacks)) {
      continue;
    }

    foreach ($wp_filter[$hook_name]->callbacks as $priority => $callbacks) {
      foreach ($callbacks as $callback) {
        $function = $callback['function'] ?? null;
        if (!is_array($function) || !is_object($function[0])) {
          continue;
        }

        if (str_starts_with(get_class($function[0]), 'Slug_Automator\\')) {
          remove_filter($hook_name, $function, $priority);
        }
      }
    }
  }
}
add_action('after_setup_theme', 'coop_disable_slug_automator_ai_hooks', 1);

/**
 * 文字列をローマ字スラッグへ変換する。
 *
 * 漢字の意味はローカル処理だけでは翻訳できないため、漢字を含む場合は失敗扱いにする。
 */
function coop_romanize_slug($title)
{
  if (!is_string($title) || '' === trim($title)) {
    return '';
  }

  if (preg_match('/\p{Han}/u', $title)) {
    return '';
  }

  $romanized = $title;

  if (class_exists('Transliterator')) {
    $transliterator = Transliterator::create('Hiragana-Latin; Katakana-Latin; Latin-ASCII; Lower()');
    if ($transliterator) {
      $romanized = $transliterator->transliterate($title);
    }
  }

  // 変換後にも非ASCII文字が残る場合は、不完全なスラッグを採用しない。
  if (preg_match('/[^\x00-\x7F]/', $romanized)) {
    return '';
  }

  return sanitize_title($romanized);
}

/**
 * ニュースとポートフォリオには8桁ゼロ埋めの投稿IDを使う。
 * その他の日本語スラッグはローマ字へ置換し、変換不能なら同じ形式の投稿IDを使う。
 */
function coop_replace_japanese_post_slug($post_id, $post, $update, $post_before)
{
  if (!$post instanceof WP_Post || wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
    return;
  }

  if (!in_array($post->post_type, array('post', 'page', 'portfolio', 'news'), true)) {
    return;
  }

  if (in_array($post->post_status, array('auto-draft', 'inherit', 'trash'), true)) {
    return;
  }

  if (in_array($post->post_type, array('portfolio', 'news'), true)) {
    $slug = str_pad((string) $post_id, 8, '0', STR_PAD_LEFT);
  } else {
    $decoded_slug = rawurldecode((string) $post->post_name);
    if ('' !== $decoded_slug && !preg_match('/[^\x00-\x7F]/', $decoded_slug)) {
      return;
    }

    $slug = coop_romanize_slug($post->post_title);
    if ('' === $slug) {
      $slug = str_pad((string) $post_id, 8, '0', STR_PAD_LEFT);
    }
  }

  $slug = wp_unique_post_slug(
    $slug,
    $post_id,
    $post->post_status,
    $post->post_type,
    (int) $post->post_parent
  );

  if ($slug === $post->post_name) {
    return;
  }

  global $wpdb;
  $wpdb->update(
    $wpdb->posts,
    array('post_name' => $slug),
    array('ID' => $post_id),
    array('%s'),
    array('%d')
  );
  clean_post_cache($post_id);
}
add_action('wp_after_insert_post', 'coop_replace_japanese_post_slug', 10, 4);

/**
 * ニュースとポートフォリオの公開URLを投稿IDベースに統一する。
 */
function coop_post_type_id_permalink($post_link, $post)
{
  $bases = array(
    'news' => 'news',
    'portfolio' => 'portfolio',
  );

  if (!$post instanceof WP_Post || !isset($bases[$post->post_type])) {
    return $post_link;
  }

  $post_id = str_pad((string) $post->ID, 8, '0', STR_PAD_LEFT);
  $path = $bases[$post->post_type] . '/-/' . $post_id;

  return home_url(user_trailingslashit($path));
}
add_filter('post_type_link', 'coop_post_type_id_permalink', 10, 2);

/**
 * 今回のURL構造変更を一度だけリライトルールへ反映する。
 */
function coop_flush_portfolio_rewrite_rules_once()
{
  $rewrite_version = 'portfolio-post-type-v2';

  if ($rewrite_version === get_option('coop_rewrite_version')) {
    return;
  }

  flush_rewrite_rules(false);
  update_option('coop_rewrite_version', $rewrite_version, false);
}
add_action('init', 'coop_flush_portfolio_rewrite_rules_once', 99);
