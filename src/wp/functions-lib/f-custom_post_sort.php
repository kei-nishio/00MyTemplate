<?php
// ! カスタム投稿の並び順をユーザーフィールド値に基づいて変更する
function sort_posts_by_user_first_name($query)
{
  $post_types = array('builder'); // 複数のカスタム投稿タイプを指定

  if ($query->is_main_query() && !is_admin() && is_post_type_archive($post_types)):
    $query->set('posts_per_page', -1); // すべての投稿を取得

    add_filter('posts_results', function ($posts) {
      foreach ($posts as $post):
        $user_id = $post->post_author;
        $post->user_first_name = get_user_meta($user_id, 'first_name', true);
      endforeach;

      // 名（user_first_name）＝ふりがなでソート
      usort($posts, function ($a, $b) {
        return strcmp($a->user_first_name, $b->user_first_name);
      });

      return $posts;
    });
  endif;
}
add_action('pre_get_posts', 'sort_posts_by_user_first_name');
