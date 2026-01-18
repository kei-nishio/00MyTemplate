<?php

// ! Bogoの言語スイッチャーの表記を変更
/**
 * @link https://okimono.life/wordpress/%E7%84%A1%E6%96%99%E3%81%A7%E4%BD%BF%E3%81%88%E3%82%8Bwordpress%E5%A4%9A%E8%A8%80%E8%AA%9E%E5%8C%96%E3%83%97%E3%83%A9%E3%82%B0%E3%82%A4%E3%83%B3bogo%E3%80%90%E5%9F%BA%E6%9C%AC%E3%81%8A%E8%A8%AD/
 */
add_filter('bogo_language_switcher_links', function ($links) {
	for ($i = 0; $i < count($links); $i++) {
		if ('ja' === $links[$i]['locale']) { //日本語の場合
			$links[$i]['title'] = 'JP'; //JPが変更後のテキスト
			$links[$i]['native_name'] = 'JP'; //JPが変更後のテキスト
		}
		if ('en_US' === $links[$i]['locale']) { //英語の場合
			$links[$i]['title'] = 'EN'; //ENが変更後のテキスト
			$links[$i]['native_name'] = 'EN'; //ENが変更後のテキスト
		}
		if ('zh_CN' === $links[$i]['locale']) { //简体中文の場合
			$links[$i]['title'] = '简体'; //简体が変更後のテキスト
			$links[$i]['native_name'] = '简体'; //简体が変更後のテキスト
		}
		if ('zh_HK' === $links[$i]['locale']) { //香港の場合
			$links[$i]['title'] = '繁体'; //繁体が変更後のテキスト
			$links[$i]['native_name'] = '繁体'; //繁体が変更後のテキスト
		}
	}
	return $links;
});

// ! Bogoの言語スイッチャーの国旗を非表示
/**
 * @link https://okimono.life/wordpress/%E7%84%A1%E6%96%99%E3%81%A7%E4%BD%BF%E3%81%88%E3%82%8Bwordpress%E5%A4%9A%E8%A8%80%E8%AA%9E%E5%8C%96%E3%83%97%E3%83%A9%E3%82%B0%E3%82%A4%E3%83%B3bogo%E3%80%90%E5%9F%BA%E6%9C%AC%E3%81%8A%E8%A8%AD/
 */
add_filter('bogo_use_flags', 'bogo_use_flags_false');
function bogo_use_flags_false()
{
	return false;
}
