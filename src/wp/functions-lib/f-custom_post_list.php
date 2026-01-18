<?php
// ! カスタム投稿の一覧画面をカスタマイズする
// ? 基本構文：add_filter('manage_edit-{post_type}_columns', '関数名');

// *  カスタム投稿の一覧にアイキャッチ画像を表示する
function add_custom_post_thumbnail_column($columns)
{
	$columns['thumbnail'] = 'アイキャッチ';
	return $columns;
}
function show_custom_post_thumbnail_column($column_name, $post_id)
{
	if ($column_name === 'thumbnail') {
		$thumbnail = get_the_post_thumbnail($post_id, [50, 50]);
		echo $thumbnail ?: 'なし';
	}
}
// builder 投稿タイプに適用
add_filter('manage_edit-builder_columns', 'add_custom_post_thumbnail_column');
add_action('manage_builder_posts_custom_column', 'show_custom_post_thumbnail_column', 10, 2);
// column 投稿タイプに適用
add_filter('manage_edit-column_columns', 'add_custom_post_thumbnail_column');
add_action('manage_column_posts_custom_column', 'show_custom_post_thumbnail_column', 10, 2);

// *　カスタム投稿の一覧で不要なカラムを削除する
function remove_unwanted_custom_post_columns($columns)
{
	unset($columns['tags']);   // タグ列を削除
	unset($columns['date']);   // 日付列を削除
	unset($columns['author']); // 投稿者列を削除
	return $columns;
}
// add_filter('manage_edit-{post_type1}_columns', 'remove_unwanted_custom_post_columns');

// * カスタム投稿の一覧でカスタムフィールド画像を表示する
function customize_case_columns($columns)
{
	unset($columns['thumbnail']); // デフォルトのアイキャッチ画像を削除
	$columns['case_thumbnail'] = 'カスタム画像'; // ACF の画像用カラムを追加
	return $columns;
}
function display_case_custom_thumbnail($column_name, $post_id)
{
	if ($column_name === 'case_thumbnail') {
		$group = get_field('acfCaseEyecatchGroups', $post_id);
		$image_data = $group[0]['acfCaseEyecatch'] ?? null;

		if (isset($image_data['url'])) {
			// 配列の 'url' キーから画像URLを取得
			$image_url = esc_url($image_data['url']);
			echo '<img src="' . $image_url . '" width="50" height="50" alt="ACF画像">';
		} else {
			echo 'なし'; // 画像がない場合
		}
	}
}
add_filter('manage_edit-case_columns', 'customize_case_columns');
add_action('manage_case_posts_custom_column', 'display_case_custom_thumbnail', 10, 2);
