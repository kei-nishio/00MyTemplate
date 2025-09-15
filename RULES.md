# Copilot 用プロンプト（Figma 画像 → 静的サイト 最小規約）

## 目的

- 画像を忠実再現。テキスト/コードは漏れなく出力。ヘッダー/フッターは指示時のみ。

## 出力物/構成

- HTML / CSS / JS（必要時）

```text
project-root/
├─ index.html
└─ assets/{css/style.css, js/script.js(任意), img/...}
```

## 規約

- HTML: セマンティック。TOP の h1=ロゴ。見出し階層厳守。
- CSS： リセットCSSを用いること（ assets\css\ress.min.css ）
- 命名: FLOCSS + BEM（prefix: l-/c-/p-/u-/js-、形: block\_\_element--modifier）
- レイアウト: SP ファースト → @media (min-width:768px)
- 単位: px 統一
- 変数: CSS カスタムプロパティで色/タイポ/余白
- 画像: 親で比率管理、img は width:100%; height:auto; object-fit:cover

## 構造パターン

```html
<section class="p-section">
  <div class="p-section__inner l-inner">
    <div class="p-section__heading">
      <hgroup class="c-section-title">
        <h2 class="c-section-title__main">タイトル</h2>
        <p class="c-section-title__sub">サブ</p>
      </hgroup>
    </div>
    <div class="p-section__content"><!-- コンテンツ --></div>
  </div>
</section>
```

## 出力フォーマット

### index.html

```html
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><!-- ページタイトル --></title>
    <link rel="stylesheet" href="assets/css/style.css" />
  </head>
  <body>
    <main id="main" class="l-main">
      <!-- 構造パターンに沿って各セクションを実装 -->
    </main>
    <!-- JSが不要なら下行を削除 -->
    <script src="assets/js/script.js" defer></script>
  </body>
</html>
```

### assets/css/style.css

```css
:root {
  --inner-width: 1080px;
  --pad-sp: 15px;
  --pad-pc: 25px;
  --ff: 'Zen Kaku Gothic New', Arial, sans-serif;
  --c-text: #333;
  --c-muted: #666;
  --c-accent: #0066cc;
}
html {
  font-size: 100%;
}
body {
  margin: 0;
  font-family: var(--ff);
  color: var(--c-text);
  line-height: 1.6;
}
img {
  display: block;
  width: 100%;
  height: auto;
  object-fit: cover;
}

/* Layout */
.l-inner {
  width: 100%;
  max-width: 600px;
  margin-inline: auto;
  padding-inline: var(--pad-sp);
}
@media (min-width: 768px) {
  .l-inner {
    max-width: calc(var(--inner-width)+var(--pad-pc) * 2);
    padding-inline: var(--pad-pc);
  }
}
.l-section {
  margin-top: 60px;
}
@media (min-width: 768px) {
  .l-section {
    margin-top: 100px;
  }
}

/* Component（例） */
.c-section-title {
  text-align: center;
}
.c-section-title__main {
  margin: 0 0 8px;
  font-size: 28px;
  font-weight: 700;
}
@media (min-width: 768px) {
  .c-section-title__main {
    font-size: 40px;
  }
}
.c-section-title__sub {
  margin: 0;
  color: var(--c-muted);
}

.c-button {
  display: inline-block;
  border: 1px solid var(--c-text);
  border-radius: 20px;
  background: var(--c-text);
  transition: transform 0.2s;
}
.c-button a {
  display: inline-block;
  padding: 12px 32px;
  color: #fff;
  text-decoration: none;
  text-align: center;
}
.c-button:hover {
  transform: translate(1px, 1px);
}
.c-button--contact {
  background: var(--c-accent);
}

/* Page（例） */
.p-section__content {
  /* 必要に応じて grid/flex を定義*/
}
```

### assets/js/script.js

```javascript
document.addEventListener('DOMContentLoaded', () => {
  /* 必要な機能のみ実装 */
});

// 例：アコーディオン
class Accordion {
  constructor(el) {
    this.el = el;
    this.q = el.querySelector('.p-faq__q');
    this.a = el.querySelector('.p-faq__a');
    this.q && this.q.addEventListener('click', () => this.t());
  }
  t() {
    const open = this.el.classList.toggle('is-open');
    if (this.a) this.a.style.maxHeight = open ? this.a.scrollHeight + 'px' : '0';
  }
}
document.querySelectorAll('.js-accordion').forEach((el) => new Accordion(el));
```

## 画像配置パターン（親で比率管理）

```html
<figure class="p-section__figure u-ar-16x9">
  <img src="assets/img/hero.jpg" alt="ヒーロー画像" width="1600" height="900" />
</figure>
<!-- p-section__content の中に配置すること -->
```

## 画像ルール（最小）

- assets/img/ は .jpg/.jpeg 推奨（透過のみ .png）。WebP 併用可。
- CLS 抑制のため可能なら `<img>` に width/height を付与。
- 基本: `img{width:100%; height:auto; object-fit:cover}`

## ユーティリティ（アスペクト比） - assets/css/style.css の末尾に追加

```css
/* Aspect Ratio Utilities */
.u-ar-16x9 {
  aspect-ratio: 16/9;
  display: block;
  overflow: hidden;
}
.u-ar-4x3 {
  aspect-ratio: 4/3;
  display: block;
  overflow: hidden;
}
.u-ar-1x1 {
  aspect-ratio: 1/1;
  display: block;
  overflow: hidden;
}
/* 可変比率（任意）: <div class="u-ar" style="--ar:3/2"> */
.u-ar {
  aspect-ratio: var(--ar, 16/9);
  display: block;
  overflow: hidden;
}
```

## JS フック一覧（本文の「規約」節に 1 行追加）

- JS フック: `.js-accordion` / `.js-hamburger` / `.js-drawer` / `.js-header` / `.js-to-top`

## 最小 JS スタブ（必要時のみ） - assets/js/script.js の末尾に追加

```javascript
// Hamburger/Drawer (最小)
(() => {
  const b = document.querySelector('.js-hamburger'),
    d = document.querySelector('.js-drawer');
  if (!b || !d) return;
  b.addEventListener('click', () => {
    b.classList.toggle('is-active');
    d.classList.toggle('is-active');
  });
})();
// To-Top (最小)
(() => {
  const t = document.querySelector('.js-to-top');
  if (!t) return;
  addEventListener('scroll', () => t.classList.toggle('is-active', scrollY > 600));
  t.addEventListener('click', () => scrollTo({ top: 0, behavior: 'smooth' }));
})();
```

## 最終チェックリスト（貼り付け可・運用用）

- [ ] ディレクトリ: index.html / assets/{css/style.css, js/script.js(任), img/} は揃っている
- [ ] HTML: セマンティック・TOP の h1=ロゴ・見出し階層 OK
- [ ] 命名: FLOCSS+BEM（l-/c-/p-/u-/js-）に統一、構造パターン準拠
- [ ] CSS: px 統一・SP→@media(min-width:768px)・変数利用 OK
- [ ] 画像: 親で比率管理（u-ar-\*）・img は cover・width/height 付与
- [ ] JS: 必要時のみ・フック名は規約どおり・スタブ動作確認済み
