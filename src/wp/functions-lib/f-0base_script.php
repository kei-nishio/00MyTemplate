<?php
// ! headでgoogle fontやCDNを読み込む
add_action('wp_enqueue_scripts', 'my_script_init');
function my_script_init()
{
  // ! jQuery
  // wp_deregister_script('jquery');
  // wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js', "", "3.7.0", true);

  // ! Google Fonts
  wp_enqueue_style('zen-kaku-gothic-new', 'https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New:wght@300;400;500;700;900&display=swap');
  wp_enqueue_style('Jost', 'https://fonts.googleapis.com/css2?family=Jost:wght@600;700&display=swap');

  // ! CSS
  wp_enqueue_style('style-reset', get_theme_file_uri('/assets/css/ress.min.css'), [], '5.0.2');
  // wp_enqueue_style('style-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');
  wp_enqueue_style('style-swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', ['style-reset'], '11.0');
  wp_enqueue_style('style-main', get_theme_file_uri('/assets/css/style.css'), ['style-reset', 'style-swiper'], '1.0.0');

  // ! Script
  wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', "", "11.0", true);
  wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', ['swiper'], "3.12.5", true);
  wp_enqueue_script('gsap-scroll-trigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', ['swiper', 'gsap'], "3.12.5", true);
  wp_enqueue_script('splitType', 'https://unpkg.com/split-type', [], "0.3.4", false);
  wp_enqueue_script('scrollable', 'https://unpkg.com/scroll-hint@latest/js/scroll-hint.min.js', ['swiper', 'gsap', 'gsap-scroll-trigger', 'splitType'], "1.0.0", true);
  wp_enqueue_script('script-main', get_theme_file_uri('/assets/js/script.js'), ['swiper', 'gsap', 'gsap-scroll-trigger', 'splitType', 'scrollable'], '1.0.0', true);
}
