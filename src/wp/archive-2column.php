<?php get_header(); ?>
<main class="main">
  <div class="l-lower-heading">
    <?php get_template_part('/parts/p-lower-heading', "", ["subtitle" => "default", "title" => "デフォルト"]); ?>
  </div>

  <?php // get_template_part('/parts/c-breadcrumb'); 
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
                    <?php get_template_part('/parts/c-card', "", ["post_id" => $post_id]); ?>
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
</main>
<?php get_footer(); ?>