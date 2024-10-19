<?php
$domain = parse_url(home_url(), PHP_URL_HOST);
$domain_parts = explode('.', $domain);
if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], $domain_parts) === false) {
  wp_redirect(home_url('/'));
  exit();
};
?>
<?php get_header(); ?>
<main class="main">
  <h1>thank you</h1>
</main>
<?php get_footer(); ?>