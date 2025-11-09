<?php
// ! reCAPTCHAを問い合わせだけに表示させる
add_action('wp_enqueue_scripts', function () {
  if (is_page(array('contact', 'confirm', 'thanks'))) return;
  wp_deregister_script('google-recaptcha');
}, 100, 0);

// ! reCAPTCHA バッジのz-indexを調整（サイドヘッダーがある場合の調整）
// function adjust_recaptcha_zindex()
// {
//   if (is_page(array('contact', 'thanks'))) {
//     echo '<style>
//       .grecaptcha-badge { 
//         z-index: 9999 !important; 
//       }
//     </style>';
//   }
// }
// add_action('wp_head', 'adjust_recaptcha_zindex');
