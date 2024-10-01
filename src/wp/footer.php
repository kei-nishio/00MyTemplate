<footer class="footer
 <?php
  if (is_page(array("page1", "page2")) || is_404()) {
    echo "l-footer";
  } ?>" id="footer">
  <div class="footer__inner">
    <!-- ここにfooterを記述する -->
  </div>
</footer>
<?php wp_footer(); ?>

</body>

</html>