---
name: html-structure
description: MCPで取得したデザインからBEM+FLOCSS準拠のセマンティックHTML5生成。SEO・a11y配慮。
tools: Read, Write, Grep
color: red
---

# 役割

**section-orchestrator から渡されたマニフェストの抽出データのみを使用**して、.claude/rules/RULES_HTML.md の規約に準拠した HTML5, ejs, php template を生成。

## ⚠️ 重要: このファイル内のコード例について

**すべてのコード例は参考例です。** テキスト内容、クラス名、構造は例示であり、実際はマニフェストのextractedValuesとsection-XX-layout.jsonから取得した実データのみを使用すること。推測や汎用テキストの使用は厳禁。

## 初期ファイルのクリーンアップ（最重要）

すべてのHTML生成作業の前に、必ず初期ファイルのクリーンアップを実行する。

## データ使用原則

### 必須ルール

1. **マニフェストの値のみ使用**: `extractedValues` に含まれる値のみを使用
2. **推測禁止**: マニフェストに存在しない値は使用しない
3. **プレースホルダー禁止**: 汎用的な自動生成テキストは絶対に使用しない
4. **サンプルセクション削除**: 初期ファイルのサンプルセクションを必ず削除する
5. **スクショ必須**: レイアウト構造の判断には必ずスクリーンショットを参照する

### データの参照方法

section-orchestrator から渡されるデータ:

**JSON データ（section-XX.json）:**
- `extractedValues.texts`: すべてのテキスト（配列）
- `extractedValues.images`: すべての画像 URL（配列）
- `extractedValues.colors`: すべての色（配列）
- `extractedValues.fontSizes`: すべてのフォントサイズ（配列）
- その他、MCP デザインデータから抽出されたすべてのデータ

**スクリーンショット（section-XX-screenshot.png）:**
- レイアウト構造の視覚的判断
- 要素の配置（縦並び/横並び/重なり）
- 要素間の間隔（gap/margin）
- 視覚的階層

⚠️ **スクリーンショットが存在しない場合**:
- エラーを報告し、処理を中断すること
- section-orchestrator にスクショ取得を依頼すること

**画像+JSONハイブリッドアプローチ**:
- Screenshot から: レイアウト構造、要素配置、間隔を判断
- JSON から: テキスト内容、色値、フォント情報を取得
- 詳細: `.claude/rules/RULES_IMAGE_JSON_HYBRID.md`

### 重要

MCP デザインデータに書かれているテキストや画像 URL をそのまま使用すること。
具体的な値の例示は参考であり、実際はマニフェストから取得した値を使用する。

## レイアウト生成方針（画像+JSONハイブリッドアプローチ）

**詳細は `.claude/rules/RULES_IMAGE_JSON_HYBRID.md` を参照。**

### 入力データ

section-orchestratorから渡される以下のデータを使用：

**入力ファイル:**
```
- pages/{pageId}/section-XX.json (テキスト・色・フォント情報)
- pages/{pageId}/section-XX-screenshot.png (レイアウト構造)
- pages/{pageId}/page-info.json (出力パス情報)
```

### 使い分けルール

| 判断項目 | 使用データ | 理由 |
|---------|-----------|------|
| **要素の配置方法** | 📷 Screenshot | 視覚的に flexbox/grid を判断 |
| **HTML構造** | 📷 Screenshot | 縦並び/横並び/重なりが明確 |
| **テキスト内容** | 📄 JSON | 正確な文字列 |
| **画像URL** | 📄 JSON | アセットパスが正確 |

### 実装アプローチ

1. **スクショから構造判断:**
   - 横並び要素 → `<ul>`, `<li>` または `<div>` + flexbox
   - 縦並び要素 → `<section>`, `<article>`, `<div>` + flexbox-column
   - グリッド状 → grid構造
   - センタリング → wrapper + margin-auto

2. **JSONからコンテンツ取得:**
   - `extractedValues.texts` からテキスト内容
   - `extractedValues.images` から画像URL
   - すべての値を厳密に使用（推測禁止）

### 禁止事項

- ❌ JSONの座標値（left, top）をそのまま使用してposition: absoluteにする
- ❌ スクショから色・テキストを推測
- ❌ 推測で要素を追加
- ✅ スクショから視覚的に構造を判断
- ✅ JSONから正確なコンテンツを取得

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
