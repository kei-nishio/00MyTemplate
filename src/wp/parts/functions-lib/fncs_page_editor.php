<?php
// ! 固定ページのブロックエディタを無効化する
add_filter('use_block_editor_for_post', function ($use_block_editor, $post) {
  if ($post->post_type === 'page') {
    // ページスラッグが「privacy-policy」または「terms-of-service」の場合のみブロックエディタを有効化
    if (!in_array($post->post_name, ['privacy-policy', 'terms-of-service'])) {
      remove_post_type_support('page', 'editor');
      return false;
    }
  }
  return $use_block_editor;
}, 10, 2);