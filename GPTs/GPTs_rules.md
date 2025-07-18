# Web 制作コーディング規約

- 画像をもとに Web ページを再現してください。
- すべてのテキストとコードを漏れなく出力してください。
- ヘッダーとフッターは指示がない限り、コーディング不要です。


## 出力形式

- HTML（セマンティック）
- CSS（FLOCSS + BEM）
- JS（必要時のみ）

## HTML 規約

### 基本構造

- セマンティックタグ使用（`<section>`, `<main>`, `<nav>`等）
- TOP ページ h1 はロゴ、下位ページは div
- 見出しレベル適切に使用

### class 命名（FLOCSS + BEM）

- **Layout**: `l-inner`, `l-section`, `l-header`
- **Component**: `c-card`, `c-button-normal`, `c-section-title`
- **Project**: `p-header`, `p-fv`, `p-section-grid`
- **JavaScript**: `js-accordion`, `js-header`

### 構造パターン

```html
<section class="p-section-name">
  <div class="p-section-name__inner l-inner">
    <div class="p-section-name__heading">
      <hgroup class="c-section-title">
        <h2 class="c-section-title__main">タイトル</h2>
        <p class="c-section-title__sub">サブタイトル</p>
      </hgroup>
    </div>
    <div class="p-section-name__content">
      <!-- コンテンツ -->
    </div>
  </div>
</section>
```

## CSS 規約

### 設計指針

- FLOCSS + BEM 命名規則
- 単位は px 統一
- 1 ファイルで動作

### 基本設定

```css
:root {
  --inner-width: 1080px;
  --padding-sp: 15px;
  --padding-pc: 25px;
  --font-family-primary: 'Zen Kaku Gothic New', Arial, sans-serif;
  --color-primary: #333333;
  --color-secondary: #666666;
  --color-accent: #0066cc;
  --z-header: 5000;
}

html {
  font-size: 100%;
}
body {
  font-family: var(--font-family-primary);
  color: var(--color-primary);
  line-height: 1.6;
}
```

### Layout

```css
.l-inner {
  width: 100%;
  max-width: 600px;
  margin-inline: auto;
  padding-inline: var(--padding-sp);
}
@media (min-width: 768px) {
  .l-inner {
    max-width: calc(var(--inner-width) + var(--padding-pc) * 2);
    padding-inline: var(--padding-pc);
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
```

### Component

```css
.c-section-title {
  text-align: center;
}
.c-section-title__main {
  font-size: 28px;
  font-weight: 700;
}
@media (min-width: 768px) {
  .c-section-title__main {
    font-size: 40px;
  }
}

.c-button-normal {
  display: inline-block;
  border: 1px solid var(--color-primary);
  border-radius: 20px;
  background-color: var(--color-primary);
  transition: transform 0.2s ease;
}
.c-button-normal a {
  display: inline-block;
  padding: 12px 32px;
  color: white;
  text-align: center;
}
.c-button-normal:hover {
  transform: translate(1px, 1px);
}
.c-button-normal--white {
  background-color: white;
}
.c-button-normal--white a {
  color: var(--color-primary);
}
```

## JavaScript 規約

### 基本方針

- Vanilla JavaScript 使用
- クラスベース設計
- DOMContentLoaded 初期化

### 基本構造

```javascript
document.addEventListener('DOMContentLoaded', () => {
  // 機能初期化
});

// ユーティリティ
function lockScroll(lock) {
  const value = lock ? 'hidden' : '';
  document.body.style.overflowY = value;
}
```

### アコーディオン

```javascript
class AccordionToggle {
  constructor(element) {
    this.element = element;
    this.trigger = element.querySelector('.p-faq__q');
    this.content = element.querySelector('.p-faq__a');
    this.init();
  }
  init() {
    this.trigger.addEventListener('click', () => this.toggle());
  }
  toggle() {
    const isOpen = this.element.classList.contains('is-open');
    this.element.classList.toggle('is-open', !isOpen);
    this.content.style.maxHeight = isOpen ? '0' : this.content.scrollHeight + 'px';
  }
}

document.querySelectorAll('.js-accordion').forEach((el) => new AccordionToggle(el));
```

### ハンバーガーメニュー

```javascript
class DrawerToggle {
  constructor() {
    this.hamburger = document.querySelector('.js-hamburger');
    this.drawer = document.querySelector('.js-drawer');
    this.init();
  }
  init() {
    this.hamburger.addEventListener('click', () => this.toggle());
  }
  toggle() {
    const isActive = this.hamburger.classList.contains('is-active');
    this.hamburger.classList.toggle('is-active', !isActive);
    this.drawer.classList.toggle('is-active', !isActive);
    lockScroll(!isActive);
  }
}
new DrawerToggle();
```

### その他機能

```javascript
// TOPに戻るボタン
const toTopButton = document.querySelector('.js-to-top');
if (toTopButton) {
  window.addEventListener('scroll', () => {
    toTopButton.classList.toggle('is-active', window.scrollY > 600);
  });
  toTopButton.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  });
}

// ヘッダー非表示
let lastScrollTop = 0;
const header = document.querySelector('.js-header');
window.addEventListener('scroll', () => {
  const scrollTop = document.documentElement.scrollTop;
  header.classList.toggle('is-scrolled', scrollTop > lastScrollTop && scrollTop > window.innerHeight * 0.2);
  lastScrollTop = scrollTop;
});
```

## JavaScript フック

- `.js-accordion` - アコーディオン
- `.js-hamburger` - ハンバーガーメニュー
- `.js-drawer` - ドロワーメニュー
- `.js-header` - ヘッダー
- `.js-to-top` - トップに戻る

この規約に従って画像を正確にコード化してください。
