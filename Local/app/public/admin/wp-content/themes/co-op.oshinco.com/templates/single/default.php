<?php get_header(); ?>

<div class="coop-singular">
	<?php while (have_posts()) : the_post(); ?>
		<?php
		$post_type = get_post_type();
		$taxonomies = array(
			'post' => array('category', 'post_tag'),
			'news' => array('news_category', 'news_tags'),
			'case' => array('case_category', 'case_tags'),
		);
		$category_taxonomy = $taxonomies[$post_type][0] ?? null;
		$tag_taxonomy = $taxonomies[$post_type][1] ?? null;
		$categories = $category_taxonomy ? get_the_terms(get_the_ID(), $category_taxonomy) : array();
		$tags = $tag_taxonomy ? get_the_terms(get_the_ID(), $tag_taxonomy) : array();
		$hero_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
		if (!$hero_image) {
			$hero_image = get_theme_file_uri('/assets/images/rameswaram-dhanushkodi.webp');
		}
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class('coop-entry'); ?>>
			<header class="coop-entry__header" style="--coop-entry-hero-image: url('<?php echo esc_url($hero_image); ?>');">
				<div class="coop-entry__header-inner">
					<h1 class="coop-entry__title"><?php the_title(); ?></h1>
					<?php if (!is_page()) : ?>
						<div class="coop-entry__meta">
							<time class="coop-entry__date" datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>"><?php echo esc_html(get_the_date('Y.m.d')); ?></time>
							<?php if ($categories && !is_wp_error($categories)) : ?>
								<div class="coop-entry__terms coop-entry__categories">
									<?php foreach ($categories as $category) : ?>
										<a href="<?php echo esc_url(get_term_link($category)); ?>"><?php echo esc_html($category->name); ?></a>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
							<?php if ($tags && !is_wp_error($tags)) : ?>
								<div class="coop-entry__terms coop-entry__tags">
									<?php foreach ($tags as $tag) : ?>
										<a href="<?php echo esc_url(get_term_link($tag)); ?>">#<?php echo esc_html($tag->name); ?></a>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</header>

			<?php if (has_post_thumbnail()) : ?>
				<figure class="coop-entry__featured">
					<?php the_post_thumbnail('full', array('class' => 'coop-entry__featured-image')); ?>
				</figure>
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
