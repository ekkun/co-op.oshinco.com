<div id="hmenu">
  <ul>
    <?php
    if (has_nav_menu('primary')) {
      wp_nav_menu(array(
        'theme_location' => 'primary',
        'container'      => '',
        'items_wrap'     => '%3$s',
      ));
    }
    ?>
  </ul>
</div>
<hr />
