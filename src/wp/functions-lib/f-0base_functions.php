<?php
// ! 「2024-99-99」を「99.99（曜日）」形式に変換する
function formatDateToJapanese($dateText)
{
	$date = new DateTime($dateText);
	$daysJapanese = [
		'Mon' => '月',
		'Tue' => '火',
		'Wed' => '水',
		'Thu' => '木',
		'Fri' => '金',
		'Sat' => '土',
		'Sun' => '日',
	];
	$dayOfWeek = $date->format('D');
	$formattedDate = $date->format('m.d') . '<span>（' . $daysJapanese[$dayOfWeek] . '）</span>';
	return $formattedDate;
	// 使用例：echo formatDateToJapanese('2024-10-18'); // 出力: 10.18（木）
}
