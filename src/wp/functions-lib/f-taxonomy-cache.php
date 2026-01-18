<?php
// ! タクソノミーのキャッシュを保持する（タクソノミー保存時等に更新する）
/**
 * @uses get_terms($taxonomy) を使用した時に自動的にキャッシュを利用します。
 * */
function get_terms_with_cache($terms, $taxonomies, $args)
{
	if (is_admin() || empty($taxonomies[0])) {
		return $terms; // 管理画面ではキャッシュを適用しない
	}

	$cache_key = 'terms_cache_' . $taxonomies[0];

	// キャッシュが存在する場合はキャッシュを返す
	if (($cached_terms = get_transient($cache_key)) !== false) {
		return $cached_terms;
	}

	// キャッシュがない場合は通常の get_terms() を実行し、キャッシュに保存
	$terms = get_terms($args);
	set_transient($cache_key, $terms, 30 * DAY_IN_SECONDS); // 30日間キャッシュ

	return $terms;
}

// フィルターを適用し、get_terms() を自動的にキャッシュ化
add_filter('get_terms', 'get_terms_with_cache', 10, 3);

// ! タクソノミーが変更されたときにキャッシュをクリア
function clear_terms_cache($term_id, $taxonomy)
{
	delete_transient('terms_cache_' . $taxonomy);
}

// タクソノミーの変更（編集・作成・削除）時にキャッシュをクリア
add_action('edited_terms', 'clear_terms_cache', 10, 2);
add_action('create_term', 'clear_terms_cache', 10, 2);
add_action('delete_term', 'clear_terms_cache', 10, 2);
