<?php
// ! 言語によって読み込むテンプレートをカスタムする
add_filter('template_include', 'load_custom_template_based_on_language', 99);
function load_custom_template_based_on_language($template)
{
  $current_language = get_locale();  // 現在のロケールを取得

  // 英語の場合、テンプレート名に '-en' を追加
  if ($current_language == 'en_US') {
    // 現在のテンプレートファイル名から基本名を抽出（例：page.php から page を取得）
    $path_info = pathinfo($template);
    $file_base_name = $path_info['filename'];

    // '-en' を追加して新しいファイル名を構成
    $new_template_name = $file_base_name . '-en.php';
    $new_template_path = $path_info['dirname'] . '/' . $new_template_name;

    // 新しいテンプレートファイルが存在するか確認
    if (file_exists($new_template_path)) {
      return $new_template_path;  // 新しいテンプレートファイルを読み込む
    }
  }

  // 言語が英語以外の場合、または新しい英語テンプレートが存在しない場合は元のテンプレートを使用
  return $template;
}
