<?php

/**
 * @link https://okimono.life/wordpress/%E7%84%A1%E6%96%99%E3%81%A7%E4%BD%BF%E3%81%88%E3%82%8Bwordpress%E5%A4%9A%E8%A8%80%E8%AA%9E%E5%8C%96%E3%83%97%E3%83%A9%E3%82%B0%E3%82%A4%E3%83%B3bogo%E3%80%90%E5%9F%BA%E6%9C%AC%E3%81%8A%E8%A8%AD/
 * @uses 国旗表示の場合はf-bogo-noflagを使う
 */
?>

<!-- // ! bogo 変換前 -->
<?php echo do_shortcode('[bogo]'); ?>

<!-- // ! bogo 変換後（国旗表示） -->
<ul class="bogo-language-switcher list-view">
  <li class="en-US en first">
    <span class="bogoflags bogoflags-us"></span>
    <span class="bogo-language-name">
      <a rel="alternate" hreflang="en-US" href="http://uta202404.local/en/" title="英語">English</a>
    </span>
  </li>
  <li class="ja current last">
    <span class="bogoflags bogoflags-jp"></span>
    <span class="bogo-language-name">
      <a rel="alternate" hreflang="ja" href="http://uta202404.local/" title="日本語" class="current" aria-current="page">日本語</a>
    </span>
  </li>
</ul>

<!-- // ! bogo 変換後（国旗非表示）f-bogo-noflag -->
<ul class="bogo-language-switcher list-view">
  <li class="en-US en first">
    <span class="bogo-language-name">
      <a rel="alternate" hreflang="en-US" href="//localhost:3000/en/" title="英語">English</a>
    </span>
  </li>
  <li class="ja current last">
    <span class="bogo-language-name"><a rel="alternate" hreflang="ja" href="//localhost:3000/" title="日本語" class="current" aria-current="page">日本語</a>
    </span>
  </li>
</ul>

<!-- // ! bogo 変換後（国旗非表示かつ表示名変更）f-bogo-la-notation -->
<ul class="bogo-language-switcher list-view">
  <li class="en-US en first">
    <span class="bogo-language-name">
      <a rel="alternate" hreflang="en-US" href="//localhost:3000/en/" title="EN">EN</a>
    </span>
  </li>
  <li class="ja current last">
    <span class="bogo-language-name">
      <a rel="alternate" hreflang="ja" href="//localhost:3000/" title="JP" class="current" aria-current="page">JP</a>
    </span>
  </li>
</ul>