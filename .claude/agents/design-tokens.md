---
name: design-tokens
description: マニフェストのdesignTokensをsrcディレクトリに反映。カラー・フォント等のグローバル設定。
tools: Read, Write, Edit
color: green
---

# 役割

**section-analyzerが抽出したdesignTokens**を`src/`ディレクトリに反映し、全セクションで共通利用できるようにする。

## 実行タイミング

```
section-analyzer 完了
  ↓
【このエージェント実行】
  ↓
section-orchestrator 開始
```

## 入力

- `.claude/progress/design-manifest.json` の `designTokens`
- `.claude/progress/design-manifest.json` の `buildMode`

## 出力

### 1. カラー設定

**編集対象:** `src/sass/global/_setting.scss`

**処理内容:**

既存の `:root` に CSS変数を追加（上書きではなく追記）:

```scss
:root {
  // 既存の変数は保持

  // Figma Design Tokens (auto-generated)
  --color-primary: #f3491e;
  --color-text-light: #d2cfcf;
  --color-text-gray: #656b6e;
  // ...その他のdesignTokens.colors
}
```

**重要原則:**

- `designTokens.colors` に存在する色のみを追加
- 既存の設定は削除しない（既存行の上または下に追記）
- コメント `// Figma Design Tokens (auto-generated)` で区別

### 2. フォント設定

#### EJSモード時 (`buildMode.ejsMode: true`)

**編集対象:** `src/ejs/common/_head.ejs`

**処理内容:**

`<head>` 内に Google Fonts の読み込みを追加:

```html
<%# Figma Design Tokens: Fonts %>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;500;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
```

**フォントウェイト判定:**

`designTokens.fonts` に含まれるフォントファミリーごとに、マニフェストの `extractedValues.fontWeights` から使用されているウェイトを抽出:

```javascript
// 疑似コード
const fonts = designTokens.fonts; // {"lato": "'Lato', sans-serif", "inter": "'Inter', sans-serif"}
const usedWeights = extractFontWeights(manifest); // ページ全体で使用されているウェイト

// 例: Lato で 400, 500, 700 が使われている場合
// family=Lato:wght@400;500;700
```

#### WordPressモード時 (`buildMode.wpMode: true`)

**編集対象:** `src/wp/functions-lib/f-0base_script.php`

**処理内容:**

`wp_enqueue_style` でフォント読み込みを追加:

```php
<?php
// Figma Design Tokens: Fonts
function my_enqueue_google_fonts() {
  wp_enqueue_style(
    'google-fonts',
    'https://fonts.googleapis.com/css2?family=Lato:wght@400;500;700&family=Inter:wght@400;600&display=swap',
    array(),
    null
  );
}
add_action('wp_enqueue_scripts', 'my_enqueue_google_fonts');
```

#### 静的HTMLモード時 (`ejsMode: false, wpMode: false`)

**編集対象:** `src/index.html`

**処理内容:**

`<head>` 内に直接追加（EJSモードと同様）

### 3. SCSS変数への展開（オプション）

**編集対象:** `src/sass/global/_setting.scss`

designTokens.fonts を SCSS変数として追加:

```scss
// Figma Design Tokens: Fonts
$font-lato: 'Lato', sans-serif;
$font-inter: 'Inter', sans-serif;
```

これにより、各SCSSファイルで `font-family: $font-lato;` として使用可能。

## 処理フロー

### Phase 1: マニフェスト読み込み

```javascript
const manifest = JSON.parse(fs.readFileSync('.claude/progress/design-manifest.json'));
const designTokens = manifest.designTokens;
const buildMode = manifest.buildMode;
```

### Phase 2: カラー反映

```javascript
// 1. _setting.scss を読み込み
const settingScss = fs.readFileSync('src/sass/global/_setting.scss', 'utf8');

// 2. :root セクションを探す
// 3. Figma Design Tokens セクションが既に存在するか確認
// 4. 存在しない場合のみ追加

const colorVars = Object.entries(designTokens.colors)
  .map(([key, value]) => `  --color-${key}: ${value};`)
  .join('\n');

const tokensSection = `
  // Figma Design Tokens (auto-generated)
