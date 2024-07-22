<?php
// ! 特定ページからのリダイレクト設定
add_action('template_redirect', 'redirect_page');
function redirect_page()
{
  if (is_post_type_archive('case-study') && !is_front_page()) {
    wp_redirect(home_url('/tax-case-study-place/bath-room/'), 301);
    exit;
  }
}
