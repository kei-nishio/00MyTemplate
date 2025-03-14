<?php
$args = [
  'mid_size' => wp_is_mobile() ? 1 : 2,
  'prev_text' => '',
  'next_text' => '',
];
the_posts_pagination($args);
