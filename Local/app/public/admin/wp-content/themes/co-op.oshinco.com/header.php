<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
      <ul class="coop-navigation__list">
        <li class="coop-navigation__item"><a class="coop-navigation__link" href="<?php echo esc_url(home_url('/')); ?>">HOME</a></li>
        <li class="coop-navigation__item"><a class="coop-navigation__link" href="<?php echo esc_url(home_url('/about-us/')); ?>">ABOUT US</a></li>
        <li class="coop-navigation__item"><a class="coop-navigation__link" href="<?php echo esc_url(home_url('/contact/')); ?>">CONTACT US</a></li>
        <li class="coop-navigation__item"><a class="coop-navigation__link" href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">PRIVACY POLICY</a></li>
        <li class="coop-navigation__item"><a class="coop-navigation__link" href="https://oshinco.com/" target="_blank" rel="noopener noreferrer">OFFICIAL SITE</a></li>
      </ul>
    </nav>
    <div class="coop-header__foot"><p>&copy; <?php echo esc_html(wp_date('Y')); ?> Oshinco Co-op.</p></div>
  </header>
<main class="wrapper" id="site-content">
