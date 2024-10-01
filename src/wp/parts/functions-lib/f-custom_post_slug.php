<?php
// ! カスタム投稿のスラッグをIDで設定する
function custom_post_type_link($post_link, $post)
{
  if (is_object($post) && in_array($post->post_type, array('case-study', 'voice'))) {
    return home_url($post->post_type . '/' . $post->ID . '/');
  }
  return $post_link;
}
add_filter('post_type_link', 'custom_post_type_link', 1, 2);

function custom_rewrite_rules($rules)
{
  $new_rules = array(
    'case-study/([0-9]+)?$' => 'index.php?post_type=case-study&p=$matches[1]',
    'voice/([0-9]+)?$' => 'index.php?post_type=voice&p=$matches[1]',
  );
  return $new_rules + $rules;
}
add_filter('rewrite_rules_array', 'custom_rewrite_rules');
