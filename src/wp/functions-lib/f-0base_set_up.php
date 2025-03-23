<?php
// ! テーマの基本機能を設定する
function my_setup()
{
  // ! 投稿機能
  add_theme_support('post-thumbnails'); // アイキャッチ画像を有効化
  add_theme_support('automatic-feed-links'); // RSSフィードのリンクを自動挿入
  add_theme_support('title-tag'); // <title>タグを自動生成

  // ! HTML5対応
  add_theme_support('html5', array(
    'search-form',
    'comment-form',
    'comment-list',
    'gallery',
    'caption',
  ));

  // ! ブロックエディタ対応（Gutenberg）
  add_theme_support('wp-block-styles'); // ブロック標準スタイル有効化
  add_theme_support('responsive-embeds'); // 埋め込みコンテンツのレスポンシブ化
  // add_theme_support('editor-styles'); // エディタ用CSSの適用を許可
  // add_editor_style('assets/css/editor-style.css'); // エディタ用のCSSファイルを読み込む
}
add_action('after_setup_theme', 'my_setup');
