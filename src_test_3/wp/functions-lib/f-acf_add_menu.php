<?php
// ! ACFのフィールドグループをカスタムメニューに追加する
/**
 * @uses  $image[$i] = get_field('acfBrandImage0' . $i, 'options');
 * @uses  $image_url[$i]  = esc_url($image[$i]['url']);
 * @uses  $image_alt[$i]  = esc_attr($image[$i]['alt']);
 * @uses  $image_alt[$i] = $image_alt[$i] === "" ? "コレクション" . $i : $image_alt[$i];
 */


function my_custom_acf_menu()
{
  $menu_slug = 'hoge'; // メニュースラッグを変数として定義
  $field_group_key = 'group_66b47ed487504'; // フィールドグループのキー

  // フィールドグループの情報を取得
  $field_group = acf_get_field_group($field_group_key);
  $field_group_title = $field_group['title'];

  // トップレベルメニューを追加
  add_menu_page(
    $field_group_title, // ページタイトル
    $field_group_title, // メニュータイトル
    'manage_options', // 必要な権限
    $menu_slug, // 変数として定義したメニュースラッグ
    function () use ($field_group_key, $menu_slug) {
      my_custom_acf_menu_page($field_group_key, $menu_slug);
    },
    'dashicons-heart', // アイコンURL
    6 // メニュー位置
  );
}

function my_custom_acf_menu_page($field_group_key, $menu_slug)
{
  if (!current_user_can('manage_options')) {
    wp_die(__('このページにアクセスする権限がありません。'));
  }

  // フィールドグループの情報を取得
  $field_group = acf_get_field_group($field_group_key);
  $field_group_title = $field_group['title'];

  acf_form_head();

  echo '<div class="wrap"><h2>' . esc_html($field_group_title) . '</h2>';

  // ACFフォームを表示
  acf_form(array(
    'post_id' => 'options', // オプションページのフィールドを表示する場合
    'field_groups' => array($field_group_key), // フィールドグループのIDを指定
    'return' => admin_url('admin.php?page=' . $menu_slug), // カスタムメニューにリダイレクト
    'submit_value' => __('更新', 'acf'),
  ));

  echo '</div>';
  ob_end_flush();
}

add_action('admin_menu', 'my_custom_acf_menu');
add_action('admin_init', function () {
  ob_start(); // 出力バッファリングを管理画面全体に適用
});
