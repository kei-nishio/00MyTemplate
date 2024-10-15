<?php
// ! カスタム投稿のスラッグをIDで設定する
function custom_post_type_link($post_link, $post)
{
  if (is_object($post) && in_array($post->post_type, array('event', 'developer', 'column'))) {
    return home_url($post->post_type . '/' . $post->ID . '/');
  }
  return $post_link;
}
add_filter('post_type_link', 'custom_post_type_link', 1, 2);

function custom_rewrite_rules($rules)
{
  $new_rules = array(
    '([a-zA-Z0-9_-]+)/([0-9]+)?$' => 'index.php?post_type=$matches[1]&p=$matches[2]',
  );
  return $new_rules + $rules;
}
add_filter('rewrite_rules_array', 'custom_rewrite_rules');
