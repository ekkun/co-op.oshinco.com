<?php
if ($args) {
  $taxonomy = (empty($args['taxonomy'])) ? null : $args['taxonomy'];
  $wp_query = (empty($args['wp_query'])) ? $wp_query : $args['wp_query'];
}
while ($wp_query->have_posts()) {
  $wp_query->the_post();
  $news_url = get_field('news_url');
  $news_old_url = get_field('url');
  $news_redirect_url = ($news_url) ? $news_url : $news_old_url;
  $news_external_link = get_field('news_external-link');
  $news_external_link = ($news_external_link) ? $news_external_link[0]['value'] : null;
  $news_old_window_target = get_field('window_target');
  $news_old_article = apply_filters('the_content', get_post_meta($post->ID, 'news_article', true));
  if ($news_redirect_url) {
    $permalink = $news_redirect_url;
    //if ($news_external_link || $news_old_window_target == '_blank') {
    //  header('Location: '. $news_redirect_url .'');
    //  exit();
    //}
  } else {
    $permalink = get_permalink();
  }
  $term = '';
  if (class_exists('WPSEO_Primary_Term')) {
    $wpseo_primary_term = new WPSEO_Primary_Term($taxonomy, get_the_ID());
    $primary_term_id = $wpseo_primary_term->get_primary_term();
    if ($primary_term_id && !is_wp_error($primary_term_id)) {
      $term = get_term($primary_term_id);
    }
  }
  if (empty($term) || is_wp_error($term)) {
    $terms = get_the_terms(get_the_ID(), $taxonomy);
    if ($terms && !is_wp_error($terms)) {
      $term = $terms[0];
    } else {
      $term = null;
    }
  }
  if ($term && is_object($term)) {
    $term_name = $term->name;
    $term_slug = $term->slug;
    $term_link = get_term_link($term->slug, $taxonomy);
  } else {
    $term_name = null;
    $term_slug = null;
    $term_link = null;
  }
?>
  <li class="p-news__item" <?php /*id="news-<?php the_ID(); ?>"*/ ?>>
    <a href="<?php echo $permalink; ?>" <?php if ($news_external_link || $news_old_window_target == '_blank') { ?> target="_blank" rel="noopener" <?php } ?>>
      <div class="p-news__meta">
        <time class="p-news__meta-datetime" datetime="<?php echo get_post_time('Y-m-d'); ?>"><?php echo get_post_time('Y.m.d'); ?></time>
        <div class="p-news__meta-category"><span class="c-label c-label-news"><?php echo $term->name; ?></span></div>
      </div>
      <p><?php the_title(); ?></p>
    </a>
  </li>
<?php
}
?>