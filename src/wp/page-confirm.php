<?php
// リファラが無効な場合はホームページにリダイレクト
$domain = parse_url(home_url(), PHP_URL_HOST);
$domain_parts = explode('.', $domain);
$referrer_host = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) : null;
// ドメイン部分がリファラに含まれているかどうかをチェック
$referrer_valid = false;
if ($referrer_host) {
  foreach ($domain_parts as $part) {
    if (strpos($referrer_host, $part) !== false) {
      $referrer_valid = true;
      break;
    }
  }
}
// リファラが無効な場合はホームページにリダイレクト
if (!$referrer_valid) {
  wp_redirect(home_url('/'));
  exit();
}
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
              <?php echo do_shortcode('[contact-form-7 id="da7f2a3" title="confirm"]'); ?>
            <?php else: ?>
              <p style="text-align: center; margin-top: 20px; ">ここにショートコードが入ります。</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else : ?>
    <p style="text-align: center; margin-top: 20px; ">記事が投稿されていません。</p>
  <?php endif; ?>
</main>

<?php get_footer(); ?>