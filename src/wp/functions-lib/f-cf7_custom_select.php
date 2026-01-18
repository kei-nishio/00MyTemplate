<?php
// ! Contact Form 7でセレクトボックスをカスタマイズする
/**
 * @link https://kaminarimagazine.com/web/2019/09/16/contact-form-7%E3%81%A7select%E3%83%9C%E3%83%83%E3%82%AF%E3%82%B9%E3%81%AE%E4%B8%AD%E8%BA%AB%E3%82%92%E5%88%A5%E3%81%AE%E8%A6%81%E7%B4%A0%E3%81%8B%E3%82%89%E8%87%AA%E5%8B%95%E3%81%A7%E8%A8%AD%E5%AE%9A/
 * @link https://minkapi.style/web/post-355/
 * 
 * wpcf7_form_tag_data_option
 * 目的: wpcf7_form_tag_data_option フックは、フォームタグが出力するオプションデータをフィルタリングするために使用されます。具体的には、フォームタグに関連するデータオプション（例えば、ドロップダウンリストの選択肢など）を動的に変更する場合に利用されます。
 * 使用例: このフックを使用して、フォームに含まれる選択肢をユーザーの以前の選択や外部データベースの値に基づいて動的に変更することができます。
 * wpcf7_form_tag
 * 目的: wpcf7_form_tag フックは、フォームタグを生成する際に使用されます。このフックを使うことで、フォームタグのマークアップ自体をカスタマイズしたり、新しいカスタムフォームタグを追加したりすることができます。
 * 使用例: このフックを使用して、カスタムバリデーションメッセージを追加したり、特定のフォームタグに追加のHTML属性を挿入したりすることが可能です。
 * */

// ! 「未定義の値がこの項目を通じて送信されました。」回避用コード
//チェックボックス用
remove_action('wpcf7_swv_create_schema', 'wpcf7_swv_add_checkbox_enum_rules', 20, 2);
//セレクトメニュー用
remove_action('wpcf7_swv_create_schema', 'wpcf7_swv_add_select_enum_rules', 20, 2);

// ! page-contact.php
// * カスタム投稿タイプの値を取得してクエリIDによる自動チェックをする場合
// * CF7に [custom_builder your-checkbox0 id:f-checkbox0] を追記すること
function custom_add_form_tag_builder()
{
	wpcf7_add_form_tag('custom_builder', 'custom_builder_form_tag_handler', ['name-attr' => true]);
}
function custom_builder_form_tag_handler($tag)
{
	$name = $tag->name; // CF7 の name 属性を取得
	$id = $tag->get_option('id', 'id', true); // ID を取得（修正）

	$author_ids = isset($_GET['author_id']) ? array_map('intval', explode(',', sanitize_text_field($_GET['author_id']))) : [];

	// `builder` カスタム投稿の一覧を取得
	$query_args = [
		'posts_per_page' => -1,
		'post_type'      => 'builder',
		'order'          => 'DESC',
	];
	$the_query = new WP_Query($query_args);

	$id_attr = !empty($id) ? ' id="' . esc_attr($id) . '"' : ''; // ID 属性を適用
	$html = '<span class="wpcf7-form-control-wrap" data-name="' . esc_attr($name) . '">';
	$html .= '<span class="wpcf7-form-control wpcf7-checkbox"' . $id_attr . '>'; // IDを適用

	if ($the_query->have_posts()) {
		$first = true;
		while ($the_query->have_posts()) {
			$the_query->the_post();
			$post_title = esc_attr(get_the_title());
			$author_id = get_the_author_meta('ID');

			// `checked` を適用するか判定
			$checked = in_array($author_id, $author_ids) ? ' checked' : '';

			// CF7 のチェックボックス構造に準拠
			$html .= '<span class="wpcf7-list-item ' . ($first ? 'first' : 'last') . '">';
			$html .= '<label>';
			$html .= '<input type="checkbox" name="' . esc_attr($name) . '[]" value="' . $post_title . '"' . $checked . '>';
			$html .= '<span class="wpcf7-list-item-label">' . $post_title . '</span>';
			$html .= '</label>';
			$html .= '</span>';
			$first = false;
		}
		wp_reset_postdata();
	}

	$html .= '</span></span>';

	return $html;
}
add_action('wpcf7_init', 'custom_add_form_tag_builder');

// * カスタムタクソノミーの値を取得する場合
function custom_select_values_contact($values, $options, $args)
{
	if (!is_array($values)) {
		$values = [];
	}
	if (in_array('custom-checkbox-location', $options)) {
		$terms = get_terms([
			'taxonomy'   => 'renovation-area',
			'hide_empty' => false,
		]);

		if (!is_wp_error($terms) && !empty($terms)) {
			foreach ($terms as $term) {
				$values[] = $term->name;
			}
		}
	}
	return $values;
}
add_filter('wpcf7_form_tag_data_option', 'custom_select_values_contact', 10, 3);

// ! page-form-event.php
function custom_select_values_form_event($values, $options, $args)
{
	if (!is_array($values)) {
		$values = [];
	}
	// * クエリに event_id が存在する場合に一意のイベントを設定
	if (in_array('custom-select-event', $options)) {
		$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : null;

		if ($event_id) {
			$event = get_post($event_id);
			if ($event && $event->post_type === 'event') {
				$values[] = esc_attr($event->post_title);
			}
		}
	}
	// * クエリに event_id が存在する場合にカスタムフィールドを取得する
	if (in_array('custom-select-date', $options)) {
		$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : null;

		if ($event_id) {
			$reserve_times = function_exists('get_field')
				? get_field('acfEventReserveTime', $event_id)
				: get_post_meta($event_id, 'acfEventReserveTime', true);

			// テキストエリアの改行を考慮して分割
			if (!empty($reserve_times) && is_string($reserve_times)) {
				$times_array = preg_split("/\r\n|\n|\r/", $reserve_times);
				foreach ($times_array as $time) {
					if (!empty(trim($time))) {
						$values[] = esc_attr(trim($time));
					}
				}
			}
		}
	}

	return $values;
}
add_filter('wpcf7_form_tag_data_option', 'custom_select_values_form_event', 10, 3);

// ! Contact Form 7でセレクトボックスをクエリ値に基づきカスタマイズする
function custom_select_values($values, $options, $args)
{
	// フォームオプションに 'hoge-select' が含まれている場合のみ実行
	if (in_array('hoge-select', $options)) {
		$default_company = 'デフォルト'; // デフォルト名を設定
		$author_id = isset($_GET['author-id']) ? intval($_GET['author-id']) : null;
		$values = []; // 選択肢を初期化

		// author-id が存在し、該当するユーザーが author ロールか確認
		if ($author_id) {
			$author = get_user_by('ID', $author_id);
			if ($author && user_can($author, 'author')) {
				$values[] = esc_attr($author->last_name); // 最初に author の last_name を設定
			}
		}
		$values[] = $default_company; // デフォルト名を追加（常に2番目または最初）

		// 残りの authors を first_name でソートして取得し、選択肢に追加
		$authors = get_users([
			'role' => 'author',
			'orderby' => 'meta_value',
			'meta_key' => 'first_name',
			'order' => 'ASC',
			'exclude' => $author_id ? [$author_id] : [] // author-id を除外
		]);

		foreach ($authors as $author) {
			$values[] = esc_attr($author->last_name);
		}
	}

	return $values;
}
add_filter('wpcf7_form_tag_data_option', 'custom_select_values', 10, 3);
