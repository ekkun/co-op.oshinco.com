<?php
get_header();
$news_query = new WP_Query(array(
  'post_type'      => 'news',
  'posts_per_page' => 5,
  'post_status'    => 'publish',
  'orderby'        => 'date',
  'order'          => 'DESC',
));
$portfolio_query = new WP_Query(array(
  'post_type'           => 'portfolio',
  'posts_per_page'      => 6,
  'post_status'         => 'publish',
  'orderby'             => 'date',
  'order'               => 'DESC',
  'ignore_sticky_posts' => true,
));
?>
<div class="coop-home">
  <section class="coop-news" aria-labelledby="coop-news-title">
    <h2 class="coop-news__label" id="coop-news-title">NEWS</h2>
    <?php if ($news_query->have_posts()) : ?>
      <div class="coop-news__ticker" data-news-ticker>
        <div class="coop-news__track">
          <?php $news_index = 0; ?>
          <?php while ($news_query->have_posts()) : $news_query->the_post(); ?>
            <a class="coop-news__link<?php echo (0 === $news_index) ? ' is-active' : ''; ?>" href="<?php the_permalink(); ?>"<?php echo (0 === $news_index) ? '' : ' aria-hidden="true" tabindex="-1"'; ?>>
              <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('Y.m.d')); ?></time>
              <span class="coop-news__title"><?php the_title(); ?></span>
              <span class="coop-news__arrow" aria-hidden="true"></span>
            </a>
            <?php $news_index++; ?>
          <?php endwhile; ?>
        </div>
      </div>
    <?php else : ?><p class="coop-news__empty">新しいお知らせはありません。</p><?php endif; wp_reset_postdata(); ?>
  </section>
  <section class="coop-cases" aria-labelledby="coop-cases-title" data-rest-url="<?php echo esc_url(rest_url('wp/v2/portfolio')); ?>" data-current-page="1" data-total-pages="<?php echo esc_attr($portfolio_query->max_num_pages); ?>">
    <header class="coop-cases__header"><h1 class="coop-cases__title" id="coop-cases-title">PORTFOLIO</h1></header>
    <?php if ($portfolio_query->have_posts()) : ?>
      <div class="coop-cases__grid" id="portfolio">
        <div class="coop-cases__sizer" aria-hidden="true"></div>
        <?php while ($portfolio_query->have_posts()) : $portfolio_query->the_post(); ?>
          <article <?php post_class('coop-case-card'); ?>><a class="coop-case-card__link" href="<?php the_permalink(); ?>">
            <figure class="coop-case-card__media">
              <?php if (has_post_thumbnail()) : the_post_thumbnail('full', array('loading' => 'lazy')); else : ?><span class="coop-case-card__placeholder" aria-hidden="true"></span><?php endif; ?>
            </figure>
            <div class="coop-case-card__body"><h2 class="coop-case-card__title"><span class="coop-case-card__title-text"><?php the_title(); ?></span></h2><div class="coop-case-card__excerpt"><?php the_excerpt(); ?></div><span class="coop-case-card__arrow" aria-hidden="true"></span></div>
          </a></article>
        <?php endwhile; ?>
      </div>
      <?php if ($portfolio_query->max_num_pages > 1) : ?><div class="coop-infinite-scroll" aria-live="polite" aria-busy="false"><span class="coop-infinite-scroll__status"><span class="coop-infinite-scroll__spinner" aria-hidden="true"></span><span class="coop-infinite-scroll__label">Loading more articles...</span></span></div><?php endif; ?>
    <?php else : ?><p class="coop-cases__empty">公開中のポートフォリオはありません。</p><?php endif; wp_reset_postdata(); ?>
  </section>
</div>
<?php get_footer(); ?>
