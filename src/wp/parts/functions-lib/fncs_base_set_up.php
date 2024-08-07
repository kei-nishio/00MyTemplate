<?php
// ! 投稿画面の表示項目を設定する
add_action('after_setup_theme', 'my_setup');
function my_setup()
{
  add_theme_support('post-thumbnails'); // アイキャッチ画像を有効化
  add_theme_support('automatic-feed-links'); // 投稿とコメントのRSSフィードのリンクを有効化
  add_theme_support('title-tag'); // titleタグ自動生成
  add_theme_support('html5', array( // HTML5による出力
    'search-form',
    'comment-form',
    'comment-list',
    'gallery',
    'caption',
  ));
}
