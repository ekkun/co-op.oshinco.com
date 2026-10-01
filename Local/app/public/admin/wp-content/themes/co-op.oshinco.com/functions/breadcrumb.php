<?php
// BREADCRUMB
// https://www.webdesignleaves.com/pr/wp/wp_breadcrumbs.html
function custom_breadcrumb($args = array()) {
  global $post;
  // デフォルトの値
  $defaults = array(
    'nav_div' => 'div',
    'aria_label' => '',
    'id' => '',
    'nav_div_class' => 'c-breadcrumbs',
    'ul_class' => 'c-breadcrumbs__list',
    'li_class' => '',
    'li_active_class' => '',
    'aria_current' => '',
    'show_home' => true,
    'show_current' => true,
    'home' => 'ホーム',
    'blog_home' => 'Blog',
    'search' => 'で検索した結果',
    'tag' => 'タグ : ',
    'author' => '投稿者',
    'notfound' => 'お探しのページは見つかりませんでした',
    'separator' => '',
    'cat_off' => false,
    'cat_parents_off' => false,
    'tax_off' => false,
    'tax_parents_off' => false,
    'show_cpta' => true,
    'show_cat_tag_for_cpt' => false,
    'post_type' => '',
  );
  //引数の値とデフォルトをマージ
  $args = wp_parse_args($args, $defaults);
  //マージした値を変数として抽出
  extract($args, EXTR_SKIP);

  // ポジション
  $position = '1';

  //マージした値を元に出力するかどうかを設定
  $aria_label = $aria_label ? ' aria-label="' . $aria_label . '" ' : '';
  $id = $id ? ' id="' . $id . '" ' : '';
  $nav_div_class = $nav_div_class ? ' class="' . $nav_div_class . '" ' : '';
  $ul_class = $ul_class ? ' class="' . $ul_class . '" ' : '';
  $li_class = $li_class ? ' class="' . $li_class . '" ' : '';
  $li_active_class = $li_active_class ? ' class="' . $li_active_class . '" ' : '';
  $aria_current = $aria_current ? ' aria-current="' . $aria_current . '"' : '';

  //パンくずリストのマークアップ文字列の初期化
  $str = '';

  //ホーム・フロントページの場合
  if (is_front_page() || is_home()) {
    if ($show_home) {
      $label = is_front_page() ? $home : $blog_home;
      echo  '<' . $nav_div . $id . $nav_div_class . $aria_label . '><ol' . $ul_class . ' itemscope itemtype="https://schema.org/BreadcrumbList"><li' . $li_active_class . $aria_current . '>' . $label . '</li></ol></' . $nav_div . '>' . "\n";
    }
  }
  //ホーム・フロントページでない場合（且つ管理ページでない場合）
  if (!is_front_page() && !is_home() && !is_admin()) {
    //ホームへのリンクを含むリストを生成
    $str .= '<' . $nav_div . $id . $nav_div_class . $aria_label . '>' . "\n";
    $str .= '<div class="l-inner">' . "\n";
    $str .= '<ol' . $ul_class . ' itemscope itemtype="https://schema.org/BreadcrumbList">' . "\n";
    $str .= '<li' . $li_class . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . home_url() . '/" itemprop="item"><span itemprop="name">' . $home . '</span><meta itemprop="position" content="' . $position . '"></a></li>' . "\n";
    //$wp_query の query_vars から get_query_var() でクエリ変数の値を取得
    //タクソノミー名を取得（タクソノミーアーカイブの場合のみ取得可能）
    $my_taxonomy = get_query_var('taxonomy');
    //投稿タイプ名を取得（カスタム投稿タイプ個別ページの場合のみ取得可能）
    $cpt = get_query_var('post_type');
    //カスタムタクソノミーアーカイブページ
    //タクソノミー名が取得できて且つカスタムタクソノミーアーカイブページの場合
    if ($my_taxonomy &&  is_tax($my_taxonomy)) {
      //ポジション
      $position++;
      //タームオブジェクト（現在のページのオブジェクト）を取得
      $my_term = get_queried_object();
      //タクソノミーの object_type プロパティは配列
      $post_types = get_taxonomy($my_taxonomy)->object_type;
      //配列の0番目からカスタム投稿タイプのスラッグ（カスタム投稿タイプ名）を取得
      $cpt = $post_types[0];
      //get_post_type_archive_link()：指定した投稿タイプのアーカイブページのリンク
      //get_post_type_object($cpt)->label：指定した投稿タイプのオブジェクトのラベル（名前）
      //カスタム投稿のアーカイブページへのリンクを追加
      $str .= $separator;
      $str .= '<li' . $li_class . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url(get_post_type_archive_link($cpt)) . '" itemprop="item"><span itemprop="name">' . get_post_type_object($cpt)->label . '</span><meta itemprop="position" content="' . $position . '"></a></li>' . "\n";
      //タームオブジェクトに親があればそれらを取得してリンクを生成してリストに追加
      if ($my_term->parent != 0) {
        //ポジション
        $position++;
        //祖先タームオブジェクトの ID の配列を取得し逆順に（取得される配列の並びは階層の下から上）
        $ancestors = array_reverse(get_ancestors($my_term->term_id, $my_term->taxonomy));
        //全ての祖先タームオブジェクトのアーカイブページへのリンクを生成してリストに追加
        foreach ($ancestors as $ancestor) {
          //ポジション
          $position++;
          $str .= $separator;
          $str .= '<li' . $li_class . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url(get_term_link($ancestor, $my_term->taxonomy)) . '" itemprop="item"><span itemprop="name">' . get_term($ancestor, $my_term->taxonomy)->name . '</span><meta itemprop="position" content="' . $position . '"></a></li>' . "\n";
        }
      }
      //ポジション
      $position++;
      //ターム名を追加
      $str .= $separator;
      $str .= '<li' . $li_active_class . $aria_current . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span itemprop="name">' . $my_term->name . '</span><meta itemprop="position" content="' . $position . '"></li>' . "\n";
      //カテゴリーのアーカイブページ
    } elseif (is_category()) {
      //ポジション
      $position++;
      //カテゴリーオブジェクトを取得
      $cat = get_queried_object();
      //取得したカテゴリーオブジェクトに親があればそれらを取得してリンクを生成してリストに追加
      if ($cat->parent != 0) {
        $ancestors = array_reverse(get_ancestors($cat->term_id, 'category'));
        foreach ($ancestors as $ancestor) {
          //ポジション
          $position++;
          $str .= $separator;
          $str .= '<li' . $li_class . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url(get_category_link($ancestor)) . '" itemprop="item"><span itemprop="name">' . get_cat_name($ancestor) . '</span><meta itemprop="position" content="' . $position . '"></a></li>' . "\n";
        }
      }
      //ポジション
      $position++;
      //カテゴリー名を追加
      $str .= $separator;
      $str .= '<li' . $li_active_class . $aria_current . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span itemprop="name">' . $cat->name . '</span><meta itemprop="position" content="' . $position . '"></li>' . "\n";
      //カスタム投稿のアーカイブページ
    } elseif (is_post_type_archive()) {
      //ポジション
      $position++;
      //カスタム投稿タイプ名を取得
      $cpt = get_query_var('post_type');
      //カスタム投稿タイプ名を追加
      $str .= $separator;
      $str .= '<li' . $li_active_class . $aria_current . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span itemprop="name">' . get_post_type_object($cpt)->label . '</span><meta itemprop="position" content="' . $position . '"></li>' . "\n";
      //カスタム投稿タイプの個別記事ページ
    } elseif ($cpt && is_singular($cpt)) {
      if ($show_cpta) {
        //ポジション
        $position++;
        //カスタム投稿タイプアーカイブページへのリンクを生成してリストに追加
        $str .= $separator;
        $str .= '<li' . $li_class . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url(get_post_type_archive_link($cpt)) . '" itemprop="item"><span itemprop="name">' . get_post_type_object($cpt)->label . '</span><meta itemprop="position" content="' . $position . '"></a></li>' . "\n";
      }
      //このカスタム投稿タイプに登録されている全てのタクソノミーオブジェクトの名前を取得
      $taxes = get_object_taxonomies($cpt);
      //タクソノミーオブジェクトの名前が取得できれば
      if (count($taxes) !== 0) {
        //タクソノミーを表示する場合
        if (!$tax_off) {
          //配列の先頭のタクソノミーオブジェクトの名前（複数ある可能性があるので先頭のものを使う）
          //デフォルトでは標準のカテゴリーやタグが追加されている場合はインデックスを変更
          //但し、show_cat_tag_for_cpt が true の場合はカテゴリーを取得可能に
          $tax_index = 0;
          if (!$show_cat_tag_for_cpt) {
            for ($i = 0; $i < count($taxes); $i++) {
              if ($taxes[$i] !== 'category' && $taxes[$i] !== 'post_tag' && $taxes[$i] !== 'post_format') {
                $tax_index = $i;
                break;
              }
            }
          }
          $mytax = $taxes[$tax_index] ? $taxes[$tax_index] : null;
          //カスタムフィールドに優先するタクソノミーのラベルが記載されていればそのタクソノミーを選択
          //タクソノミーのラベルを取得
          $my_pref_tax_label = get_post_meta(get_the_ID(), 'my_pref_tax', true) ? esc_attr(get_post_meta(get_the_ID(), 'my_pref_tax', true)) : null;
          //ラベルからタクソノミーを取得（戻り値はタクソノミーの名前の配列）
          $my_pref_tax_name = get_taxonomies(array('label' => $my_pref_tax_label));
          //タクソノミー名の初期化
          $my_pref_tax = '';
          //取得した配列が1つの場合、その値が優先されるタクソノミーの名前
          if (count($my_pref_tax_name) == 1) {
            $my_pref_tax = $my_pref_tax_name[key($my_pref_tax_name)];
          }
          //タクソノミーの名前が取得できて且つそのタクソノミーが現在の投稿タイプに属している場合は、そのタクソノミーを使用
          if ($my_pref_tax && is_object_in_taxonomy($post->post_type, $my_pref_tax)) {
            $mytax = $my_pref_tax;
          }
          //投稿に割り当てられたタームオブジェクト（配列）を取得
          $terms = get_the_terms($post->ID, $mytax);
          //カスタムフィールドに優先するタームが記載されていればその値を取得して $myterm へ
          $myterm = get_post_meta(get_the_ID(), 'myterm', true) ? esc_attr(get_post_meta(get_the_ID(), 'myterm', true)) : null;
          //$terms が取得できていれば一番下の階層のタームを取得（できない場合は null に）
          $my_term = $terms ? get_deepest_term($terms, $mytax, $myterm) : null;
          //タームが取得できていれば
          if (!empty($my_term)) {
            //$tax_parents_off がfalse（初期値）でタームに親があればそれらを取得してリンクを生成してリストに追加
            if ($my_term->parent != 0 && !$tax_parents_off) {
              //ポジション
              $position++;
              $ancestors = array_reverse(get_ancestors($my_term->term_id, $mytax));
              foreach ($ancestors as $ancestor) {
                //ポジション
                $position++;
                $str .= $separator;
                $str .= '<li' . $li_class . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url(get_term_link($ancestor, $mytax)) . '" itemprop="item"><span itemprop="name">' . get_term($ancestor, $mytax)->name . '</span><meta itemprop="position" content="' . $position . '"></a></li>' . "\n";
              }
            }
            //ポジション
            $position++;
            //タームのリンクを追加
            $str .= $separator;
            $str .= '<li' . $li_class . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url(get_term_link($my_term, $mytax)) . '" itemprop="item"><span itemprop="name">' . $my_term->name . '</span><meta itemprop="position" content="' . $position . '"></a></li>' . "\n";
          }
        }
      }
      if ($show_current) {
        //ポジション
        $position++;
        $str .= $separator;
        //$post->post_title には HTML タグが入っている可能性があるのでタグを除去
        //wp_strip_all_tags() の代わりに PHP の strip_tags() でも
        $str .= '<li' . $li_active_class . $aria_current . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span itemprop="name">' . wp_strip_all_tags($post->post_title) . '</span><meta itemprop="position" content="' . $position . '"></li>' . "\n";
      }
      //個別投稿ページ（添付ファイルも true と判定されるので除外）
    } elseif (is_single() && !is_attachment()) {
      //投稿が属するカテゴリーオブジェクトの配列を取得
      $categories = get_the_category($post->ID);
      //カテゴリーを表示する場合
      if (!$cat_off) {
        //カスタムフィールドに優先するカテゴリーが記載されていればその値を取得して $myterm へ
        $myterm = get_post_meta(get_the_ID(), 'myterm', true) ? esc_attr(get_post_meta(get_the_ID(), 'myterm', true)) : null;
        //一番下の階層のカテゴリーを取得
        $cat = get_deepest_term($categories, 'category', $myterm);
        //$cat_parents_off が false（初期値）でカテゴリーに親があればそれらを取得してリンクを生成してリストに追加
        if ($cat->parent != 0 && !$cat_parents_off) {
          $ancestors = array_reverse(get_ancestors($cat->term_id, 'category'));
          foreach ($ancestors as $ancestor) {
            //ポジション
            $position++;
            $str .= $separator;
            $str .= '<li' . $li_class . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url(get_category_link($ancestor)) . '" itemprop="item"><span itemprop="name">' . get_cat_name($ancestor) . '</span><meta itemprop="position" content="' . $position . '"></a></li>' . "\n";
          }
        }
        //ポジション
        $position++;
        //カテゴリーのリンクを追加
        $str .= $separator;
        $str .= '<li' . $li_class . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url(get_category_link($cat->term_id)) . '" itemprop="item"><span itemprop="name">' . $cat->name . '</span><meta itemprop="position" content="' . $position . '"></a></li>' . "\n";
      }
      if ($show_current) {
        //ポジション
        $position++;
        $str .= $separator;
        $str .= '<li' . $li_active_class . $aria_current . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span itemprop="name">' . wp_strip_all_tags($post->post_title) . '</span><meta itemprop="position" content="' . $position . '"></li>' . "\n";
      }
      //固定ページ
    } elseif (is_page()) {
      //固定ページに親があればそれらを取得してリンクを生成してリストに追加
      if ($post->post_parent != 0) {
        $ancestors = array_reverse(get_post_ancestors($post->ID));
        foreach ($ancestors as $ancestor) {
          //ポジション
          $position++;
          $str .= $separator;
          $str .= '<li' . $li_class . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . esc_url(get_permalink($ancestor)) . '" itemprop="item"><span itemprop="name">' . get_the_title($ancestor) . '</span><meta itemprop="position" content="' . $position . '"></a></li>' . "\n";
        }
      }
      //ポジション
      $position++;
      //固定ページ名を追加
      $str .= $separator;
      $str .= '<li' . $li_active_class . $aria_current . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span itemprop="name">' . wp_strip_all_tags($post->post_title) . '</span><meta itemprop="position" content="' . $position . '"></li>' . "\n";
      //日付ベースのアーカイブページ
    } elseif (is_date()) {
      //年別アーカイブ
      if (get_query_var('day') != 0) {
        //ポジション
        $position++;
        //日付アーカイブページでは get_query_var() でアーカイブページの年・月・日を取得できる
        //取得した値と get_year_link() などを使ってリンクを生成
        $str .= $separator;
        $str .= '<li' . $li_class . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . get_year_link(get_query_var('year')) . '" itemprop="item"><span itemprop="name">' . get_query_var('year') . '年</span><meta itemprop="position" content="' . $position . '"></a></li>' . "\n";
        $str .= $separator;
        $str .= '<li' . $li_class . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . get_month_link(get_query_var('year'), get_query_var('monthnum')) . '" itemprop="item"><span itemprop="name">' . get_query_var('monthnum') . '月</span><meta itemprop="position" content="' . $position . '"></a></li>' . "\n";
        $str .= $separator;
        $str .= '<li' . $li_active_class . $aria_current . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span itemprop="name">' . get_query_var('day') . '日</span><meta itemprop="position" content="' . $position . '"></li>' . "\n";
        //月別アーカイブ
      } elseif (get_query_var('monthnum') != 0) {
        //ポジション
        $position++;
        $str .= $separator;
        $str .= '<li' . $li_class . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="' . get_year_link(get_query_var('year')) . '" itemprop="item"><span itemprop="name">' . get_query_var('year') . '年</span><meta itemprop="position" content="' . $position . '"></a></li>' . "\n";
        $str .= $separator;
        $str .= '<li' . $li_active_class . $aria_current . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span itemprop="name">' . get_query_var('monthnum') . '月</span><meta itemprop="position" content="' . $position . '"></li>' . "\n";
        //年別アーカイブ
      } else {
        //ポジション
        $position++;
        $str .= $separator;
        $str .= '<li' . $li_active_class . $aria_current . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span itemprop="name">' . get_query_var('year') . '年</span><meta itemprop="position" content="' . $position . '"></li>' . "\n";
      }
      //検索結果表示ページ
    } elseif (is_search()) {
      //ポジション
      $position++;
      $str .= $separator;
      $str .= '<li' . $li_active_class . $aria_current . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span itemprop="name">「' . get_search_query() . '」' . $search . '</span><meta itemprop="position" content="' . $position . '"></li>' . "\n";
      //投稿者のアーカイブページ
    } elseif (is_author()) {
      //ポジション
      $position++;
      $str .= $separator;
      $str .= '<li' . $li_active_class . $aria_current . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span itemprop="name">' . $author . ' : ' . get_the_author_meta('display_name', get_query_var('author')) . '</span><meta itemprop="position" content="' . $position . '"></li>' . "\n";
      //タグのアーカイブページ
    } elseif (is_tag()) {
      //ポジション
      $position++;
      $str .= $separator;
      //$str.='<li' .$li_active_class. $aria_current.'>'. $tag .' '. single_tag_title( '' , false ). '</li>';
      $str .= '<li' . $li_active_class . $aria_current . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span itemprop="name">' . single_tag_title($tag, false) . '</span><meta itemprop="position" content="' . $position . '"></li>' . "\n";
      //添付ファイルページ
    } elseif (is_attachment()) {
      //ポジション
      $position++;
      $str .= $separator;
      $str .= '<li' . $li_active_class . $aria_current . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span itemprop="name">' . wp_strip_all_tags($post->post_title) . '</span><meta itemprop="position" content="' . $position . '"></li>' . "\n";
      //404 Not Found ページ
    } elseif (is_404()) {
      //ポジション
      $position++;
      $str .= $separator;
      $str .= '<li' . $li_active_class . $aria_current . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span itemprop="name">' . $notfound . '</span><meta itemprop="position" content="' . $position . '"></li>' . "\n";
      //その他
    } else {
      //ポジション
      $position++;
      $str .= $separator;
      $str .= '<li' . $li_active_class . $aria_current . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><span itemprop="name">' . wp_get_document_title() . '</span><meta itemprop="position" content="' . $position . '"></li>' . "\n";
    }
    $str .= '</ol>' . "\n";
    $str .= '</div>' . "\n";
    $str .= '</' . $nav_div . '>' . "\n";
  }
  echo $str;
}
