<?php
get_header();
$news_query = new WP_Query(array('post_type' => 'news', 'posts_per_page' => 1, 'post_status' => 'publish'));
?>
<div class="coop-home">
  <section class="coop-news" aria-labelledby="coop-news-title">
    <h2 class="coop-news__label" id="coop-news-title">NEWS</h2>
    <?php if ($news_query->have_posts()) : $news_query->the_post(); ?>
      <a class="coop-news__link" href="<?php the_permalink(); ?>"><time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('Y.m.d')); ?></time><span class="coop-news__title"><?php the_title(); ?></span><span class="coop-news__arrow" aria-hidden="true">&#8599;</span></a>
    <?php else : ?><p class="coop-news__empty">新しいお知らせはありません。</p><?php endif; wp_reset_postdata(); ?>
  </section>
  <section class="coop-cases" aria-labelledby="coop-cases-title" data-rest-url="<?php echo esc_url(rest_url('wp/v2/case')); ?>" data-current-page="<?php echo esc_attr(max(1, get_query_var('paged'))); ?>" data-total-pages="<?php echo esc_attr($GLOBALS['wp_query']->max_num_pages); ?>">
    <header class="coop-cases__header"><h1 class="coop-cases__title" id="coop-cases-title">CASE STUDIES</h1></header>
    <?php if (have_posts()) : ?>
      <div class="coop-cases__grid" id="case-studies">
        <div class="coop-cases__sizer" aria-hidden="true"></div>
        <?php while (have_posts()) : the_post(); ?>
          <article <?php post_class('coop-case-card'); ?>><a class="coop-case-card__link" href="<?php the_permalink(); ?>">
            <figure class="coop-case-card__media">
              <?php if (has_post_thumbnail()) : the_post_thumbnail('post-thumb', array('loading' => 'lazy')); else : ?><span class="coop-case-card__placeholder" aria-hidden="true"></span><?php endif; ?>
            </figure>
            <div class="coop-case-card__body"><h2 class="coop-case-card__title"><?php the_title(); ?></h2><div class="coop-case-card__excerpt"><?php the_excerpt(); ?></div><span class="coop-case-card__arrow" aria-hidden="true">&#8594;</span></div>
          </a></article>
        <?php endwhile; ?>
      </div>
      <?php if ($GLOBALS['wp_query']->max_num_pages > 1) : ?><ion-infinite-scroll threshold="300px"><ion-infinite-scroll-content loading-spinner="crescent" loading-text="Loading more works..."></ion-infinite-scroll-content></ion-infinite-scroll><?php endif; ?>
    <?php else : ?><p class="coop-cases__empty">公開中の実績はありません。</p><?php endif; ?>
  </section>
</div>
<?php get_footer(); ?>
