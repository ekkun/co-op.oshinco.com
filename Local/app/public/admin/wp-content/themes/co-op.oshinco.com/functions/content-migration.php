<?php
/**
 * 旧 post を case に一度だけ移行する。
 * ID・日時・本文・メディア関連を維持し、category / post_tag も同名の
 * case taxonomy へコピーする。
 */
function coop_migrate_posts_to_cases() {
  if (get_option('coop_posts_migrated_to_case')) return;

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

    wp_update_post(array('ID' => $post_id, 'post_type' => 'case'));

    foreach (array('case_category' => $categories, 'case_tags' => $tags) as $taxonomy => $terms) {
      if (is_wp_error($terms)) continue;
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

  update_option('coop_posts_migrated_to_case', array(
    'status' => 'done',
    'count'  => count($post_ids),
  ), false);
  flush_rewrite_rules(false);
}
// CPT と taxonomy が登録された後に一度だけ実行する。
add_action('init', 'coop_migrate_posts_to_cases', 20);
