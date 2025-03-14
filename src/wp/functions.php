<?php
// ! function集
get_template_part('/functions-lib/f-0base_functions');

// ! headでgoogle fontやCDNを読み込む
get_template_part('/functions-lib/f-0base_script');

// ! 投稿画面の表示項目を設定する
get_template_part('/functions-lib/f-0base_set_up');

// * 以下オプション * //

// ! ACFのフィールドグループをカスタムメニューに追加する
// get_template_part('/functions-lib/f-acf_add_menu');

// ! カスタムメニューを追加する
// get_template_part('/functions-lib/f-add_custom_menu');

// ! Bogoの言語スイッチャーの表記を変更と国旗を非表示
// get_template_part('/functions-lib/f-bogo');

// ! Breadcrumb NavXT で特定ページのパンくずをカスタムする
// get_template_part('/functions-lib/f-breadcrumb-navxt');

// ! Contact Form 7でセレクトボックスをカスタマイズする
get_template_part('/functions-lib/f-cf7_custom_select');

// ! reCAPTCHAを問い合わせだけに表示させる
// get_template_part('/functions-lib/f-cf7_recaptcha');

// ! Contact Form 7で自動挿入されるPタグ、brタグを削除
get_template_part('/functions-lib/f-cf7_reset');

// ! Contact Form 7で郵便番号のバリデーションを追加する
// get_template_part('/functions-lib/f-cf7_validation');

// ! カスタムページネーションを追加する
get_template_part('/functions-lib/f-custom_pagination');

// ! カスタム投稿のスラッグを設定する
get_template_part('/functions-lib/f-custom_post_slug');

// ! カスタム投稿の並び順をユーザーフィールド値に基づいて変更する
// get_template_part('/functions-lib/f-custom_post_sort');

// ! カスタム投稿のカスタムタクソノミーでフィルタリングする
// get_template_part('/functions-lib/f-custom_post_tax_filter');

// ! クエリパラメータ値に基づき、カスタム投稿一覧を投稿者IDでフィルターする
// get_template_part('/functions-lib/f-custom_posts_filter');

// ! カスタム投稿の表示件数を設定する
get_template_part('/functions-lib/f-custom_posts_page');

// ! カスタムタクソノミーのターム数を制限する
// get_template_part('/functions-lib/f-custom_tax_limit');

// ! データをJavaScriptに引き渡す
// get_template_part('/functions-lib/f-js_data');

// ! 通常投稿タイプのラベルを変更する（例：「投稿」⇒「ブログ」）もしくは非表示にする
get_template_part('/functions-lib/f-menu_post');

// ! 管理画面のユーザープロフィール項目をカスタマイズする
// get_template_part('/functions-lib/f-menu_profile');

// ! author（投稿者）ログイン時は管理画面のメニューを制限する
// get_template_part('/functions-lib/f-menu_restrict');

// ! ページのブロックエディタを無効化する
// get_template_part('/functions-lib/f-page_editor');

// ! リダイレクト設定
// get_template_part('/functions-lib/f-redirect');

// ! Smart Custom Field のオプション投稿を追加する
// get_template_part('/functions-lib/f-scf_add_option');

// ! Smart Custom Field のフィールド値を判定する
// get_template_part('/functions-lib/f-scf_field_validation');

// ! 言語によって読み込むテンプレートをカスタムする
// get_template_part('/functions-lib/f-template-customize');