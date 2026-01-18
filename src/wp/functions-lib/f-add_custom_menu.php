<?php
// ! カスタムメニューを追加する
function my_custom_menu()
{
	// カスタムカテゴリーのデータを取得
	$categories = get_categories(array('taxonomy' => 'foo_category'));
	$parent_slug = 'manage_options'; // 親メニューのスラッグ

	// トップレベルメニューを追加
	add_menu_page(
		'カスタムメニューのタイトル', // ページタイトル
		'カスタムメニュー', // メニュータイトル
		'manage_options', // 必要な権限（例：manage_options、activate_pluginsなど）
		$parent_slug, // メニュースラッグ
		'my_custom_menu_page', // 表示するコンテンツを生成する関数
		'dashicons-admin-generic', // アイコンURL
		6 // メニュー位置
	);

	// カテゴリーごとにサブメニューを追加
	foreach ($categories as $category) {
		add_submenu_page(
			$parent_slug, // 親メニューのスラッグ
			$category->name, // ページタイトル
			$category->name, // メニュータイトル
			'manage_options', // 必要な権限
			$parent_slug . '-' . $category->slug, // メニュースラッグ
			function () use ($category) { // ここでクロージャを使用
				echo '<div class="wrap"><h2>' . $category->name . '</h2></div>';
			} // 表示するコンテンツを生成する関数
		);
	}
}
function my_custom_menu_page()
{
	echo '<div class="wrap"><h2>カスタムメニュー</h2></div>';
}
add_action('admin_menu', 'my_custom_menu');


// ! トップページ編集リンクをサイドバーメニューに追加（確実な方法）
function add_top_page_link_to_menu()
{
	$page = get_page_by_path('top', OBJECT, 'page');

	if ($page) {
		add_menu_page(
			'トップページ編集',
			'トップページ',
			'edit_pages',
			'post.php?post=' . $page->ID . '&action=edit',
			'',
			'dashicons-admin-home',
			13
		);
	}
}
add_action('admin_menu', 'add_top_page_link_to_menu', 999);  // 優先度を高く設定

// ! ダッシュボードにトップページ編集リンクを表示
function add_top_page_dashboard_widget()
{
	wp_add_dashboard_widget(
		'top_page_edit_widget',
		'トップページ編集',
		'display_top_page_edit_widget'
	);
}
add_action('wp_dashboard_setup', 'add_top_page_dashboard_widget');

function display_top_page_edit_widget()
{
	$page = get_page_by_path('top', OBJECT, 'page');

	if ($page) {
		$edit_url = admin_url('post.php?post=' . $page->ID . '&action=edit');
		echo '<div style="padding: 10px;">';
		echo '<a href="' . esc_url($edit_url) . '" class="button button-primary button-large" style="width: 100%; text-align: center; font-size: 16px; padding: 15px;">トップページを編集</a>';
		echo '</div>';
	} else {
		echo '<p>トップページが見つかりません。</p>';
	}
}
