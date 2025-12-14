<?php
// ! Contact Form 7でセレクトボックスの値で送信先を変更する（お問い合わせページ用）

/**
 * お問い合わせページ
 * Contact Form 7 の「お問い合わせ種別」セレクトの選択肢に応じて送信先を変更
 * @uses 
 * 以下をCF7のその他設定に記述することで動作
 * route_セレクト①: mail1@local.com, mail2@local.jp
 * route_セレクト②: mail3@local.co.jp
 */

add_action('wpcf7_before_send_mail', 'custom_cf7_route_by_additional_settings');

function custom_cf7_route_by_additional_settings($contact_form)
{
  // 1. 送信データを取得
  $submission = WPCF7_Submission::get_instance();
  if (! $submission) {
    return;
  }

  $posted_data = $submission->get_posted_data();

  // =========================================================
  // 設定：ここでユニークな名前を指定
  // =========================================================
  $select_field_name = 'your-select';

  // この項目が存在しないフォームなら、処理を終了（＝他のフォームには影響しない）
  if (empty($posted_data[$select_field_name])) {
    return;
  }

  // =========================================================
  // 以降は変更なし
  // =========================================================

  $user_selection = $posted_data[$select_field_name];

  if (is_array($user_selection)) {
    $user_selection = implode(', ', $user_selection);
  }

  // 「その他の設定」を取得（改行コード対応版）
  $additional_settings = $contact_form->prop('additional_settings');
  $lines = preg_split('/[\r\n]+/', $additional_settings);

  $target_email = '';

  foreach ($lines as $line) {
    if (empty(trim($line))) continue;

    if (strpos(trim($line), 'route_') === 0) {
      $parts = explode(':', $line, 2);
      if (count($parts) == 2) {
        $setting_key = trim(str_replace('route_', '', $parts[0]));
        $setting_email = trim($parts[1]);

        if ($setting_key === $user_selection) {
          $target_email = $setting_email;
          break;
        }
      }
    }
  }

  if (! empty($target_email)) {
    $mail_prop = $contact_form->prop('mail');
    $mail_prop['recipient'] = $target_email;
    $contact_form->set_properties(array('mail' => $mail_prop));
  }
}
