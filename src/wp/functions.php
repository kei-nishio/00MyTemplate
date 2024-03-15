<?php

// ! 投稿画面の表示項目を設定する
add_action('after_setup_theme', 'my_setup');
function my_setup()
{
  add_theme_support('post-thumbnails'); // アイキャッチ画像を有効化
  add_theme_support('automatic-feed-links'); // 投稿とコメントのRSSフィードのリンクを有効化
  add_theme_support('title-tag'); // titleタグ自動生成
  add_theme_support('html5', array( // HTML5による出力
    'search-form',
    'comment-form',
    'comment-list',
    'gallery',
    'caption',
  ));
}

// ! headでgoogle fontやCDNを読み込む
add_action('wp_enqueue_scripts', 'my_script_init');
function my_script_init()
{
  // jQuery
  wp_deregister_script('jquery');
  wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js', "", "3.7.0", true);

  // Google Fonts
  wp_enqueue_style('NotoSansJP', 'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap');
  wp_enqueue_style('Roboto', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap');
  wp_enqueue_style('Oswald', 'https://fonts.googleapis.com/css2?family=Oswald:wght@700&display=swap');

  // Script
  wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array('jquery'), "11.0", true);
  wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', array('jquery', 'swiper'), "3.12.5", true);
  wp_enqueue_script('gsap-scroll-trigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', array('jquery', 'swiper', 'gsap'), "3.12.5", true);
  wp_enqueue_script('script-main', get_theme_file_uri('/assets/js/script.js'), array('jquery', 'swiper', 'gsap', 'gsap-scroll-trigger'), '1.0.0', true);

  // CSS
  wp_enqueue_style('style-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');
  wp_enqueue_style('style-swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
  wp_enqueue_style('style-main', get_theme_file_uri('/assets/css/style.css'), array(), '1.0.0');
}

// ! カスタム投稿の表示件数を設定する
add_action('pre_get_posts', 'change_posts_per_page');
function change_posts_per_page($query)
{
  if (is_admin() || !$query->is_main_query())
    return;
  if ($query->is_archive('works')) { //カスタム投稿タイプを指定
    $query->set('posts_per_page', '6'); //表示件数を指定（-1は全件）
  }
}

// ! Contact Form 7で自動挿入されるPタグ、brタグを削除
add_filter('wpcf7_autop_or_not', 'wpcf7_autop_return_false');
function wpcf7_autop_return_false()
{
  return false;
}
