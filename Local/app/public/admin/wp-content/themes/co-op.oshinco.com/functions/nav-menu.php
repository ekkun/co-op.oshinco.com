<?php
// メニュー機能有効化
add_theme_support('menus');

// wp_nav_menuのliにclassを追加
function classList_add_li($classes, $item, $args) {
  if (isset($args->add_li_class)) {
    $classes['class'] = $args->add_li_class;
  }
  // wp_nav_menuのcurrent liにclassを追加
  if (isset($args->add_li_current_class)) {
    $classes = str_replace('current-menu-item', $args->add_li_current_class, $classes);
  }
  $classes = preg_grep("/menu-item|\d/", $classes, PREG_GREP_INVERT);
  return $classes;
}
add_filter('nav_menu_css_class', 'classList_add_li', 1, 3);

// wp_nav_menuのaにclassを追加
function classList_add_a($classes, $item, $args) {
  if (isset($args->add_li_class)) {
    $classes['class'] = $args->add_a_class;
  }
  unset($classes['aria-current']);
  return $classes;
}
add_filter('nav_menu_link_attributes', 'classList_add_a', 1, 3);

// wp_nav_menuのsub-menuにclassを追加
function classList_add_submenu($classes, $args, $depth) {
  $count = count($args->add_submenu_class);
  if (isset($args->add_submenu_class)) {
    $childs = $args->add_submenu_class;
    $classes = array($childs[$depth]);
    $classes = isset($classes) ? $classes : 'empty';
  }
  $classes = preg_grep("/sub-menu|\d/", $classes, PREG_GREP_INVERT);
  return $classes;
}
add_filter('nav_menu_submenu_css_class', 'classList_add_submenu', 1, 3);

// wp_nav_menuのliから不要id削除
function wp_navtag_remove($var) {
  return is_array($var) ? array_intersect($var, array('current-menu-item')) : '';
}
//add_filter('nav_menu_css_class', 'wp_navtag_remove', 100, 1); // liのclassを強制的に全削除
add_filter('nav_menu_item_id', 'wp_navtag_remove', 100, 1); // liのidを強制的に削除
add_filter('page_css_class', 'wp_navtag_remove', 100, 1);
