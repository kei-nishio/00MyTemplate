<?php if (function_exists('bcn_display')) { ?>
  <div class="c-breadcrumb" vocab="http://schema.org/" typeof="BreadcrumbList">
    <div class="breadcrumb__container">
      <?php if (function_exists('bcn_display'))  bcn_display(); ?>
    </div>
  </div>
<?php } ?>