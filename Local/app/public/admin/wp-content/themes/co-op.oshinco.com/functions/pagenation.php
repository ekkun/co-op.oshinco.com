<?php
// PAGINATION
function pagination($pages, $paged, $range = 2, $show_only = false) {

  if (wp_is_mobile()) {
    $range = $range / 2;
  }

  $pages = (int)$pages; // float型で渡ってくるので明示的に int型 へ
  $paged = $paged ?: 1; // get_query_var('paged')をそのまま投げても大丈夫なように
  $display = $range * 2 + 1;
  $before_shortage = $range - ($pages - $paged);
  $after_shortage = $range - ($paged - 1);

  // 表示テキスト
  $text_before = "Prev";
  $text_next = "Next";

  // 1ページのみで表示設定が true の時
  if ($show_only && $pages === 1) {
    echo '';
    return;
  }

  // 1ページのみで表示設定もない場合
  if ($pages === 1) return;

  // 2ページ以上の時
  if (1 !== $pages) {
    // echo '全' . $pages . 'ページ($pages), ';
    // echo $paged . 'ページ目($paged), ';
    // echo '表示' . $display . '個($display), ';
    // if ($pages != $display) {
    //   echo '全ページと表示件数が同じではない, ';
    // }
    // if ($pages >= $display) {
    //   echo 'ページ数が多い, ';
    //   if ($paged <= $range) {
    //     echo '表示件数が少ない, ' . $after_shortage . '個不足, ';
    //   }
    //   if (($pages - $paged) < $range) {
    //     echo '表示件数が少ない, ' . $before_shortage . '個不足, ';
    //   }
    // }
    echo '<div class="c-paging">';
    echo '<ul>';
    if ($paged > 1) {
      // 「前へ」の表示
      echo '<li><a href="' . get_pagenum_link($paged - 1) . '" class="c-paging__prev"></a></li>';
    }
    // 最初のページ
    //if ($pages != $display && $paged >= $display - 1) {
    if ($pages != $display && $paged >= $display - 1) {
      echo '<li><a href="' . get_pagenum_link('1') . '" class="">1</a></li>';
      // if ($pages > $display) {
      //   echo '<span>..</span>';
      // }
    }
    // 最後のページ
    if ($pages >= $display) {
      $start = $pages - ($display - 1);
      for ($c = 1; $c <= $before_shortage; $c++) {
        echo '<li><a href="' . get_pagenum_link($start) . '" class="">' . $start . '</a></li>';
        $start++;
      }
    }
    for ($i = 1; $i <= $pages; $i++) {
      if ($i <= $paged + $range && $i >= $paged - $range) {
        // $paged +- $range 以内であればページ番号を出力
        if ($paged === $i) {
          echo '<li><a class="c-paging__act">' . $i . '</a></li>';
        } else {
          echo '<li><a href="' . get_pagenum_link($i) . '" class="">' . $i . '</a></li>';
        }
      }
    }
    // 最初のページ
    if ($pages >= $display) {
      // 最初のページ
      $start = ($display - $after_shortage) + 1;
      for ($c = 1; $c <= $after_shortage; $c++) {
        echo '<li><a href="' . get_pagenum_link($start) . '" class="">' . $start . '</a></li>';
        $start++;
      }
    }
    // 最後のページ
    if ($pages != $display && $paged <= $pages - ($display - $range)) {
      //if ($pages != $display && ($pages - $paged) < $range && $paged <= $pages - ($display - $range)) {
      // if ($pages > $display) {
      //   echo '<span>..</span>';
      // }
      echo '<li><a href="' . get_pagenum_link($pages) . '" class="">' . $pages . '</a></li>';
    }
    if ($paged < $pages) {
      // 「次へ」の表示
      echo '<li><a href="' . get_pagenum_link($paged + 1) . '" class="c-paging__next"></a></li>';
    }
    echo '</ul>';
    echo '</div>';
  }
}
