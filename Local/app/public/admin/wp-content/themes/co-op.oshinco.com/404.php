<?php
/**
 * 404 page template.
 *
 * @package co-op-oshinco
 */

$coop_flickr_photos = coop_get_flickr_photos(35);

get_header();
?>

<div class="coop-not-found">
  <div class="coop-not-found__grid">
    <?php foreach ($coop_flickr_photos as $coop_flickr_photo) : ?>
      <a
        class="coop-not-found__photo"
        href="<?php echo esc_url($coop_flickr_photo['link']); ?>"
        target="_blank"
        rel="noopener noreferrer"
        style="--coop-404-image: url('<?php echo esc_url($coop_flickr_photo['image']); ?>');"
      >
        <span class="screen-reader-text"><?php echo esc_html($coop_flickr_photo['title']); ?></span>
      </a>
    <?php endforeach; ?>
  </div>

  <div class="coop-not-found__veil" aria-hidden="true"></div>
  <h1 class="coop-not-found__title">PAGE NOT FOUND</h1>
</div>

<?php get_footer(); ?>
