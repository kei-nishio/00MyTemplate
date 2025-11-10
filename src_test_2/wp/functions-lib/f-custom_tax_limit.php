<?php
// ! カスタムタクソノミーのターム数を制限する
function limit_taxonomy_term_creation($term, $taxonomy)
{
  $target_taxonomy = 'news-info'; // タクソノミー名
  $max_terms = 2; // タームの最大数

  if ($taxonomy === $target_taxonomy) {
    // すでに登録されているタームの数を取得
    $existing_terms = get_terms([
      'taxonomy' => $target_taxonomy,
      'hide_empty' => false,
    ]);

    // ターム数が上限を超えた場合、エラーメッセージを返す
    if (count($existing_terms) >= $max_terms) {
      return new WP_Error('term_limit_exceeded', 'このカテゴリーにはこれ以上、追加できません。（最大' . $max_terms . 'つまで）');
    }
  }
  return $term;
}
add_filter('pre_insert_term', 'limit_taxonomy_term_creation', 10, 2);
