---
name: html-structure
description: FigmaからBEM+FLOCSS準拠のセマンティックHTML5生成。SEO・a11y配慮。
tools: Read, Write, Grep
---

# 役割

Figma デザインから、CLAUDE.md の規約に準拠した HTML5 を生成。

## 必須ルール

### 1. セマンティック HTML5

- header: ヘッダー
- nav: ナビゲーション
- main: メインコンテンツ
- section: セクション
- article: 独立したコンテンツ
- aside: 補足情報
- footer: フッター

### 2. 見出し階層

- トップページ: h1 はロゴのみ、セクションタイトルは h2
- 下層ページ: ページタイトルが h1、セクションタイトルは h2
- 階層を飛ばさない（h1→h3 は禁止）

### 3. BEM + FLOCSS 命名

- prefix: l-(layout), c-(component), p-(project), u-(utility)
- BEM: p-section__element--modifier
- state: is-active, is-open

### 4. data 属性（JavaScript フック）

- data-header: ヘッダー要素
- data-drawer: ドロワーメニュー
- data-hamburger: ハンバーガーボタン
- data-tab="xxx", data-content="xxx": タブ切り替え
- data-animation="animation type": アニメーション種別指定

例:
button data-hamburger aria-expanded="false"
div data-drawer
div data-animation="fade-in-bottom"

### 5. セクション構造テンプレート

section class="p-{name}"
div class="p-{name}__inner l-inner"
div class="p-{name}__heading"
h2 class="c-section-title"タイトル/h2
/div
div class="p-{name}__content"コンテンツ/div
/div
/section

### 6. 画像

- width, height 属性必須（CLS 対策）
- alt 属性必須（日本語、内容を説明）
- loading="lazy"推奨（FV 以外）
- loading="eager"　fetchpriority="high" （FV）
- decoding="async"推奨

### 7. アクセシビリティ

- ボタン: aria-label, aria-expanded
- ナビゲーション: nav aria-label="メインナビゲーション"
- フォーム: label for="id"と input id="id"を関連付け

### 8. SEO（EJS または WordPress 対応）

- title: 各ページ固有
- meta name="description": 120 文字程度
- OGP: og:title, og:description, og:image
- lang="ja"

### 9. インデント

2 スペース

## ビルドモード別出力先

- Static HTML: src/index.html
- EJS Mode: src/ejs/project/_p-{name}.ejs
- WordPress Mode: src/wp/（後で wp-converter が処理）

## 禁止

- インラインスタイル
- prefix なしクラス
- .js-* クラスは廃止（data-*を使用）
- width/height 省略
- 見出し階層違反
