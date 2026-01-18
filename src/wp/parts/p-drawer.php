<?php // drawer 
?>
<?php
$nav_items = [
	[
		"nameEN" => "news",
		"nameJP" => "ニュース",
		"slug" => "news"
	],
	[
		"nameEN" => "プライバシーポリシー",
		"nameJP" => "privacy policy",
		"slug" => "privacy-policy"
	],
	[
		"nameEN" => "お問い合わせ",
		"nameJP" => "contact",
		"slug" => "contact"
	],
	[
		"nameEN" => "お問い合わせ",
		"nameJP" => "inquiry",
		"slug" => "inquiry"
	],
	[
		"nameEN" => "google",
		"nameJP" => "外部リンク",
		"slug" => "https://google.com/"
	]
];
?>
<div class="p-drawer" data-drawer>
	<div class="p-drawer__inner l-inner">
		<div class="p-drawer__nav">
			<ul class="p-drawer__list">
				<?php foreach ($nav_items as $item): ?>
					<li class="p-drawer__item">
						<a href="<?php echo (strpos($item['slug'], 'http') === 0) ? esc_url($item['slug']) : esc_url(home_url($item['slug'])); ?>" <?php echo (strpos($item['slug'], 'http') === 0) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?> class="link">
							<span class="en"><?php echo esc_html($item["nameEN"]); ?></span>
							<span class="jp"><?php echo esc_html($item["nameJP"]); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div>
<div class="p-drawer-mask" data-drawer-mask></div>