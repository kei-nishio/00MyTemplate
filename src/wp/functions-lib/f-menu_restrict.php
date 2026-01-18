<?php
// ! author（投稿者）ログイン時は管理画面のメニューを制限する
function restrict_admin_menu_for_author()
{
	// 現在のユーザーが「author」ロールの場合
	if (current_user_can('author') && !current_user_can('edit_others_posts')):
		global $menu, $submenu;

		// 表示したいメニュー項目とカスタム投稿タイプのスラッグを設定
		$allowed_menu_items = [
			'edit.php?post_type=event',    // カスタム投稿タイプ「event」
			'edit.php?post_type=developer', // カスタム投稿タイプ「developer」
			'upload.php',                  // メディア
			'profile.php'                  // 自分のプロフィール
		];

		// メニューの項目を一旦すべて非表示にする
		foreach ($menu as $key => $value):
			$menu_slug = $value[2];

			// メニュー項目のスラッグが許可されたリストにない場合は非表示
			if (!in_array($menu_slug, $allowed_menu_items)):
				unset($menu[$key]);
			endif;
		endforeach;

		// サブメニューの制限（必要に応じて）
		foreach ($submenu as $key => $value):
			if (!in_array($key, $allowed_menu_items)):
				unset($submenu[$key]);
			endif;
		endforeach;
	endif;
}
add_action('admin_menu', 'restrict_admin_menu_for_author', 999);

// ! author（投稿者）ログイン時は自分の投稿のみ表示する
function restrict_user_posts_to_own($query)
{
	if (is_admin() && $query->is_main_query() && !current_user_can('edit_others_posts')):
		$current_user = wp_get_current_user();
		$query->set('author', $current_user->ID);
	endif;
}
add_action('pre_get_posts', 'restrict_user_posts_to_own');

// ! author（投稿者）ログイン時は自分のメディアのみ表示する
function show_only_own_media_in_library($query)
{
	// 管理者以外のユーザーに対してのみ制限を適用
	if (!current_user_can('manage_options')):
		// 現在のユーザーIDを取得
		$current_user_id = get_current_user_id();
		// 自分のアップロードしたメディアのみ表示
		$query['author'] = $current_user_id;
	endif;
	return $query;
}
add_filter('ajax_query_attachments_args', 'show_only_own_media_in_library');
