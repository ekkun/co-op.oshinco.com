<?php
if ($args) {
  $post = (empty($args['post'])) ? null : $args['post'];
  $type = (empty($args['type'])) ? null : $args['type'];
  $course_type = (empty($args['course_type'])) ? null : $args['course_type'];
  $course_type_terms = (empty($args['course_type_terms'])) ? null : $args['course_type_terms'];
  $taxonomy = (empty($args['taxonomy'])) ? null : $args['taxonomy'];
  $term_slug = (empty($args['term_slug'])) ? null : $args['term_slug'];
  $terms = (empty($args['terms'])) ? null : $args['terms'];
  $post_type_label = (empty($args['post_type_label'])) ? null : $args['post_type_label'];
}
if ($type == 'top') {
  $args = array(
    'post_type' => array($post),
    'post_status' => 'publish',
    'posts_per_page' => 12,
    'paged' => $paged,
    'orderby' => 'date',
    'order' => 'DESC',
  );
} elseif ($type == 'page') {
  $args = array(
    'post_type' => array($post),
    'post_status' => 'publish',
    'posts_per_page' => 12,
    'paged' => $paged,
    'tax_query' => array(
      'relation' => 'OR',
      array(
        'taxonomy' => $course_type,
        'field' => 'slug',
        'terms' => $term_slug,
        'operator' => 'AND',
      ),
    ),
    'orderby' => 'date',
    'order' => 'DESC',
  );
} else {
  $args = array(
    'post_type' => array($post),
    'post_status' => 'publish',
    'posts_per_page' => 12,
    'paged' => $paged,
    'tax_query' => array(
      'relation' => 'OR',
      array(
        'taxonomy' => $taxonomy,
        'field' => 'slug',
        'terms' => $term_slug,
        'operator' => 'AND',
      ),
    ),
    'orderby' => 'date',
    'order' => 'DESC',
  );
}
?>
<?php
$wp_query = new WP_Query($args);
if ($wp_query->have_posts()) {
?>
  <div class="p-news">
    <ul class="p-news__list">
      <?php get_template_part('templates/partial/news', 'loop', $args = array('taxonomy' => $taxonomy)); ?>
    </ul>
  </div>
  <?php
  if (function_exists('pagination')) {
    pagination($wp_query->max_num_pages, get_query_var('paged'));
  }
  ?>
  <?php /*if (function_exists('wp_pagenavi')) {
    wp_pagenavi();
  }*/ ?>
<?php
}
wp_reset_query();
?>