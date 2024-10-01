<?php
// ! Smart Custom Field のオプション投稿を追加する
/**
 * @param string $page_title ページのtitle属性値
 * @param string $menu_title 管理画面のメニューに表示するタイトル
 * @param string $capability メニューを操作できる権限（manage_options とか）
 * @param string $menu_slug オプションページのスラッグ。ユニークな値にすること。
 * @param string|null $icon_url メニューに表示するアイコンの URL
 * @link https://developer.wordpress.org/resource/dashicons/
 * @param int $position メニューの位置
 * @link https://2inc.org/blog/2016/06/06/5280/
 * @uses SCF::add_options_page('料金', '料金一覧', 'manage_options', 'price-options', 'dashicons-money-alt', '6');
 */

//  ギャラリー
SCF::add_options_page('ギャラリー', 'ギャラリー', 'manage_options', 'gallery-options', 'dashicons-format-gallery', '8');


SCF::add_options_page('よくある質問', 'よくある質問', 'manage_options', 'faq-options', 'dashicons-list-view
', '11');
