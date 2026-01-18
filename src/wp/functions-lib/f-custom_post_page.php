<?php
// ! カスタム投稿の表示件数を設定する
add_action('pre_get_posts', 'change_posts_per_page');
function change_posts_per_page($query)
{
	if (is_admin() || !$query->is_main_query()) {
		return;
	}

	// すべての記事を表示するカスタム投稿タイプとカスタムタクソノミーを配列で指定
	$all_posts_custom_post_types = ['builder', 'package'];
	$all_posts_custom_taxonomies = ['builder-area', 'renovation-area'];
	if ($query->is_post_type_archive($all_posts_custom_post_types) || $query->is_tax($all_posts_custom_taxonomies)) {
		$query->set('posts_per_page', -1);
		return;
	}

	// カスタム投稿タイプ「news」のアーカイブページの表示件数を設定
	$posts_per_page = wp_is_mobile() ? 12 : 12;
	$custom_post_types = ['event', 'case', 'column'];
	$custom_taxonomies = ['event-area', 'case-location', 'case-area', 'case-budget'];
	if ($query->is_post_type_archive($custom_post_types) || $query->is_tax($custom_taxonomies)) {
		$query->set('posts_per_page', $posts_per_page);
		return;
	}

	// その他のカスタム投稿タイプのアーカイブページの表示件数を設定
	$default_posts_per_page = wp_is_mobile() ? 10 : 10;
	if ($query->is_post_type_archive() || $query->is_tax()) {
		$query->set('posts_per_page', $default_posts_per_page);
	}
}
