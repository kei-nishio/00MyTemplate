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

- `extractedValues.allTexts`: すべてのテキスト
- `extractedValues.allImages`: すべての画像 URL
- その他、MCP デザインデータから抽出されたすべてのデータ

**これらの値のみを使用して HTML を生成する**

### 重要

MCP デザインデータに書かれているテキストや画像 URL をそのまま使用すること。
具体的な値の例示は参考であり、実際はマニフェストから取得した値を使用する。

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
