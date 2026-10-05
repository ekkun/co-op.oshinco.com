<?php get_header(); ?>

<div class="coop-singular">
	<?php while (have_posts()) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class('coop-entry'); ?>>
			<header class="coop-entry__header">
				<p class="coop-entry__eyebrow"><?php echo esc_html(is_page() ? 'PAGE' : strtoupper((string) get_post_type_object(get_post_type())->labels->singular_name)); ?></p>
				<h1 class="coop-entry__title"><?php the_title(); ?></h1>
				<?php if (is_singular(array('post', 'case', 'news'))) : ?>
					<time class="coop-entry__date" datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>"><?php echo esc_html(get_the_date('Y.m.d')); ?></time>
				<?php endif; ?>
			</header>

			<?php if (has_post_thumbnail()) : ?>
				<figure class="coop-entry__hero"><?php the_post_thumbnail('full'); ?></figure>
			<?php endif; ?>

			<div class="coop-entry__content entry-content">
				<?php the_content(); ?>
				<?php wp_link_pages(array('before' => '<nav class="coop-page-links" aria-label="' . esc_attr__('Page navigation', 'co-op-oshinco') . '">', 'after' => '</nav>')); ?>
			</div>

			<?php if (is_singular(array('post', 'case', 'news'))) : ?>
				<nav class="coop-entry-nav" aria-label="<?php esc_attr_e('Post navigation', 'co-op-oshinco'); ?>">
					<div class="coop-entry-nav__item coop-entry-nav__item--previous"><?php previous_post_link('%link', '<span>PREVIOUS</span>%title'); ?></div>
					<div class="coop-entry-nav__item coop-entry-nav__item--next"><?php next_post_link('%link', '<span>NEXT</span>%title'); ?></div>
				</nav>
			<?php endif; ?>
		</article>
	<?php endwhile; ?>
</div>

<?php get_footer(); ?>
