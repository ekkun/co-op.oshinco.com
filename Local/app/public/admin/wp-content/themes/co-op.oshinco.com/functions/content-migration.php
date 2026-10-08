<?php
/**
 * 旧 case の投稿タイプ・タクソノミーを portfolio へ一度だけ移行する。
 */
function coop_migrate_cases_to_portfolio()
{
  if (get_option('coop_cases_migrated_to_portfolio')) {
    return;
  }

  global $wpdb;

  $post_ids = $wpdb->get_col(
    "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'case'"
  );

  $wpdb->update(
    $wpdb->posts,
    array('post_type' => 'portfolio'),
    array('post_type' => 'case'),
    array('%s'),
    array('%s')
  );
  $wpdb->update(
    $wpdb->term_taxonomy,
    array('taxonomy' => 'portfolio_category'),
    array('taxonomy' => 'case_category'),
    array('%s'),
    array('%s')
  );
  $wpdb->update(
    $wpdb->term_taxonomy,
    array('taxonomy' => 'portfolio_tags'),
    array('taxonomy' => 'case_tags'),
    array('%s'),
    array('%s')
  );

  // ナビゲーションメニューに登録済みの投稿・ターム参照も引き継ぐ。
  foreach (array(
    'case' => 'portfolio',
    'case_category' => 'portfolio_category',
    'case_tags' => 'portfolio_tags',
  ) as $old_object => $new_object) {
    $wpdb->update(
      $wpdb->postmeta,
      array('meta_value' => $new_object),
      array(
        'meta_key' => '_menu_item_object',
        'meta_value' => $old_object,
      ),
      array('%s'),
      array('%s', '%s')
    );
  }

  foreach ($post_ids as $post_id) {
    clean_post_cache((int) $post_id);
  }

  update_option('coop_cases_migrated_to_portfolio', array(
    'status' => 'done',
    'count'  => count($post_ids),
  ), false);
}
add_action('init', 'coop_migrate_cases_to_portfolio', 15);

/**
 * 旧「投稿」をポートフォリオへ一度だけ移行する。
 * ID・日時・本文・メディア関連を維持し、category / post_tag も同名の
 * portfolio taxonomy へコピーする。
 */
function coop_migrate_posts_to_portfolio()
{
  if (get_option('coop_posts_migrated_to_portfolio')) {
    return;
  }

  $post_ids = get_posts(array(
    'post_type'        => 'post',
    'post_status'      => array('publish', 'draft', 'pending', 'private', 'future'),
    'posts_per_page'   => -1,
    'fields'           => 'ids',
    'suppress_filters' => true,
  ));

  foreach ($post_ids as $post_id) {
    $categories = wp_get_post_terms($post_id, 'category');
    $tags       = wp_get_post_terms($post_id, 'post_tag');

    wp_update_post(array('ID' => $post_id, 'post_type' => 'portfolio'));

    foreach (array('portfolio_category' => $categories, 'portfolio_tags' => $tags) as $taxonomy => $terms) {
      if (is_wp_error($terms)) {
        continue;
      }

      foreach ($terms as $term) {
        $target = term_exists($term->slug, $taxonomy);
        if (!$target) {
          $target = wp_insert_term($term->name, $taxonomy, array(
            'slug'        => $term->slug,
            'description' => $term->description,
          ));
        }

        if (!is_wp_error($target)) {
          $term_id = is_array($target) ? $target['term_id'] : $target;
          wp_set_object_terms($post_id, array((int) $term_id), $taxonomy, true);
        }
      }
    }
  }

  update_option('coop_posts_migrated_to_portfolio', array(
    'status' => 'done',
    'count'  => count($post_ids),
  ), false);
}
// CPT と taxonomy が登録された後に一度だけ実行する。
add_action('init', 'coop_migrate_posts_to_portfolio', 20);
