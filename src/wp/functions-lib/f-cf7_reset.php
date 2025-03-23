<?php
// ! Contact Form 7で自動挿入されるPタグ、brタグを削除
function wpcf7_autop_return_false()
{
  return false;
}
add_filter('wpcf7_autop_or_not', 'wpcf7_autop_return_false');
