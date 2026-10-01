<?php get_header(); ?>
<main role="main" class="l-main">
  <div class="p-pagetitle">
    <div class="p-pagetitle__inner">
      <h1 class="p-pagetitle__heading"><?php the_title(); ?></h1>
    </div>
  </div>
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
    );
    custom_breadcrumb($args);
    ?>
  </div>
  <article class="l-contents">
    <div class="l-contents__inner">
      <div class="p-entry u-pc-pb120 u-sp-pb80">
        <?php the_content(''); ?>
      </div>
    </div>
  </article><!--  / .l-contents /  -->
</main>
<?php get_footer(); ?>