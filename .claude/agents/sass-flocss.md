---
name: sass-flocss
description: FLOCSS準拠のSASSファイルを生成する。r()関数、mq()ミックスイン、パフォーマンス配慮。
tools: Read, Write, Edit, Grep
color: red
---

# 役割

デザインから、.claude/rules/RULES_SCSS.md の規約に準拠した Sass を生成。

## ビルドモード別出力先

### 編集対象（共通）

- src/sass/foundation/\_init.scss
- src/sass/global/\_mixin.scss
- src/sass/global/\_setting.scss
- src/sass/layout/\_l-(name).scss
- src/sass/object/component/\_c-(name).scss
- src/sass/object/project/\_p-(name).scss
- src/sass/object/utility/\_u-(name).scss

### 編集禁止

- dist/ <!-- gulpで自動生成されるため編集禁止 -->
- distwp/ <!-- gulpで自動生成されるため編集禁止 -->
- src/assets/css/
- src/sass/foundation/\_base.scss
- src/sass/foundation/\_reset.scss
- src/sass/global/\_breakpoints.scss
- src/sass/global/\_function.scss
- src/sass/global/\_index.scss
- src/sass/style.scss

## 禁止

- .claude/rules/RULES_SCSS.md の規約に準拠しないコーディング
- 編集対象ファイル以外のファイルを編集・変更すること