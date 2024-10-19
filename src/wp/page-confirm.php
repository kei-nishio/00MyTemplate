<?php
$domain = parse_url(home_url(), PHP_URL_HOST);
if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], $domain) === false) {
  wp_redirect(home_url());
  exit();
};
?>

<?php get_header(); ?>
<main class="main">
  <div class="l-lower-heading">
    <?php get_template_part('/parts/p-lower-heading', "", ["subtitle" => "contact", "title" => "お問い合わせ（確認）"]); ?>
  </div>

  <?php // get_template_part('/parts/c-breadcrumb'); 
  ?>

  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
      <div class="l-lower-top p-sub-contact">
        <div class="p-sub-contact__inner l-inner">
          <div class="p-sub-contact__form">
            <?php if (true): ?>
              <?php echo do_shortcode('[contact-form-7 id="da7f2a3" title="確認画面"]'); ?>
            <?php else: ?>
              <p style="text-align: center;">ここにショートコードが入ります。</p>
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