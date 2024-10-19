<?php
// ! カスタムメニューを追加する
add_action('admin_menu', 'my_custom_menu');
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
