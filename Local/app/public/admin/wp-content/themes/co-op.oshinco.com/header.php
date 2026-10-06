<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta name="format-detection" content="telephone=no">
  <link rel="icon" href="<?php echo esc_url(home_url('/favicon.ico')); ?>" sizes="32x32">
  <link rel="icon" href="<?php echo esc_url(home_url('/icon.svg')); ?>" type="image/svg+xml">
  <link rel="apple-touch-icon" href="<?php echo esc_url(home_url('/apple-touch-icon.png')); ?>">
  <link rel="manifest" href="<?php echo esc_url(home_url('/manifest.json')); ?>">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#site-content"><?php esc_html_e('Skip to the content', 'co-op-oshinco'); ?></a>

  <header class="coop-header" data-coop-header>
    <div class="coop-header__brand">
      <a class="coop-logo" href="<?php echo esc_url(home_url('/')); ?>" rel="home" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?> ホーム">
        <span class="coop-logo__name">OSHINCO CO-OP.</span>
      </a>
    </div>
    <button class="coop-menu-toggle" type="button" aria-expanded="false" aria-controls="coop-navigation">
      <span class="coop-menu-toggle__line"></span><span class="coop-menu-toggle__line"></span>
      <span class="screen-reader-text">メニューを開く</span>
    </button>
    <nav class="coop-navigation" id="coop-navigation" aria-label="メインメニュー">
      <?php
      wp_nav_menu(array(
        'theme_location'       => 'primary',
        'container'            => false,
        'menu_id'              => false,
        'menu_class'           => 'coop-navigation__list',
        'fallback_cb'          => false,
        'depth'                => 1,
        'add_li_class'         => 'coop-navigation__item',
        'add_li_current_class' => 'is-current',
        'add_a_class'          => 'coop-navigation__link',
        'link_before'          => '<span class="coop-navigation__label">',
        'link_after'           => '</span>',
      ));
      ?>
    </nav>
    <div class="coop-header__foot"><p>&copy; <?php echo esc_html(wp_date('Y')); ?> Oshinco Co-op.</p></div>
  </header>
<main class="wrapper" id="site-content">
