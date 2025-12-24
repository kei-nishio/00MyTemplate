<?php

/**
 * サイト設定（SNS、オンラインショップなどの外部リンク管理）
 * 
 * リンクが設定されていない場合は空文字を指定してください。
 * 例）href="<?php echo esc_url(get_link_or_hash(SNS_INSTAGRAM)); ?>"
 * 空文字の場合、get_link_or_hash()関数により自動的に # に変換されます。
 */

// SNS リンク
define('SNS_X', '');
define('SNS_INSTAGRAM', '');
define('SNS_FACEBOOK', '');

/**
 * リンクが空の場合は # を返す
 * 
 * @param string $url リンクURL
 * @return string URLまたは#
 */
function get_link_or_hash($url)
{
  return !empty($url) ? $url : '#';
}
