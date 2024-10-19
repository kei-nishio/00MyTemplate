<?php
// ! カスタム投稿一覧を投稿者でクエリでフィルターする
function filter_author_id_query($query)
{
  if (is_post_type_archive('event') && $query->is_main_query() && isset($_GET['author_id'])):
    $query->set('author', sanitize_text_field($_GET['author_id']));
  endif;
}
add_action('pre_get_posts', 'filter_author_id_query');
