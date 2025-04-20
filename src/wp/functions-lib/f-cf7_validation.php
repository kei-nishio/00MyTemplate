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

// ! Contact Form 7でメールアドレスの確認バリデーションを追加する
function wpcf7_custom_email_validation_filter($result, $tag)
{
  $tag_name = strtolower($tag->name);

  if ('your-email-confirm' === $tag_name) {
    $your_email = isset($_POST['your-email']) ? sanitize_email(trim($_POST['your-email'])) : '';
    $your_email_confirm = isset($_POST['your-email-confirm']) ? sanitize_email(trim($_POST['your-email-confirm'])) : '';

    if ($your_email !== $your_email_confirm) {
      $result->invalidate($tag, __('メールアドレスが一致しません', 'your-text-domain'));
    }
  }

  return $result;
}
add_filter('wpcf7_validate_email', 'wpcf7_custom_email_validation_filter', 20, 2);
add_filter('wpcf7_validate_email*', 'wpcf7_custom_email_validation_filter', 20, 2);
