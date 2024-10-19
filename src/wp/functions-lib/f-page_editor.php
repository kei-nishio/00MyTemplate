<?php
// ! ブロックエディタを無効化する
// 固定ページの場合
add_filter('use_block_editor_for_post', function ($use_block_editor, $post) {
  if ($post->post_type === 'page') {
    if (in_array($post->post_name, ['flow', 'news'])) {
      remove_post_type_support('page', 'editor');
      return false;
    }
  }
  return $use_block_editor;
}, 10, 2);


// カスタム投稿の場合
add_action('init', 'my_remove_post_support');
function my_remove_post_support()
{
  remove_post_type_support('case-study', 'editor');
}
