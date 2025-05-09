<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="format-detection" content="telephone=no">
  <meta http-equiv="X-UA-Compatible" content="ie=edge"><!-- for IE -->
  <?php if (false): ?>
    <?php if (is_404()) : ?>
      <meta http-equiv="refresh" content=" 7; url=<?php echo esc_url(home_url('/')); ?>">
    <?php endif; ?>
  <?php endif; ?>
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>
  <?php
  $nav_items = [
    [
      "nameEN" => "news",
      "nameJP" => "ニュース",
      "slug" => "news"
    ],
    [
      "nameEN" => "プライバシーポリシー",
      "nameJP" => "privacy policy",
      "slug" => "privacy-policy"
    ],
    [
      "nameEN" => "お問い合わせ",
      "nameJP" => "contact",
      "slug" => "contact"
    ],
    [
      "nameEN" => "お問い合わせ",
      "nameJP" => "inquiry",
      "slug" => "inquiry"
    ],
    [
      "nameEN" => "google",
      "nameJP" => "外部リンク",
      "slug" => "https://google.com/"
    ]
  ];
  ?>
  <?php // header 
  ?>
  <header class="p-header js-header">
    <div class="p-header__inner">
      <?php // logo 
      ?>
      <?php if (is_front_page()): ?>
        <h1 class="p-header__logo">
        <?php else: ?>
          <div class="p-header__logo">
          <?php endif; ?>
          <a href="<?php echo esc_url(home_url('/')); ?>">
            <img src="<?php echo get_theme_file_uri(); ?>/assets/images/common/logo.svg" alt="<?php echo esc_html(get_bloginfo('name')); ?>" />
          </a>
          <?php if (!is_front_page()): ?>
          </div>
        <?php else: ?>
        </h1>
      <?php endif; ?>
      <?php // navigation 
      ?>
      <nav class="p-header__nav">
        <ul class="p-header__list">
          <?php foreach ($nav_items as $item): ?>
            <li class="p-header__item">
              <a href="<?php echo (strpos($item['slug'], 'http') === 0) ? esc_url($item['slug']) : esc_url(home_url($item['slug'])); ?>" <?php echo (strpos($item['slug'], 'http') === 0) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?> class="link">
                <span class="en"><?php echo esc_html($item["nameEN"]); ?></span>
                <span class="jp"><?php echo esc_html($item["nameJP"]); ?></span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </nav>

      <!-- hamburger -->
      <div class="p-header__hamburger">
        <div class="c-hamburger js-hamburger">
          <span class="c-hamburger__border"></span>
          <span class="c-hamburger__border"></span>
          <span class="c-hamburger__border"></span>
        </div>
      </div>

    </div>
  </header>

  <?php // drawer 
  ?>
  <div class="p-drawer js-drawer">
    <div class="p-drawer__inner l-inner">
      <div class="p-drawer__nav">
        <ul class="p-drawer__list">
          <?php foreach ($nav_items as $item): ?>
            <li class="p-drawer__item">
              <a href="<?php echo (strpos($item['slug'], 'http') === 0) ? esc_url($item['slug']) : esc_url(home_url($item['slug'])); ?>" <?php echo (strpos($item['slug'], 'http') === 0) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?> class="link">
                <span class="en"><?php echo esc_html($item["nameEN"]); ?></span>
                <span class="jp"><?php echo esc_html($item["nameJP"]); ?></span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </div>
  <div class="p-drawer-mask js-drawer-mask"></div>