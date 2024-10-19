<?php
// ! カスタム投稿の表示件数を設定する
add_action('pre_get_posts', 'change_posts_per_page');
function change_posts_per_page($query)
{
  if (is_admin() || !$query->is_main_query()) {
    return;
  }

  $posts_per_page = wp_is_mobile() ? 2 : 3;

  // カスタム投稿タイプ「news」のアーカイブページの表示件数を設定
  if ($query->is_post_type_archive('news')) {
    $query->set('posts_per_page', $posts_per_page);
    return;
  }
  if ($query->is_tax('news-type')) {
    $query->set('posts_per_page', $posts_per_page);
    return;
  }

  // カスタム投稿タイプのアーカイブページの表示件数を設定
  $default_posts_per_page = wp_is_mobile() ? 2 : 3;
  if ($query->is_post_type_archive()) {
    $query->set('posts_per_page', $default_posts_per_page);
    return;
  }
  if ($query->is_tax()) {
    $query->set('posts_per_page', $default_posts_per_page);
    return;
  }
}
