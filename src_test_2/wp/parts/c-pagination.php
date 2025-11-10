<?php
global $wp_query;

$total   = $wp_query->max_num_pages;
$current = max(1, get_query_var('paged'));

echo '<div class="c-pagination">';

// ＜＜ (最初へ)
if ($current > 1) {
  echo '<a class="first-page page-numbers" href="' . esc_url(get_pagenum_link(1)) . '">＜＜</a>';
}

echo paginate_links([
  'mid_size'  => wp_is_mobile() ? 1 : 2,
  'end_size'  => 1,
  'prev_text' => '＜',
  'next_text' => '＞',
  'current'   => $current,
  'total'     => $total,
]);

// ＞＞ (最後へ)
if ($current < $total) {
  echo '<a class="last-page page-numbers" href="' . esc_url(get_pagenum_link($total)) . '">＞＞</a>';
}

echo '</div>';
