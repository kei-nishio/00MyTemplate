<?php
$slug = $args['slug'] ?? 'default';
?>
<?php get_header(); ?>
<main class="main">
  <div class="l-lower-heading">
    <?php if ($slug === "news"): ?>
      <?php get_template_part('/parts/p-lower-heading', "", ["subtitle" => "news", "title" => "ニュース一覧"]); ?>
    <?php else: ?>
      <?php get_template_part('/parts/p-lower-heading', "", ["subtitle" => "default", "title" => "デフォルト"]); ?>
    <?php endif; ?>
  </div>

  <?php // get_template_part('/parts/c-breadcrumb'); 
  ?>

  <?php if (false): ?>
    <?php // 1 column 
    ?>
    <div class="l-lower-top p-column1">
      <div class="p-column1__inner l-inner">
        <div class="p-column1__content">
          <div class="p-archive">
            <?php if (have_posts()) : ?>
              <ul class="p-archive__list">
                <?php while (have_posts()) : the_post(); ?>
                  <li class="p-archive__item">
                    <?php $post_id = get_the_ID(); ?>
                    <?php get_template_part('/parts/c-card', $slug, ["post_id" => $post_id]); ?>
                  </li>
                <?php endwhile; ?>
              </ul>
              <div class="p-archive__pagination">
                <?php get_template_part('/parts/c-pagination'); ?>
              </div>
            <?php else : ?>
              <p style="text-align: center;">記事が投稿されていません。</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  <?php else: ?>
    <?php // 2 column 
    ?>
    <div class="l-lower-top p-column2">
      <div class="p-column2__inner l-inner">
        <div class="p-column2__sidebar">
          <?php get_sidebar(); ?>
        </div>
        <div class="p-column2__main">
          <div class="p-column2__content">
            <div class="p-archive">
              <?php if (have_posts()) : ?>
                <ul class="p-archive__list">
                  <?php while (have_posts()) : the_post(); ?>
                    <li class="p-archive__item">
                      <?php $post_id = get_the_ID(); ?>
                      <?php get_template_part('/parts/c-card', $slug, ["post_id" => $post_id]); ?>
                    </li>
                  <?php endwhile; ?>
                </ul>
                <div class="p-archive__pagination">
                  <?php get_template_part('/parts/c-pagination'); ?>
                </div>
              <?php else : ?>
                <p style="text-align: center;">記事が投稿されていません。</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>

</main>
<?php get_footer(); ?>