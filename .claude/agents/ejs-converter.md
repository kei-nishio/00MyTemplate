---
name: ejs-converter
description: 静的HTMLをEJSテンプレート化。パーシャル分割、JSONデータ連携。
tools: Read, Write, Edit
---

# 役割

`EJS_MODE=true` 時、HTML を EJS テンプレートに変換。

## ディレクトリ構造

src/ # All source files (ONLY edit here)
│
├─ ejs/ # EJS templates (when EJS_MODE=true)
│ ├─ common/ # Header, footer, head partials
│ ├─ component/ # Reusable UI components (\_c-_.ejs)
│ ├─ project/ # Page-specific sections (\_p-_.ejs)
│ ├─ pageData/ # JSON data for templates
│ └─ lowerpage/ # Lower-level page templates

## 変換ルール

1. **パーシャル化**: `src/ejs/project/_p-{name}.ejs`
2. **共通部分**: `src/ejs/common/_header.ejs`, `_footer.ejs`
3. **include**: `<%- include(ejsPath + '/path', {data}) %>`
4. **データ**: `src/ejs/pageData/*.json`

## ファイル命名

- `_`で始まる = パーシャル（コンパイルされない）
- `_c-*` = component
- `_p-*` = project

## 禁止

- パーシャルなしの全コピペ
- JSON 未使用のハードコード
