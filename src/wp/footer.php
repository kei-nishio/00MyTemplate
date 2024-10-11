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

<?php if (is_page("contact")) : ?>
  <script>
    // ** 郵便番号から住所を取得
    // ***********
    const zipCodeField = document.querySelector('input[name="your-zip-code"]');
    const addressField = document.querySelector('input[name="your-address"]');
    zipCodeField.addEventListener('blur', function() {
      const zipCode = zipCodeField.value.replace('-', '');
      if (zipCode.length === 7) {
        fetch(`https://api.zipaddress.net/?zipcode=${zipCode}`)
          .then((response) => response.json())
          .then((data) => {
            if (data.code === 200) {
              addressField.value = data.data.fullAddress;
            } else {
              alert('郵便番号が正しいかご確認ください。');
            }
          });
        // .catch((error) => {
        //   console.error('Error:', error);
        //   alert('住所の取得に失敗しました。');
        // });
      }
    });
  </script>
<?php endif; ?>
</body>

</html>