<?php
// ! 通常投稿の一覧画面をカスタマイズする
// * 通常投稿の一覧にアイキャッチ画像のカラムを作成して表示する
function add_thumbnail_column($columns)
{
	$columns = array_slice($columns, 0, 1, true) +
		array('thumbnail' => 'アイキャッチ') +
		array_slice($columns, 1, null, true);
	return $columns;
}
function display_thumbnail_column($column_name, $post_id)
{
	if ($column_name === 'thumbnail') {
		$thumbnail = get_the_post_thumbnail($post_id, [50, 50]);
		echo $thumbnail ? $thumbnail : 'なし';
	}
}
add_filter('manage_posts_columns', 'add_thumbnail_column');
add_action('manage_posts_custom_column', 'display_thumbnail_column', 10, 2);

// * 通常投稿の一覧で不要なカラムを削除する
function remove_tags_column($columns)
{
	unset($columns['tags']); // 'tags' = タグ列
	return $columns;
}
add_filter('manage_posts_columns', 'remove_tags_column');
