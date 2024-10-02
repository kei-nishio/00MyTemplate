<?php
// ! reCAPTCHAを問い合わせだけに表示させる
add_action('wp_enqueue_scripts', function () {
  if (is_page(array('contact', 'confirm', 'thanks'))) return;
  wp_deregister_script('google-recaptcha');
}, 100, 0);
