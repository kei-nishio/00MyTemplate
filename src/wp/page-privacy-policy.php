<?php get_header(); ?>
<main class="main">
  <?php get_template_part('/parts/p-lower-heading', "", ["subtitle" => "privacy policy", "title" => "プライバシーポリシー"]); ?>

  <?php // get_template_part('/parts/c-breadcrumb'); 
  ?>

  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
      <div class="l-lower-top p-privacy-policy">
        <div class="p-privacy-policy__inner l-inner">
          <div class="p-privacy-policy__content">
            <div class="p-content p-content--revert">
              <?php the_content(); ?>
            </div>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else : ?>
    <p style="text-align: center;">記事が投稿されていません。</p>
  <?php endif; ?>

</main>
<?php get_footer(); ?>