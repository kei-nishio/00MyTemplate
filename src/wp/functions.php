<?php
// ! 投稿画面の表示項目を設定する
get_template_part('/parts/functions-lib/fncs_base_set_up');

// ! headでgoogle fontやCDNを読み込む
get_template_part('/parts/functions-lib/fncs_base_script');

// * 以下オプション * //

// ! ACFのフィールドグループをカスタムメニューに追加する
get_template_part('/parts/functions-lib/fncs_acf_add_menu');

// ! カスタムメニューを追加する
get_template_part('/parts/functions-lib/fncs_add_custom_menu');

// ! Bogoの言語スイッチャーの表記を変更
get_template_part('/parts/functions-lib/fncs_bogo-la-notation');

// ! Bogoの言語スイッチャーの国旗を非表示
get_template_part('/parts/functions-lib/fncs_bogo-noflag');

// ! Breadcrumb NavXT で特定ページのパンくずをカスタムする
get_template_part('/parts/functions-lib/fncs_breadcrumb-navxt');

// ! Contact Form 7で自動挿入されるPタグ、brタグを削除
get_template_part('/parts/functions-lib/fncs_cf7_reset');

// ! Contact Form 7でセレクトボックスをカスタマイズする
get_template_part('/parts/functions-lib/fncs_cf7_custom_select');

// ! Contact Form 7で郵便番号のバリデーションを追加する
get_template_part('/parts/functions-lib/fncs_cf7_zip-code-validation');

// ! カスタムページネーションを追加する
get_template_part('/parts/functions-lib/fncs_custom_pagination');

// ! カスタム投稿の表示件数を設定する
get_template_part('/parts/functions-lib/fncs_custom_posts_page');

// ! カスタム投稿のスラッグを設定する
get_template_part('/parts/functions-lib/fncs_custom_post_slug');

// ! 固定ページのブロックエディタを無効化する
get_template_part('/parts/functions-lib/fncs_page_editor');

// ! 通常投稿タイプのラベルを変更する（「投稿」⇒「ブログ」）
get_template_part('/parts/functions-lib/fncs_post_label');

// ! リダイレクト設定
get_template_part('/parts/functions-lib/fncs_redirect');

// ! Smart Custom Field のオプション投稿を追加する
get_template_part('/parts/functions-lib/fncs_scf_add_option');

// ! Smart Custom Field のフィールド値を判定する
get_template_part('/parts/functions-lib/fncs_scf_field_validation');

// ! 言語によって読み込むテンプレートをカスタムする
get_template_part('/parts/functions-lib/fncs_template-customize');

// ! reCAPTCHAを問い合わせだけに表示させる
add_action('wp_enqueue_scripts', function () {
  if (is_page(array('contact', 'confirm', 'thanks'))) return;
  wp_deregister_script('google-recaptcha');
}, 100, 0);
