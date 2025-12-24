<?php
// ! グローバル変数の定義
global $theme_uri;
$theme_uri = get_theme_file_uri();

// ! function集
get_template_part('/functions-lib/f-0base_functions');

// ! headでgoogle fontやCDNを読み込む
get_template_part('/functions-lib/f-0base_script');

// ! テーマの基本機能を設定する
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

// ! Contact Form 7でセレクトボックスの値で送信先を変更する
// get_template_part('/functions-lib/f-cf7_custom_mail');

// ! Contact Form 7でセレクトボックスをカスタマイズする
get_template_part('/functions-lib/f-cf7_custom_select');

// ! reCAPTCHAを問い合わせだけに表示させる
// get_template_part('/functions-lib/f-cf7_recaptcha');

// ! Contact Form 7で自動挿入されるPタグ、brタグを削除
get_template_part('/functions-lib/f-cf7_reset');

// ! Contact Form 7で各種バリデーションを追加する
// get_template_part('/functions-lib/f-cf7_validation');

// ! カスタムページネーションを追加する
get_template_part('/functions-lib/f-custom_pagination');

// ! クエリパラメータ値に基づき、カスタム投稿一覧を投稿者IDでフィルターする
// get_template_part('/functions-lib/f-custom_post_filter');

// ! カスタム投稿の一覧画面をカスタマイズする
get_template_part('/functions-lib/f-custom_post_list');

// ! カスタム投稿の表示件数を設定する
get_template_part('/functions-lib/f-custom_post_page');

// ! カスタム投稿のスラッグを設定する
get_template_part('/functions-lib/f-custom_post_slug');

// ! カスタム投稿の並び順をユーザーフィールド値に基づいて変更する
// get_template_part('/functions-lib/f-custom_post_sort');

// ! カスタム投稿の管理画面でタクソノミーフィルターを追加
// get_template_part('/functions-lib/f-custom_post_tax_filter');

// ! カスタムタクソノミーのターム数を制限する
// get_template_part('/functions-lib/f-custom_tax_limit');

// ! Googleしごと検索用の構造化データ出力関数
// get_template_part('/functions-lib/f-google_job_posting');

// ! データをJavaScriptに引き渡す
// get_template_part('/functions-lib/f-js_data');

// ! WordPressログイン画面のロゴと遷移先を変更する
get_template_part('/functions-lib/f-login_logo');

// ! 通常投稿タイプのラベルを変更する（例：「投稿」⇒「ブログ」）もしくは非表示にする
get_template_part('/functions-lib/f-menu_post');

// ! 管理画面のユーザープロフィール項目をカスタマイズする
// get_template_part('/functions-lib/f-menu_profile');

// ! author（投稿者）ログイン時は管理画面のメニューを制限する
// get_template_part('/functions-lib/f-menu_restrict');

// ! ページのブロックエディタを無効化する
// get_template_part('/functions-lib/f-page_editor');

// ! 通常投稿の一覧画面をカスタマイズする
// get_template_part('/functions-lib/f-post_list');

// ! リダイレクト設定
// get_template_part('/functions-lib/f-redirect');

// ! Smart Custom Field のオプション投稿を追加する
// get_template_part('/functions-lib/f-scf_add_option');

// ! Smart Custom Field のフィールド値を判定する
// get_template_part('/functions-lib/f-scf_field_validation');

// ! タクソノミーのキャッシュを保持してget_termsを高速化する（タクソノミー保存時等に更新する）
get_template_part('/functions-lib/f-taxonomy_cache');

// ! 言語によって読み込むテンプレートをカスタムする
// get_template_part('/functions-lib/f-template-customize');