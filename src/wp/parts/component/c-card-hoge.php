<!-- p-card-hoge.php -->
<?php
$post_id = get_the_ID(); // 投稿の ID を指定
$thumbnail_id = get_post_thumbnail_id($post_id); // アイキャッチ画像の ID を取得
$alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true); // アイキャッチ画像の alt 属性を取得
?>
<a href="<?php the_permalink(); ?>" class="card-hoge">
  <!-- 画像 -->
  <figure class="card-hoge__image">
    <?php if (has_post_thumbnail()) : ?>
      <img src="<?php the_post_thumbnail_url("full"); ?>" alt="<?php echo esc_attr($alt); ?>">
      <img src="<?php the_post_thumbnail_url("full"); ?>" alt="アイキャッチ画像">
    <?php else : ?>
      <img src="<?php echo esc_url(get_theme_file_uri("/assets/images/common/noimage.jpg")); ?>" alt="ノーイメージ画像" />
    <?php endif; ?>
  </figure>
  <!-- タイトル -->
  <h2 class="card-hoge__title"><?php echo esc_html(mb_strimwidth(get_the_title(), 0, 18 * 2 + 3, '...')); ?></h2>
  <!-- 日時 -->
  <time class="card-hoge__time" datetime="<?php the_time("c"); ?>">
    <?php the_time("Y.m.d"); ?>
  </time>
  <!-- ターム -->
  <div class="card-hoge__terms">
    <?php
    $taxonomy = 'YourTaxonomyName';
    $terms = get_the_terms($post->ID, $taxonomy);
    ?>
    <?php if (!empty($terms)) : ?>
      <div class="terms-boke">
        <?php foreach ($terms as $term) : ?>
          <?php echo '<span class="terms-boke__term">' . esc_html($term->name) . '</span>'; ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
  <!-- 本文要約 -->
  <p class="card-hoge__excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
  <!-- 本文冒頭 -->
  <p class="card-blog__content"><?php echo esc_html(mb_strimwidth(strip_tags(get_the_content()), 0, 100 * 2, '')); ?></p>
</a>