    <?php if (!is_404()) : ?>
      <?php $coop_footer_photos = coop_get_flickr_photos(24); ?>
      <?php if ($coop_footer_photos) : ?>
        <footer class="coop-photo-footer" aria-label="Flickr photo stream">
          <div class="coop-photo-footer__track">
            <?php for ($coop_photo_group = 0; $coop_photo_group < 2; $coop_photo_group++) : ?>
              <div class="coop-photo-footer__group"<?php echo $coop_photo_group ? ' aria-hidden="true"' : ''; ?>>
                <?php foreach ($coop_footer_photos as $coop_footer_photo) : ?>
                  <a
                    class="coop-photo-footer__photo"
                    href="<?php echo esc_url($coop_footer_photo['link']); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    <?php echo $coop_photo_group ? 'tabindex="-1"' : ''; ?>
                    style="--coop-footer-image: url('<?php echo esc_url($coop_footer_photo['image']); ?>');"
                  >
                    <span class="screen-reader-text"><?php echo esc_html($coop_footer_photo['title']); ?></span>
                  </a>
                <?php endforeach; ?>
              </div>
            <?php endfor; ?>
          </div>
        </footer>
      <?php endif; ?>
    <?php endif; ?>
  </main><!-- .wrapper -->

  <?php wp_footer(); ?>

</body>
</html>
