<?php
// ! Contact Form 7で郵便番号のバリデーションを追加する
function custom_zip_code_validation_filter($result, $tag)
{
  $tag = new WPCF7_FormTag($tag);

  if ('your-zip-code' == $tag->name) {
    $value = isset($_POST[$tag->name]) ? trim($_POST[$tag->name]) : '';

    // 日本の郵便番号形式（7桁の数字または3-4桁の形式）を検証
    if (!preg_match('/^\d{3}-?\d{4}$/', $value)) {
      $result->invalidate($tag, "正しい郵便番号を入力してください（例：123-4567）");
    }
  }

  return $result;
}
add_filter('wpcf7_validate_text*', 'custom_zip_code_validation_filter', 20, 2);
add_filter('wpcf7_validate_text', 'custom_zip_code_validation_filter', 20, 2);


// ! Contact Form 7でフリガナのバリデーションを追加する
function custom_furigana_validation($result, $tag)
{
  $name = $tag['name'];
  if ($name == 'your-kana') {
    $value = isset($_POST[$name]) ? $_POST[$name] : '';
    if (!preg_match('/^[ァ-ンヴー]+$/u', $value)) {
      $result->invalidate($tag, 'フリガナはカタカナで入力してください。');
    }
  }
  return $result;
}
add_filter('wpcf7_validate_text*', 'custom_furigana_validation', 20, 2);
add_filter('wpcf7_validate_text', 'custom_furigana_validation', 20, 2);


// ! Contact Form 7でふりがなのバリデーションを追加する
function custom_furigana_validation($result, $tag)
{
  $name = $tag['name'];
  if ($name == 'your-kana') {
    $value = isset($_POST[$name]) ? $_POST[$name] : '';
    if (!preg_match('/^[ぁ-んー]+$/u', $value)) {
      $result->invalidate($tag, 'ふりがなはひらがなで入力してください。');
    }
  }

  return $result;
}
add_filter('wpcf7_validate_text*', 'custom_furigana_validation', 20, 2);
add_filter('wpcf7_validate_text', 'custom_furigana_validation', 20, 2);
