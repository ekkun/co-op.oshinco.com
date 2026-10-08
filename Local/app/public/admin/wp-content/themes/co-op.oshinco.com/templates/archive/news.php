<?php get_header(); ?>

<div class="coop-archive coop-news-archive">
  <section class="coop-news-list" aria-labelledby="coop-archive-title" data-archive-infinite-scroll data-container-selector=".coop-news-list__items" data-item-selector=".coop-news-list__item" data-next-page="<?php echo esc_url(get_next_posts_page_link()); ?>">
    <header class="coop-archive__header<?php echo have_posts() ? ' has-items' : ''; ?>">
      <h1 class="coop-archive__title" id="coop-archive-title">
        <?php
        if (is_tax()) {
          $term = get_queried_object();
          echo esc_html($term->name . ' / NEWS');
        } else {
          echo 'NEWS';
        }
        ?>
      </h1>
      <?php if (get_the_archive_description()) : ?>
        <div class="coop-archive__description"><?php echo wp_kses_post(wpautop(get_the_archive_description())); ?></div>
      <?php endif; ?>
    </header>

    <?php if (have_posts()) : ?>
      <div class="coop-news-list__items">
        <?php while (have_posts()) : the_post();
          $news_url = function_exists('get_field') ? get_field('news_url') : '';
          $legacy_url = function_exists('get_field') ? get_field('url') : '';
          $permalink = $news_url ?: ($legacy_url ?: get_permalink());
          $external_link = function_exists('get_field') ? get_field('news_external-link') : null;
          $legacy_target = function_exists('get_field') ? get_field('window_target') : '';
          $open_new_window = !empty($external_link) || '_blank' === $legacy_target;
          $terms = get_the_terms(get_the_ID(), 'news_category');
          $category = $terms && !is_wp_error($terms) ? reset($terms) : null;
        ?>
          <article <?php post_class('coop-news-list__item'); ?>>
            <a class="coop-news-list__link" href="<?php echo esc_url($permalink); ?>"<?php echo $open_new_window ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
              <time class="coop-news-list__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('Y.m.d')); ?></time>
              <span class="coop-news-list__category"><?php echo esc_html($category ? $category->name : 'NEWS'); ?></span>
              <h2 class="coop-news-list__title"><?php the_title(); ?></h2>
              <span class="coop-news-list__arrow" aria-hidden="true"></span>
            </a>
          </article>
        <?php endwhile; ?>
      </div>
      <?php if ($GLOBALS['wp_query']->max_num_pages > 1) : ?><div class="coop-infinite-scroll" aria-live="polite" aria-busy="false"><span class="coop-infinite-scroll__status"><span class="coop-infinite-scroll__spinner" aria-hidden="true"></span><span class="coop-infinite-scroll__label">Loading more articles...</span></span></div><?php endif; ?>
    <?php else : ?>
      <p class="coop-archive__empty">公開中のニュースはありません。</p>
    <?php endif; ?>
  </section>
</div>

<?php get_footer(); ?>
