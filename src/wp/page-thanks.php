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
	<h1>thank you</h1>
</main>
<?php get_footer(); ?>