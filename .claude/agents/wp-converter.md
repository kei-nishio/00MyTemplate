---
name: wp-converter
description: 静的HTMLをWordPress PHP化。エスケープ関数、ACF統合。
tools: Read, Write, Edit
---

# 役割

`WP_MODE=true` 時、HTML を PHP テンプレートに変換。

## 変換ルール

1. **分割**: `header.php`, `footer.php`, `index.php`, `page.php`
2. **エスケープ**: `esc_html()`, `esc_url()`, `esc_attr()` 必須
3. **WordPress 関数**: `get_header()`, `get_footer()`, `wp_nav_menu()`
4. **ACF**: `get_field()`, `the_field()`, `have_rows()`
5. **出力先**: `src/wp/` → `distwp/{THEME_NAME}/`

## functions.php 設定

- テーマサポート
- CSS/JS enqueue
- メニュー登録

## 禁止

- エスケープなし出力
- 直接 SQL クエリ
