<?php
$nav_items = [
  [
    "name" => "news",
    "slug" => home_url("/news")
  ],
  [
    "name" => "terms",
    "slug" => home_url("/terms")
  ],
  [
    "name" => "privacy policy",
    "slug" => home_url("/privacy-policy")
  ],
  [
    "name" => "contact",
    "slug" => home_url("/contact")
  ],
  [
    "name" => "google",
    "slug" => "https://google.com/"
  ]
];
?>
<?php // footer
?>
<footer class="l-footer p-footer">
  <div class="p-footer__inner l-inner">
    <?php // logo 
    ?>
    <div class="p-footer__logo">
      <a href="<?php echo esc_url(home_url('/')); ?>">
        <img src="<?php echo esc_url($theme_uri . '/assets/images/common/logo.svg'); ?>" alt="<?php echo esc_html(get_bloginfo('name')); ?>" />
      </a>
    </div>
    <?php // sns 
    ?>
    <div class="p-footer__sns">
      <ul class="p-footer__sns-list">
        <li class="p-footer__sns-item">
          <a href="" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url($theme_uri . '/assets/images/common/sns-line.png'); ?>" alt="line" loading="lazy"></a>
        </li>
        <li class="p-footer__sns-item">
          <a href="" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url($theme_uri . '/assets/images/common/sns-instagram.svg'); ?>" alt="instagram" loading="lazy"></a>
        </li>
        <li class="p-footer__sns-item">
          <a href="" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url($theme_uri . '/assets/images/common/sns-x.svg'); ?>" alt="x" loading="lazy"></a>
        </li>
        <li class="p-footer__sns-item">
          <a href="" target="_blank" rel="noopener noreferrer"><img src="<?php echo esc_url($theme_uri . '/assets/images/common/sns-facebook.svg'); ?>" alt="facebook" loading="lazy"></a>
        </li>
      </ul>
    </div>
    <?php // navigation 
    ?>
    <div class="p-footer__nav">
      <ul class="p-footer__list">
        <?php foreach ($nav_items as $nav_item): ?>
          <li class="p-footer__item">
            <a href="<?php echo esc_url($nav_item["slug"]); ?>" <?php echo (strpos($nav_item['slug'], home_url()) === 0) ? '' : 'target="_blank" rel="noopener noreferrer"'; ?>>
              <span><?php echo esc_html($nav_item["name"]); ?></span>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <p class="p-footer__copy">&copy; yoshifuji web atelier.</p>
  </div>
</footer>
<div style="height: 50vh; background-color: red; margin-top: 50vh;"></div>
<?php wp_footer(); ?>
</body>

</html>