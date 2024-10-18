<?php
// ! 投稿画面の表示項目を設定する
get_template_part('/parts/functions-lib/f-base_set_up');

// ! headでgoogle fontやCDNを読み込む
get_template_part('/parts/functions-lib/f-base_script');

// * 以下オプション * //

// ! ACFのフィールドグループをカスタムメニューに追加する
// get_template_part('/parts/functions-lib/f-acf_add_menu');

// ! カスタムメニューを追加する
// get_template_part('/parts/functions-lib/f-add_custom_menu');

// ! Bogoの言語スイッチャーの表記を変更
// get_template_part('/parts/functions-lib/f-bogo-la-notation');

// ! Bogoの言語スイッチャーの国旗を非表示
// get_template_part('/parts/functions-lib/f-bogo-noflag');

// ! Breadcrumb NavXT で特定ページのパンくずをカスタムする
// get_template_part('/parts/functions-lib/f-breadcrumb-navxt');

// ! reCAPTCHAを問い合わせだけに表示させる
// get_template_part('/parts/functions-lib/f-cf7_recaptcha');

// ! Contact Form 7で自動挿入されるPタグ、brタグを削除
// get_template_part('/parts/functions-lib/f-cf7_reset');

// ! Contact Form 7でセレクトボックスをカスタマイズする
// get_template_part('/parts/functions-lib/f-cf7_custom_select');

// ! Contact Form 7で郵便番号のバリデーションを追加する
// get_template_part('/parts/functions-lib/f-cf7_validation');

// ! カスタムページネーションを追加する
// get_template_part('/parts/functions-lib/f-custom_pagination');

// ! カスタム投稿の表示件数を設定する
// get_template_part('/parts/functions-lib/f-custom_posts_page');

// ! カスタムタクソノミーのターム数を制限する
// get_template_part('/functions-lib/f-custom_tax_limit');

// ! カスタム投稿のスラッグを設定する
// get_template_part('/parts/functions-lib/f-custom_post_slug');

// ! データをJavaScriptに引き渡す
// get_template_part('/functions-lib/f-js_data');

// ! 固定ページのブロックエディタを無効化する
// get_template_part('/parts/functions-lib/f-page_editor');

// ! 通常投稿タイプのラベルを変更する（「投稿」⇒「ブログ」）
// get_template_part('/parts/functions-lib/f-post_label');

// ! リダイレクト設定
// get_template_part('/parts/functions-lib/f-redirect');

// ! Smart Custom Field のオプション投稿を追加する
// get_template_part('/parts/functions-lib/f-scf_add_option');

// ! Smart Custom Field のフィールド値を判定する
// get_template_part('/parts/functions-lib/f-scf_field_validation');

// ! 言語によって読み込むテンプレートをカスタムする
// get_template_part('/parts/functions-lib/f-template-customize');

// ! reCAPTCHAを問い合わせだけに表示させる
add_action('wp_enqueue_scripts', function () {
  if (is_page(array('contact', 'confirm', 'thanks'))) return;
  wp_deregister_script('google-recaptcha');
}, 100, 0);
