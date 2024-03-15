<!-- p-card-hoge.php -->

<a href="<?php the_permalink(); ?>" class="card-hoge<?php echo esc_attr((!empty($args['card_mdf']) ? " " . $args['card_mdf'] : '')) ?>">
  <h2 class="card-hoge__title">
    <?php the_title(); ?>
  </h2>
  <time class="card-hoge__time" datetime="<?php the_time("c"); ?>">
    <?php the_time("Y.m.d"); ?>
  </time>
  <!-- ! term loop start -->
  <?php
  $taxonomy = 'YourTermName';
  $terms = get_the_terms($post->ID, $taxonomy);
  if (!empty($terms)) {
    foreach ($terms as $term) {
      echo '<span class="YourClassName">' . esc_html($term->name) . '</span>';
    }
  }
  ?>
  <!-- ! term loop end -->
  <p class="card-hoge__text"><?php echo esc_html(get_the_excerpt()); ?></p>
  <p class="card-hoge__image">
    <?php if (has_post_thumbnail()) : ?>
      <img src="<?php the_post_thumbnail_url("full"); ?>" alt="アイキャッチ画像">
    <?php else : ?>
      <img src="<?php echo esc_url(get_theme_file_uri("/assets/images/common/noimage.jpg")); ?>" alt="ノーイメージ画像" />
    <?php endif; ?>
  </p>
</a>