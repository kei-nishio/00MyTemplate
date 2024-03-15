<!-- post-hoge.php -->

<?php
$args = array(
  'class' => 'news__list',
  'list' => 'list-news',
  'list_mdf' => null, // or 'list-news--hoge'
  'card' => 'card-hoge',
  'card_mdf' => 'card-hoge--hoge card-hoge--boke', // or null
);
?>
<?php get_template_part('parts/post/p-post-mainloop', null, $args); ?>