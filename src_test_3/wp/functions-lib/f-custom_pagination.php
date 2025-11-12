<?php
// ! カスタムページネーションを追加する
/**
 * ページネーションのテンプレートをカスタマイズ
 * @link https://komaricote.com/wordpress/write-pagination/
 * @link https://developer.wordpress.org/reference/functions/the_posts_pagination/
 * 
 */

function custom_pagination($template)
{
  $template = '<div class="c-pagination">%3$s</div>';
  return $template;
}
add_filter('navigation_markup_template', 'custom_pagination');
