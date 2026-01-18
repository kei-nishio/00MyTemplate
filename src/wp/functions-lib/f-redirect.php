<?php
// ! 特定ページからのリダイレクト設定
/**
 * @uses 301：恒久的に別のURLの転送する場合に使用する
 * @uses 302：一時的に別のURLの転送する場合に使用する
 */
add_action('template_redirect', 'redirect_page');
function redirect_page()
{
	// * カスタム投稿タイプのアーカイブページのリダイレクト
	if (is_post_type_archive('case-study') && !is_front_page()) {
		wp_redirect(home_url('/tax-case-study-place/bath-room/'), 302);
		exit;
	}

	// * 個別記事なし投稿の場合リダイレクトする
	if (is_singular(array('campaign', 'voice'))) {
		wp_redirect(home_url('/'), 302);
		exit;
	}
}
