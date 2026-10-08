<?php
/**
 * 404 page template.
 *
 * @package co-op-oshinco
 */

$coop_flickr_feeds = array(
  'https://www.flickr.com/services/feeds/photos_public.gne?id=56004767@N00&lang=en-us&format=atom',
  'https://www.flickr.com/services/feeds/photos_public.gne?id=39138472@N08&lang=en-us&format=atom',
  'https://www.flickr.com/services/feeds/photos_public.gne?id=31655482@N00&lang=en-us&format=atom',
);

/**
 * Return Flickr photos from a public Atom feed.
 *
 * @param string $feed_url Flickr public feed URL.
 * @return array<int, array{image: string, link: string, title: string}>
 */
function coop_get_flickr_feed_photos($feed_url) {
  require_once ABSPATH . WPINC . '/feed.php';

  $feed = fetch_feed($feed_url);
  if (is_wp_error($feed)) {
    return array();
  }

  $photos = array();
  foreach ($feed->get_items(0, 60) as $item) {
    $enclosure = $item->get_enclosure();
    $image_url = $enclosure ? $enclosure->get_link() : '';

    if (!$image_url) {
      $media = $item->get_item_tags('http://search.yahoo.com/mrss/', 'content');
      $image_url = isset($media[0]['attribs']['']['url']) ? $media[0]['attribs']['']['url'] : '';
    }

    if (!$image_url) {
      continue;
    }

    $photos[] = array(
      'image' => $image_url,
      'link'  => $item->get_permalink(),
      'title' => wp_strip_all_tags($item->get_title()),
    );
  }

  return $photos;
}

$coop_flickr_photos = array();
foreach ($coop_flickr_feeds as $coop_flickr_feed) {
  $coop_flickr_photos = array_merge($coop_flickr_photos, coop_get_flickr_feed_photos($coop_flickr_feed));
}
shuffle($coop_flickr_photos);
$coop_flickr_photos = array_slice($coop_flickr_photos, 0, 35);

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
