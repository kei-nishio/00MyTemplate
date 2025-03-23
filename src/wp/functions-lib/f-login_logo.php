<?php
// ! WordPressログイン画面のロゴと遷移先を変更する
// ログイン画面のロゴ画像を変更（アスペクト比維持）
function my_login_logo()
{
  echo '<style>
    .login h1 a {
      box-sizing: border-box;
      background-image: url(' . esc_url(get_theme_file_uri('/assets/images/common/logo.svg')) . ');
      height: 80px;
      background-position: center;
      background-size: contain;
      background-repeat: no-repeat;
      display: block;
      width: calc(100% - 2 * 20px);
      margin-inline:20px;
      margin-bottom: initial;
    }
  </style>';
}
add_action('login_head', 'my_login_logo');
// ロゴクリック時のリンク先をトップページに変更
add_filter('login_headerurl', fn() => home_url());
// ロゴのtitle属性にサイト名を動的に表示
add_filter('login_headertext', function () {
  return get_bloginfo('name');
});
