<?php get_header(); ?>
<?php
if (function_exists('yoast_breadcrumb')) {
  yoast_breadcrumb('<div class="p-breadcrumbs">', '</div>');
}
?>
<div class="p-breadcrumbs">
  <?php
  $args = array(
    'nav_div' => 'nav',
    'nav_div_class' => 'p-breadcrumbs__inner',
    'aria_label' => 'breadcrumbs',
    'ul_class' => 'p-breadcrumbs__list',
    'li_class' => '',
    'li_active_class' => 'is-active',
    'aria_current' => 'page',
    'separator' => ' ',
    'post_type' => 'news',
  );
  custom_breadcrumb($args);
  ?>
</div>
<div id="content">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <div class="post" id="post-<?php the_ID(); ?>">
        <h2><?php the_title(); ?></h2>
        <div class="postmetadata">Posted on <?php the_time(get_option('date_format')) ?>, <?php the_time(get_option('time_format')) ?>, by <?php the_author() ?>, under <?php the_category(', ') ?>.</div>
        <div class="entry">
          <?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>
          <?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
        </div>
        <div class="postmetadata">
          <?php if (function_exists('the_tags'))
            the_tags(__('Tags: '), ', ', '<br />');
          ?>
          <?php edit_post_link(__('Edit'), '&nbsp;|&nbsp;&nbsp;', ''); ?>
        </div>
      </div>
    <?php endwhile; ?>
    <div class="navigation">
      <div style="text-align:left;"><?php previous_post_link('&laquo; %link') ?></div>
      <div style="text-align:right;"><?php next_post_link('%link &raquo;') ?></div>
    </div>
  <?php else : ?>
    <p>Sorry, no posts matched your criteria.</p>
  <?php endif; ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>