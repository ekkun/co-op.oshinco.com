<?php get_header(); ?>

<div class="coop-home coop-archive">
  <section class="coop-cases" aria-labelledby="coop-archive-title" data-archive-infinite-scroll data-container-selector=".coop-cases__grid" data-item-selector=".coop-case-card" data-next-page="<?php echo esc_url(get_next_posts_page_link()); ?>">
    <header class="coop-cases__header coop-archive__header">
      <h1 class="coop-cases__title" id="coop-archive-title">PORTFOLIO</h1>
      <?php if (get_the_archive_description()) : ?>
        <div class="coop-archive__description"><?php echo wp_kses_post(wpautop(get_the_archive_description())); ?></div>
      <?php endif; ?>
    </header>

    <?php if (have_posts()) : ?>
      <div class="coop-cases__grid" id="portfolio">
        <div class="coop-cases__sizer" aria-hidden="true"></div>
        <?php while (have_posts()) : the_post(); ?>
          <article <?php post_class('coop-case-card'); ?>><a class="coop-case-card__link" href="<?php the_permalink(); ?>">
            <figure class="coop-case-card__media">
              <?php if (has_post_thumbnail()) : the_post_thumbnail('post-thumb', array('loading' => 'lazy')); else : ?><span class="coop-case-card__placeholder" aria-hidden="true"></span><?php endif; ?>
            </figure>
            <div class="coop-case-card__body"><h2 class="coop-case-card__title"><span class="coop-case-card__title-text"><?php the_title(); ?></span></h2><div class="coop-case-card__excerpt"><?php the_excerpt(); ?></div><ion-icon class="coop-case-card__arrow" name="arrow-round-forward" aria-hidden="true"></ion-icon></div>
          </a></article>
        <?php endwhile; ?>
      </div>
      <?php if ($GLOBALS['wp_query']->max_num_pages > 1) : ?><div class="coop-infinite-scroll" aria-live="polite" aria-busy="false"><span class="coop-infinite-scroll__status"><span class="coop-infinite-scroll__spinner" aria-hidden="true"></span><span class="coop-infinite-scroll__label">Loading more articles...</span></span></div><?php endif; ?>
    <?php else : ?>
      <p class="coop-archive__empty">公開中のポートフォリオはありません。</p>
    <?php endif; ?>
  </section>
</div>

<?php get_footer(); ?>
