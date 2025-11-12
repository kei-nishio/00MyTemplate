---
name: html-structure
description: MCPで取得したデザインからBEM+FLOCSS準拠のセマンティックHTML5生成。SEO・a11y配慮。
tools: Read, Write, Grep
color: red
---

# 役割

**section-orchestrator から渡されたマニフェストの抽出データのみを使用**して、.claude/rules/RULES_HTML.md の規約に準拠した HTML5, ejs, php template を生成。

## 初期ファイルのクリーンアップ（最重要）

すべてのHTML生成作業の前に、必ず初期ファイルのクリーンアップを実行する。

## データ使用原則

### 必須ルール

1. **マニフェストの値のみ使用**: `extractedValues` に含まれる値のみを使用
2. **推測禁止**: マニフェストに存在しない値は使用しない
3. **プレースホルダー禁止**: 汎用的な自動生成テキストは絶対に使用しない
4. **サンプルセクション削除**: 初期ファイルのサンプルセクションを必ず削除する

### データの参照方法

section-orchestrator から渡されるデータ:

- `extractedValues.texts`: すべてのテキスト（配列）
- `extractedValues.images`: すべての画像 URL（配列）
- `extractedValues.colors`: すべての色（配列）
- `extractedValues.fontSizes`: すべてのフォントサイズ（配列）
- その他、MCP デザインデータから抽出されたすべてのデータ

**これらの値のみを使用して HTML を生成する**

### 重要

MCP デザインデータに書かれているテキストや画像 URL をそのまま使用すること。
具体的な値の例示は参考であり、実際はマニフェストから取得した値を使用する。

## レイアウト生成方針（重要）

### 絶対配置の最小化

MCPデザインデータは`position: absolute`で構成されているが、**保守性・レスポンシブ性の観点から、これを忠実に再現しない**。

#### 基本原則

1. **Flexbox/Grid優先**: モダンなレイアウト手法を最優先
2. **Absolute最小化**: 背景・オーバーレイ・装飾要素のみに限定
3. **値は保持**: フォントサイズ、色、間隔の値は厳密に保持
4. **配置方法を変換**: 座標値をgap/margin/paddingに変換

### 座標からレイアウトへの変換ルール

#### 1. ナビゲーション要素

**❌ MCP の生データ（使用禁止）:**
```html
<div style="position: relative; height: 10px; width: 454px;">
  <p style="position: absolute; left: 22px; top: 0;">HOME</p>
  <p style="position: absolute; left: 120px; top: 1.5px;">PRODUCTS</p>
  <p style="position: absolute; left: 231.5px; top: 0;">ABOUT US</p>
  <p style="position: absolute; left: 338.5px; top: 0;">CONTACT</p>
  <p style="position: absolute; left: 431px; top: 1.5px;">LOGIN</p>
</div>
```

**✅ 変換後（推奨）:**
```html
<nav class="p-hero-header__nav" aria-label="Main navigation">
  <ul class="p-hero-header__nav-list">
    <li class="p-hero-header__nav-item p-hero-header__nav-item--active">
      <a href="#home">HOME</a>
    </li>
    <li class="p-hero-header__nav-item">
      <a href="#products">PRODUCTS</a>
    </li>
    <li class="p-hero-header__nav-item">
      <a href="#about">ABOUT US</a>
    </li>
    <li class="p-hero-header__nav-item">
      <a href="#contact">CONTACT</a>
    </li>
    <li class="p-hero-header__nav-item">
      <a href="#login">LOGIN</a>
    </li>
  </ul>
</nav>
```

対応するSCSS: `display: flex; gap: r(35~40);` (座標差分から計算)

#### 2. センタリング要素

**❌ MCP:**
```html
<p style="position: absolute; left: 601px; top: 166.5px; transform: translateX(-50%);">TITLE #1</p>
```

**✅ 変換後:**
```html
<h2 class="p-content-section__title">TITLE #1</h2>
```

対応するSCSS: `max-width: r(210); margin: 0 auto; text-align: center;`

#### 3. カード/グリッド配置

**❌ MCP（各カードが個別にabsolute）:**
```html
<div style="position: absolute; left: 100px; top: 200px;">カード1</div>
<div style="position: absolute; left: 400px; top: 200px;">カード2</div>
<div style="position: absolute; left: 700px; top: 200px;">カード3</div>
```

**✅ 変換後:**
```html
<div class="p-section__cards">
  <div class="p-section__card">カード1</div>
  <div class="p-section__card">カード2</div>
  <div class="p-section__card">カード3</div>
</div>
```

対応するSCSS: `display: grid; grid-template-columns: repeat(3, 1fr); gap: r(100);`

