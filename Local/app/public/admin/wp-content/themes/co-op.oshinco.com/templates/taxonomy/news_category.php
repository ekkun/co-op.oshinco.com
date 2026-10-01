<?php
//header('Location: ' . get_home_url() . '/news/');
//exit;

$term_name = $queried_object->name;
$term_slug = $queried_object->slug;
$term_description = $queried_object->description;
$term_taxonomy = $queried_object->taxonomy;

$taxonomy = $term_taxonomy;
$terms = get_terms($taxonomy);
?>
<?php get_header(); ?>
<main role="main" class="l-main">
  <div class="p-pagetitle">
    <div class="p-pagetitle__inner">
      <h1 class="p-pagetitle__heading"><?php single_cat_title(); ?></h1>
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
        <nav class="p-tabs-nav">
          <ul class="p-tabs-nav__list">
            <li class="p-tabs-nav__item"><a href="<?php echo home_url(); ?>/news/" data-tab-id="">すべて</a></li>
            <?php foreach ($terms as $term) { ?>
              <li class="p-tabs-nav__item<?php if ($term_slug == $term->slug) { ?> is-current<?php } ?>"><a href="<?php echo home_url(); ?>/news/category/<?php echo $term->slug; ?>/" data-tab-id=""><?php echo $term->name; ?></a></li>
            <?php } ?>
          </ul>
        </nav>
        <?php get_template_part('templates/partial/news', 'list', $args = array('post' => 'news', 'type' => 'archive', 'taxonomy' => $taxonomy, 'term_slug' => $term_slug)); ?>
      </div>
    </div>
  </article><!--  / .l-contents /  -->
</main>
<?php get_footer(); ?>