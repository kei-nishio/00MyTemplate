<?php
// ! カスタム投稿の表示件数を設定する
add_action('pre_get_posts', 'change_posts_per_page');
function change_posts_per_page($query)
{
  if (is_admin() || !$query->is_main_query()) {
    return;
  }

  // カスタム投稿タイプ「campaign」のアーカイブページでの表示件数を設定
  if ($query->is_post_type_archive('campaign')) {
    $query->set('posts_per_page', 4);
  }
  if ($query->is_tax('campaign_category')) {
    $query->set('posts_per_page', 4);
  }

  // カスタム投稿タイプ「voice」のアーカイブページでの表示件数を設定
  if ($query->is_post_type_archive('voice')) {
    $query->set('posts_per_page', 6);
  }
  if ($query->is_tax('voice_category')) {
    $query->set('posts_per_page', 6);
  }
}
