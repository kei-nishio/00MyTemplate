<?php
// ! 個別記事なし投稿の場合リダイレクトする
add_action('template_redirect', 'custom_redirect_custom_post_types');
function custom_redirect_custom_post_types()
{
  if (is_singular(array('campaign', 'voice'))) {
    wp_redirect(home_url('/'), 302);
    exit;
  }
}
