<?php
$nav_items = [
  [
    "name" => "news",
    "slug" => home_url("/news")
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
<footer class="footer" id="footer">
  <div class="footer__inner">
    <!-- ここにfooterを記述する -->
  </div>
</footer>
<div style="height: 50vh; background-color: red; margin-top: 50vh;"></div>
<?php wp_footer(); ?>
</body>

</html>