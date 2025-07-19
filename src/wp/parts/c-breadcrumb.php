<div class="c-breadcrumb" vocab="http://schema.org/" typeof="BreadcrumbList">
  <div class="c-breadcrumb__inner l-inner">
    <div class="c-breadcrumb__container">
      <?php if (true): ?>
        <?php // All in One SEO を使う場合 
        ?>
        <?php echo do_shortcode('[aioseo_breadcrumbs]'); ?>
      <?php else: ?>
        <?php // Breadcrumb NavXT を使う場合 
        ?>
        <?php if (function_exists('bcn_display')) { ?>
          <?php if (function_exists('bcn_display'))  bcn_display(); ?>
        <?php } ?>
      <?php endif; ?>
    </div>
  </div>
</div>