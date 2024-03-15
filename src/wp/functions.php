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

// ! headでgoogle fontやCDNを読み込む
get_template_part('/parts/functions-lib/fncs_script');

// ! カスタム投稿の表示件数を設定する
get_template_part('/parts/functions-lib/fncs_posts_page');

// ! Contact Form 7で自動挿入されるPタグ、brタグを削除
get_template_part('/parts/functions-lib/fncs_wpcf7_reset');

// ! Contact Form 7でセレクトボックスをカスタマイズする
get_template_part('/parts/functions-lib/fncs_wpcf7_custom_select');

// ! 通常投稿タイプのラベルを変更する
get_template_part('/parts/functions-lib/fncs_post_label_change');

// ! 個別記事なし投稿の場合リダイレクトする
get_template_part('/parts/functions-lib/fncs_redirect_singular');

// ! Smart Custom Field のオプション投稿を追加する
get_template_part('/parts/functions-lib/fncs_scf_add_option');

// ! Smart Custom Field のフィールド値を判定する
get_template_part('/parts/functions-lib/fncs_scf_field_validation');

// ! カスタムページネーションを追加する
get_template_part('/parts/functions-lib/fncs_custom_pagination');

// ! カスタムメニューを追加する
// get_template_part('/parts/functions-lib/fncs_add_menu');