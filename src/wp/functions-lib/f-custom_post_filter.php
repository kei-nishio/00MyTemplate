<?php
// ! カスタム投稿一覧をURLのクエリパラメータ ?author_id=◯◯ が指定されたときに、その投稿者の投稿だけを表示する
function filter_author_id_query($query)
{
  $post_types = array('builder', 'event', 'case');
  if (is_main_query() && isset($_GET['author_id']) && is_post_type_archive($post_types)):
    $query->set('author', sanitize_text_field($_GET['author_id']));
  endif;
}
add_action('pre_get_posts', 'filter_author_id_query');
