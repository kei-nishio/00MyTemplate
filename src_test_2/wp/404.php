<?php get_header(); ?>
<main class="main">
  <div class="l-lower-heading">
    <?php get_template_part('/parts/p-lower-heading', "", ["subtitle" => "404", "title" => "Not Found"]); ?>
  </div>

  <?php // get_template_part('/parts/c-breadcrumb'); 
  ?>
  <div class="l-lower-top p-error">
    <div class="p-error__inner l-inner">
      <h2 class="p-error__title">お探しのページは<br class="u-sp">見つかりませんでした。</h2>
      <p class="p-error__text"><span>本サイトをご覧いただきありがとうございます。</span><br><span>誠に申し訳ございませんが、お探しのページが見つかりませんでした。</span><br><span>ページが削除されたか、一時的にご利用いただけない可能性があります。</span><br><span>ページのURLが正しいかご確認ください。</span>
      </p>
      <div class="p-error__button">
        <div class="c-button-normal"><a href="<?php echo esc_url(home_url('/')); ?>">トップに戻る</a><span></span></div>
      </div>
    </div>
  </div>

</main>
<?php get_footer(); ?>