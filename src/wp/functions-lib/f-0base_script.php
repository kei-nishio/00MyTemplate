<?php
// ! headでgoogle fontやCDNを読み込む
add_action('wp_enqueue_scripts', 'my_script_init');
function my_script_init()
{
  // ! jQuery
  // wp_deregister_script('jquery');
  // wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js', "", "3.7.0", true);

  // ! free fonts
  /** @link https://yakuhanjp.qranoko.jp/ */
  wp_enqueue_style('YakuHanJP', 'https://cdn.jsdelivr.net/npm/yakuhanjp@4.1.1/dist/css/yakuhanjp.css');

  // ! Google Fonts
  /**
   * @uses &display=swap
   *    - フォント読み込み中にフォールバックフォントを表示し、読み込み完了後に指定フォントに切り替える。
   *    - ページの表示速度を向上させるが、フォント切り替え時にレイアウトのズレ（CLS）が発生する可能性がある。
   *
   * @uses &display=block
   *    - フォントが完全に読み込まれるまでテキストを非表示（FOIT: Flash of Invisible Text）。
   *    - 一貫したデザインを保つが、フォント読み込みが遅いと空白時間が発生する。
   *
   * @uses &display=fallback
   *    - 一定時間（約100ms）フォントを待ち、読み込まれなかった場合はフォールバックフォントを適用。
   *    - その後フォントが読み込まれたら置き換える。
   *    - FOUT（フォントのチラつき）はあるが、FOITは防げる。
   *
   * @uses &display=optional
   *    - `fallback` に似ているが、ネットワーク状況が悪い場合はフォールバックフォントのまま適用されることもある。
   *    - モバイルなど低速環境向けに最適。
   *
   * @uses &display=auto
   *    - ブラウザのデフォルトの動作に従う。
   *    - 一般的に `block` に近い動作をするが、環境によって異なる挙動を取る可能性がある。
   */
  wp_enqueue_style('zen-kaku-gothic-new', 'https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New:wght@300;400;500;700;900&display=swap');
  wp_enqueue_style('Jost', 'https://fonts.googleapis.com/css2?family=Jost:wght@600;700&display=swap');

  // ! CSS
  wp_enqueue_style('style-reset', get_theme_file_uri('/assets/css/ress.min.css'), [], '5.0.2');
  wp_enqueue_style('scroll-hint', 'https://unpkg.com/scroll-hint@latest/css/scroll-hint.css');
  // wp_enqueue_style('style-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');
  wp_enqueue_style('style-swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', ['style-reset'], '11.0');
  wp_enqueue_style('style-main', get_theme_file_uri('/assets/css/style.css'), ['style-reset', 'style-swiper'], '1.0.0');

  // ! Script
  // wp_enqueue_script('jquery'); 
  wp_enqueue_script('splitType', 'https://unpkg.com/split-type', [], "0.3.4", false);
  wp_enqueue_script('scrollable', 'https://unpkg.com/scroll-hint@latest/js/scroll-hint.min.js', [], "1.0.0", true);
  wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', "", "11.0", true);
  wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', [], "3.12.5", true);
  wp_enqueue_script('gsap-scroll-trigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', ['gsap'], "3.12.5", true);
  wp_enqueue_script('gsap-split-text', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/SplitText.min.js', ['gsap'], "3.12.5", true);
  wp_enqueue_script('script-main', get_theme_file_uri('/assets/js/script.js'), [], '1.0.0', true);
}

// ! Google Fonts・CDN高速化のためのpreconnect
add_action('wp_head', 'add_preconnect_links');
function add_preconnect_links()
{
  echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
  echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
  echo '<link rel="preconnect" href="https://cdn.jsdelivr.net">' . "\n";
  echo '<link rel="preconnect" href="https://cdnjs.cloudflare.com">' . "\n";
  echo '<link rel="preconnect" href="https://unpkg.com">' . "\n";
}
