<?php if ( $wp_query->max_num_pages > 1 ) : ?>
	<div class="archive-nav clear">
		<?php echo get_next_posts_link( __( 'Older posts', 'co-op-oshinco' ) . ' &rarr;' ); ?>
		<?php echo get_previous_posts_link( '&larr; ' . __( 'Newer posts', 'co-op-oshinco' ) ); ?>
	</div><!-- .archive-nav -->
<?php endif; ?>