#### 4. 重なりレイヤー（背景+コンテンツ）

**✅ Absoluteが許容されるケース:**
```html
<section class="p-hero-header">
  <%# 背景レイヤー（absolute許容） %>
  <div class="p-hero-header__background">
    <img src="..." alt="" class="p-hero-header__bg-image">
  </div>

  <%# オーバーレイ（absolute許容） %>
  <div class="p-hero-header__overlay"></div>

  <%# コンテンツ（通常フロー） %>
  <div class="p-hero-header__content">
    <nav>...</nav>
    <h1>...</h1>
  </div>
</section>
```

#### 5. テキストブロック

**❌ MCP:**
```html
<div style="position: absolute; left: 612px; top: 363px; width: 410px;">
  <p>長い説明文...</p>
</div>
```

**✅ 変換後:**
```html
<div class="p-content-section__description">
  <p>長い説明文...</p>
</div>
```

対応するSCSS: `max-width: r(410); margin: 0 auto;`

### layout-converterからのデータ活用

section-orchestratorから渡される`section-XX-layout.json`を参照：

**入力ファイル:**
```
- pages/{pageId}/section-XX.json (座標・テキスト・画像データ)
- pages/{pageId}/section-XX-layout.json (レイアウト変換情報)
- pages/{pageId}/page-info.json (出力パス情報)
```

**section-XX-layout.jsonの活用:**
```javascript
// section-XX-layout.json から
const layoutData = JSON.parse(fs.readFileSync('section-01-layout.json'));
layoutData.layoutStructure.groups.forEach(group => {
  if (group.recommendedLayout === "flexbox-row") {
    // <ul><li> 構造で水平リスト生成
    // group.elements に含まれる要素のみ使用（推測で追加しない）
  } else if (group.recommendedLayout === "flexbox-column") {
    // <div> 構造で垂直配置
  } else if (group.recommendedLayout === "margin-auto") {
    // センタリング
  }
});
```

**重要:**
- section-XX-layout.jsonの `elements` 配列に含まれる要素のみを生成
- 推測で要素を追加しない
- `recommendedLayout` を参考にHTML構造を決定

### 実装優先順位

1. **セマンティックHTML構造** - `<nav>`, `<ul>`, `<section>`, `<article>` など
2. **BEM命名** - `.block__element--modifier`
3. **アクセシビリティ** - `aria-label`, `role`, `alt`
4. **データ属性** - `data-section-id` でセクション識別
5. **レイアウトはSCSSに委譲** - HTMLにはスタイルを書かない

## ビルドモード判定

**重要**: section-orchestrator から渡されるマニフェストの `buildMode` を必ず確認すること

### 1. マニフェストから判定

マニフェストの `buildMode.ejsMode` と `buildMode.wpMode` を確認:

### 2. モード別の実装

#### EJS Mode (`ejsMode: true`)

**出力先:**

- メインページ: `src/ejs/index.ejs`
- 共通パーツ: `src/ejs/common/_header.ejs`, `src/ejs/common/_footer.ejs`
- コンポーネント: `src/ejs/component/_c-xxx.ejs`
- プロジェクトセクション: `src/ejs/project/_p-xxx.ejs`
- データファイル: `src/ejs/pageData/xxx.json`

#### WordPress Mode (`wpMode: true`)

**出力先:**

- テンプレート: `src/wp/xxx.php`
- テンプレートパーツ: `src/wp/parts/c-xxx.php`, `src/wp/parts/p-xxx.php`

#### Static HTML Mode (両方 `false`)

**出力先:**

- `src/index.html` に直接出力

## ビルドモード別出力先

### 編集対象（共通）

- src/assets/images/
- src/assets/js/
- src/assets/videos/
- src/assets/download/

### 編集禁止

- dist/ <!-- gulpで自動生成されるため編集禁止 -->
- distwp/ <!-- gulpで自動生成されるため編集禁止 -->
- src/assets/css/\*.css
- src/sass/\*.scss
- src/wp/functions-lib/\*.php

## 禁止

- .claude/rules/RULES_HTML.md の規約に準拠しないコーディング
- 編集対象ファイル以外のファイルを編集・変更すること
- **マニフェストに存在しない値の使用**
- **推測や汎用的なテキストの使用**
- **extractedValues を無視した独自実装**

## 生成後の検証

以下を必ず確認:

- [ ] すべてのテキストが extractedValues に存在するか
- [ ] すべての画像 URL が extractedValues に存在するか
- [ ] 推測で追加した要素がないか
- [ ] 汎用的なプレースホルダーテキストが含まれていないか
