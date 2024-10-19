<?php get_header(); ?>
<main class="main">
  <?php get_template_part('/parts/p-lower-heading', "", ["subtitle" => "contact", "title" => "お問い合わせ（確認）"]); ?>

  <?php // get_template_part('/parts/c-breadcrumb'); 
  ?>

  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
      <div class="l-lower-top p-sub-contact">
        <div class="p-sub-contact__inner l-inner">
          <div class="p-sub-contact__form">
            <?php if (false): ?>
              <?php echo do_shortcode('[contact-form-7 id="3ee3ec1" title="入力画面（確認なし）"]'); ?>
            <?php else: ?>
              <p>ここにショートコードが入ります。</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else : ?>
    <p style="text-align: center;">記事が投稿されていません。</p>
  <?php endif; ?>
</main>

<?php get_footer(); ?>