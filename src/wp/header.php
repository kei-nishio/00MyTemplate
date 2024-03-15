<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="format-detection" content="telephone=no">
  <meta http-equiv="X-UA-Compatible" content="ie=edge"><!-- for IE -->
  <?php if (is_404()) : ?>
    <meta http-equiv="refresh" content=" 3; url=<?php echo esc_url(home_url('/')); ?>">
  <?php endif; ?>
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>
  <header class="header js-header">
    <div class="header__inner">

      <!-- logo  -->
      <h1 class="header__logo">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="header__logo-link">
          <img src="<?php echo get_theme_file_uri(); ?>/assets/images/common/logo1.svg" alt="ヘッダーロゴ" />
        </a>
      </h1>

      <!-- navigation -->
      <nav class="header__nav pc-nav u-desktop">
        <ul class="pc-nav__items">
          <li class="pc-nav__item">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="pc-nav__link">top</a>
          </li>
          <li class="pc-nav__item">
            <a href="<?php echo esc_url(home_url('/about')); ?>" class="pc-nav__link">about</a>
          </li>
          <li class="pc-nav__item">
            <a href="<?php echo esc_url(home_url('/service')); ?>" class="pc-nav__link">service</a>
          </li>
          <li class="pc-nav__item">
            <a href="<?php echo esc_url(home_url('/works')); ?>" class="pc-nav__link">works</a>
          </li>
          <li class="pc-nav__item">
            <a href="<?php echo esc_url(home_url('/news')); ?>" class="pc-nav__link">news</a>
          </li>
          <li class="pc-nav__item">
            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="pc-nav__link pc-nav__link--blue">
              <i class="fa-regular fa-envelope"></i>
              <span>contact</span>
            </a>
          </li>
        </ul>
      </nav>

      <!-- hamburger -->
      <div class="header__hamburger hamburger u-mobile js-hamburger">
        <span class="hamburger__border"></span>
        <span class="hamburger__border"></span>
        <span class="hamburger__border hamburger__border--short"></span>
      </div>

      <!-- drawer -->
      <div class="header__drawer u-mobile js-drawer-menu">
        <div class="header__drawer-header">
          <div class="header__logo">
            <div class="header__logo-link">
              <img src="<?php echo get_theme_file_uri(); ?>/assets/images/common/logo2.svg" alt="ヘッダーロゴ" />
            </div>
          </div>
        </div>
        <div class="header__drawer-inner">
          <nav class="header__sp-nav navigation">
            <ul class="navigation__items">
              <li class="navigation__item">
                <a href="<?php echo esc_url(home_url('/')); ?>">top</a>
              </li>
              <li class="navigation__item">
                <a href="<?php echo esc_url(home_url('/about')); ?>">about</a>
              </li>
              <li class="navigation__item">
                <a href="<?php echo esc_url(home_url('/service')); ?>">service</a>
              </li>
              <li class="navigation__item">
                <a href="<?php echo esc_url(home_url('/works')); ?>">works</a>
              </li>
              <li class="navigation__item">
                <a href="<?php echo esc_url(home_url('/news')); ?>">news</a>
              </li>
              <li class="navigation__item">
                <a href="<?php echo esc_url(home_url('/contact')); ?>">contact</a>
              </li>
              <li class="navigation__item">
                <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>">プライバシーポリシー</a>
              </li>
            </ul>
          </nav>

        </div>
      </div>
      <div class="header__drawer-circle-bg js-header-circle"></div>
    </div>
  </header>