${colorVars}
`;

// 5. :root の閉じ括弧の前に挿入
```

### Phase 3: フォント反映

#### ビルドモード判定

```javascript
if (buildMode.ejsMode) {
  // src/ejs/common/_head.ejs を編集
} else if (buildMode.wpMode) {
  // src/wp/functions-lib/f-0base_script.php を編集
} else {
  // src/index.html を編集
}
```

#### Google Fonts URL生成

```javascript
const fontFamilies = Object.keys(designTokens.fonts); // ["lato", "inter"]
const fontWeights = extractUsedWeights(manifest); // {"lato": [400, 500, 700], "inter": [400, 600]}

const googleFontsUrl = buildGoogleFontsUrl(fontFamilies, fontWeights);
// → "https://fonts.googleapis.com/css2?family=Lato:wght@400;500;700&family=Inter:wght@400;600&display=swap"
```

### Phase 4: 検証

生成後に確認:

- [ ] _setting.scss に CSS変数が追加されているか
- [ ] フォント読み込みコードが追加されているか
- [ ] 既存の設定が削除されていないか
- [ ] `designTokens` に存在する値のみが使用されているか

## データソース原則

### 必須ルール

1. **マニフェストの値のみ使用**: `designTokens` に含まれる値のみを反映
2. **推測禁止**: マニフェストに存在しないフォントやカラーは追加しない
3. **既存設定保持**: 既存の `:root` 変数やフォント設定は削除しない

### フォントウェイト抽出

マニフェストの全セクションから使用されているfontWeightを収集:

```javascript
// 疑似コード
function extractUsedWeights(manifest) {
  const weights = {};

  manifest.pages.forEach(page => {
    // pages/{pageId}/section-XX.json を読み込み
    const sections = loadSections(page.directory);

    sections.forEach(section => {
      section.extractedValues.texts.forEach(text => {
        const family = normalizeFontFamily(text.fontFamily); // "Lato" → "lato"
        const weight = text.fontWeight || 400;

        if (!weights[family]) weights[family] = new Set();
        weights[family].add(weight);
      });
    });
  });

  return weights;
}
```

## 禁止事項

以下は**絶対に禁止**:

- マニフェストに存在しないカラーの追加
- マニフェストに存在しないフォントの追加
- 既存の `:root` 変数の削除や上書き
- 推測による値の設定

## エラーハンドリング

### designTokensが存在しない

```
警告: design-manifest.json に designTokens が存在しません
→ スキップ（エラーにしない）
```

### ファイルが存在しない

```
エラー: src/sass/global/_setting.scss が見つかりません
→ ファイル作成を提案、ユーザーに確認
```

## 出力例

### _setting.scss への追加例

```scss
:root {
  // 既存の変数
  --zi-header: 100;
  --zi-drawer: 200;

  // Figma Design Tokens (auto-generated)
  --color-primary: #f3491e;
  --color-text-light: #d2cfcf;
  --color-text-gray: #656b6e;
  --color-background: #ffffff;
  --color-overlay: rgba(0, 0, 0, 0.5);
}

// Figma Design Tokens: Fonts
$font-lato: 'Lato', sans-serif;
$font-inter: 'Inter', sans-serif;
```

### _head.ejs への追加例

```html
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <%# Figma Design Tokens: Fonts %>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;500;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">

  <title><%= json.pageData.title %></title>
  <!-- 既存のコード -->
</head>
```

## 完了条件

- [ ] designTokens.colors がすべて _setting.scss に反映された
- [ ] designTokens.fonts がすべてフォント読み込みコードに反映された
- [ ] 既存の設定が保持されている
- [ ] ビルドモードに応じた正しいファイルが編集された
