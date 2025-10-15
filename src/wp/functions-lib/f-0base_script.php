<?php
// ! headでgoogle fontやCDNを読み込む
add_action('wp_enqueue_scripts', 'my_script_init');
function my_script_init()
{
  // ! Global Variable
  global $theme_uri;

  // ! jQuery
  // wp_deregister_script('jquery');
  // wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js', "", "3.7.0", true);

  // ! CDN free fonts
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
  $google_fonts_url = 'https://fonts.googleapis.com/css2?family=Fustat:wght@200..800&family=Noto+Sans+JP:wght@100..900&display=swap';
  wp_enqueue_style('google-fonts', $google_fonts_url, [], null);

  // ! CSS
  wp_enqueue_style('style-reset', esc_url($theme_uri . '/assets/css/ress.min.css'), [], '5.0.2');
  wp_enqueue_style('scroll-hint', 'https://unpkg.com/scroll-hint@latest/css/scroll-hint.css');
  // wp_enqueue_style('style-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');
  wp_enqueue_style('style-swiper', 'https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css', ['style-reset']);
  wp_enqueue_style('style-main', esc_url($theme_uri . '/assets/css/style.css'), ['style-reset', 'style-swiper', 'google-fonts'], date('YmdHis'));

  // ! Local font
  // $otf = esc_url(get_template_directory_uri() . '/assets/font/YDWbananaslipplus.otf');
  // $inline = "
  // @font-face{
  //   font-family:'YDWbananaslipplus';
  //   src:url('{$otf}') format('opentype');
  //   font-weight:400;
  //   font-style:normal;
  //   font-display:swap;
  // }";
  // wp_add_inline_style('style-main', $inline);

  // ! Script
  // wp_enqueue_script('jquery'); 
  wp_enqueue_script('splitType', 'https://unpkg.com/split-type', [], "0.3.4", false);
  wp_enqueue_script('scrollable', 'https://unpkg.com/scroll-hint@latest/js/scroll-hint.min.js', [], "1.0.0", true);
  wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js', [], "12.0.0", true);
  wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', [], "3.12.5", true);
  wp_enqueue_script('gsap-scroll-trigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', ['gsap'], "3.12.5", true);
  wp_enqueue_script('gsap-split-text', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/SplitText.min.js', ['gsap'], "3.12.5", true);
  wp_enqueue_script('script-main', esc_url($theme_uri . '/assets/js/script.js'), [], '1.0.0', true);
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
