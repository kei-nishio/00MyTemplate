<?php
// ! カスタム投稿一覧を投稿者でクエリでフィルターする
function filter_author_id_query($query)
{
  $post_types = array('builder', 'event', 'case');
  if (is_main_query() && isset($_GET['author_id']) && is_post_type_archive($post_types)):
    $query->set('author', sanitize_text_field($_GET['author_id']));
  endif;
}
add_action('pre_get_posts', 'filter_author_id_query');
