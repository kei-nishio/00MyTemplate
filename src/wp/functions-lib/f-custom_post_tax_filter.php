<?php
// ! カスタム投稿の管理画面でタクソノミーフィルターを追加
function get_custom_filters()
{
  return [
    'custom-post-name-1' => ['custom-taxonomy-1'],
    'custom-post-name-2' => ['custom-taxonomy-2', 'custom-taxonomy-3'],
  ];
}

// * 管理画面の投稿一覧にタクソノミーのフィルタードロップダウンを追加する
function add_custom_taxonomy_filters()
{
  $screen = get_current_screen();
  if (!$screen || empty($screen->post_type)) {
    return;
  }

  $post_type = $screen->post_type;
  $custom_filters = get_custom_filters();

  // 指定されたカスタム投稿タイプのみ処理
  if (!isset($custom_filters[$post_type])) {
    return;
  }

  foreach ($custom_filters[$post_type] as $taxonomy) {
    // タクソノミーの存在確認
    if (!taxonomy_exists($taxonomy)) {
      continue;
    }

    $tax = get_taxonomy($taxonomy);
    if (!$tax) continue;

    $selected = isset($_GET[$taxonomy]) ? sanitize_text_field($_GET[$taxonomy]) : '';

    $terms = get_terms([
      'taxonomy'   => $taxonomy,
      'hide_empty' => false,
    ]);

    if (!$terms || is_wp_error($terms)) continue;

    echo '<select name="' . esc_attr($taxonomy) . '" class="postform">';
    echo '<option value="">' . esc_html($tax->labels->all_items ?? $tax->labels->name) . '</option>';
    foreach ($terms as $term) {
      printf(
        '<option value="%s" %s>%s</option>',
        esc_attr($term->slug),
        selected($selected, $term->slug, false),
        esc_html($term->name)
      );
    }
    echo '</select>';
  }
}
add_action('restrict_manage_posts', 'add_custom_taxonomy_filters');

// * 選択されたタクソノミーフィルターに基づいて、投稿一覧の表示を変更する
function filter_posts_by_custom_taxonomies($query)
{
  global $pagenow;

  // 管理画面の投稿一覧ページ以外は処理しない
  if ($pagenow !== 'edit.php' || !$query->is_main_query() || !is_admin()) {
    return;
  }

  // 投稿タイプを取得(クエリオブジェクトから優先的に取得)
  $post_type = $query->get('post_type');

  // クエリに投稿タイプが設定されていない場合はGETパラメータから取得
  if (empty($post_type)) {
    $post_type = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : 'post';
  }

  $custom_filters = get_custom_filters();

  // フィルター設定がない投稿タイプは処理しない
  if (!isset($custom_filters[$post_type])) {
    return;
  }

  $tax_query = [];

  foreach ($custom_filters[$post_type] as $taxonomy) {
    // タクソノミーが存在し、フィルター値が設定されている場合のみ処理
    if (taxonomy_exists($taxonomy) && !empty($_GET[$taxonomy])) {
      $tax_query[] = [
        'taxonomy' => $taxonomy,
        'field'    => 'slug',
        'terms'    => sanitize_text_field($_GET[$taxonomy]),
      ];
    }
  }

  if (!empty($tax_query)) {
    $query->set('tax_query', $tax_query);
  }
}
add_action('pre_get_posts', 'filter_posts_by_custom_taxonomies');
