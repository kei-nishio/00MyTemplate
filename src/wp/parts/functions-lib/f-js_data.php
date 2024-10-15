<?php
// ! データをJavaScriptに引き渡す
// get_template_part('/functions-lib/f-js-data');
// function enqueue_custom_script()
// {
//   // contact ページかどうかを確認し、データを渡す
//   if (is_page('contact')) :
//     $author_id_get = isset($_GET['author-id']) ? intval($_GET['author-id']) : null;
//     $selected_author = $author_id_get ? get_user_by('ID', $author_id_get) : null;
//     $target = $selected_author ? $selected_author->last_name : 'default';

//     wp_localize_script('script-main', 'phpData', [
//       'target' => $target,
//     ]);
//   endif;
// }
// add_action('wp_enqueue_scripts', 'enqueue_custom_script');
