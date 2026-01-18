<?php
// ! Breadcrumb NavXT で特定ページのパンくずをカスタムする
/**
 * Breadcrumb NavXT の設定を変更する
 * @link https://pecopla.net/web-column/breadcrumbnavxt-custom
 * @link https://renkosaka.com/breadcrumb-navxt-customize/
 * 
 */
function my_custom_breadcrumbs($trail)
{
	if (is_page(array('confirm', 'thanks'))) {
		$trail->breadcrumbs = []; // パンくずをリセット

		// CONTACT パンくずアイテム（非リンク）
		$trail->add(new bcn_breadcrumb('CONTACT', null, array('singular'), esc_url(home_url('contact/')), null, false));

		// TOP パンくずアイテム（リンク）
		$trail->add(new bcn_breadcrumb('TOP', null, array('home'), esc_url(home_url('/')), null, true));
	}
}
add_action('bcn_after_fill', 'my_custom_breadcrumbs');
