<?php
if ($args) {
  $taxonomy = (empty($args['taxonomy'])) ? null : $args['taxonomy'];
}
$terms = get_the_terms(get_the_ID(), $taxonomy);
$news_category = array();
//var_dump($terms);
if ($terms) {
  foreach ($terms as $term) {
    $news_category[] = $term->slug;
  }
}
//echo get_the_ID();
$post_id = get_the_ID();
$had_posts = array($post_id);
$args = array(
  'post_type' => 'news',
  'post_status' => 'publish',
  'posts_per_page' => 3,
  'post__not_in' => array($post_id),
  'tax_query' => array(
    array(
      'taxonomy' => $taxonomy,
      'field' => 'slug',
      'terms' => $news_category,
    ),
  ),
  'orderby' => 'date',
  'order' => 'DESC',
);
$wp_query = new WP_Query($args);
if ($wp_query->have_posts()) {
?>
  <h2>関連するお知らせ</h2>
  <div class="p-news">
    <ul class="p-news__list">
      <?php get_template_part('templates/partial/news', 'loop', $args = array('taxonomy' => $taxonomy)); ?>
    </ul>
  </div>
  <div class="is-horizontal is-content-justification-space-between is-layout-flex wp-container-39 wp-block-buttons">
    <div class="wp-block-button is-style-outline"><a href="<?php echo home_url(); ?>/news/" class="wp-block-button__link wp-element-button">一覧へ戻る</a></div>
  </div>
<?php
}
wp_reset_query();
?>