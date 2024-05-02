<?php
// ! headでgoogle fontやCDNを読み込む
add_action('wp_enqueue_scripts', 'my_script_init');
function my_script_init()
{
  // jQuery
  // wp_deregister_script('jquery');
  // wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js', "", "3.7.0", true);

  // Google Fonts
  wp_enqueue_style('NotoSansJP', 'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap');
  wp_enqueue_style('Roboto', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap');
  wp_enqueue_style('Oswald', 'https://fonts.googleapis.com/css2?family=Oswald:wght@700&display=swap');

  // CSS
  // wp_enqueue_style('style-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');
  wp_enqueue_style('style-swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
  wp_enqueue_style('style-main', get_theme_file_uri('/assets/css/style.css'), array(), '1.0.0');

  // Script
  wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', "", "11.0", true);
  wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', array('swiper'), "3.12.5", true);
  wp_enqueue_script('gsap-scroll-trigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', array('swiper', 'gsap'), "3.12.5", true);
  wp_enqueue_script('script-main', get_theme_file_uri('/assets/js/script.js'), array('swiper', 'gsap', 'gsap-scroll-trigger'), '1.0.0', true);
}
