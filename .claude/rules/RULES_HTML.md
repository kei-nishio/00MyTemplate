# 独自コーディングルール

このドキュメントは、独自の HTML(EJS, WordPress Template PHP) コーディングルールをまとめたものです。

## 必須ルール

### セマンティックタグ

SEO 対策のためにセマンティックな構造とする。

- h1~h6 は階層構造とする
  - h1 はトップページの場合、ヘッダーロゴとする。
  - h1 は下層ページの場合、ページタイトルとする。
- 適切なタグを使用する
  - header, nav, section, article, footer, aside, etc...

### 視認性（コメントの適切な利用）

HTML コメントではなく、EJS, PHP コメントを使用。

**ejs**

```ejs
<%# header %>
<%# drawer %>
<%# FV %>
```

**php**

```php
<?php // header ?>
<?php // drawer ?>
<?php // FV ?>
```

### BEM + FLOCSS 命名

ファイル名とクラス名に FLOCSS 準拠のプレフィックスを使用。

- prefix: l-(layout), c-(component), p-(project), u-(utility)
- BEM: p-section\_\_element--modifier
- state: is-(status), has-(element)

| Prefix | 意味                         | 例                                 |
| ------ | ---------------------------- | ---------------------------------- |
| `c-`   | Component（再利用可能な UI） | `c-button-(name)`, `c-card-(name)` |
| `p-`   | Project（ページ固有）        | `p-fv`, `p-header`, `p-drawer`     |
| `l-`   | Layout（レイアウト構造）     | `l-inner`, `l-section`, `l-fv`     |
| `u-`   | Utility（ユーティリティ）    | `u-sp`, `u-pc`                     |

### data 属性（JavaScript フック）

JavaScript のフックとして`data-*`属性を使用。

- data-header: ヘッダー要素
- data-drawer: ドロワーメニュー
- data-hamburger: ハンバーガーボタン
- data-tab="(name)", data-content="(name)": タブ切り替え
- data-animation="(animation type)": アニメーション種別指定

例:

```
<button data-hamburger aria-expanded="false">
<div data-drawer>
<div data-animation="fade-in-bottom">
```

### セクション構造テンプレート

- 一つのタグにつき一つの機能となるように構成

```
<div class="p-(name)">
    <div class="p-(name)__inner l-inner">
        <div class="p-(name)__heading">
            <h2 class="c-section-title">タイトル</h2>
        </div>
        <div class="p-(name)__content">
           contents here
        </div>
    </div>
</div>
```

### 画像（img タグ）

- width, height 属性必須（CLS 対策）
- alt 属性必須（日本語、内容を説明）
- loading
  - 1 つ目のセクションの場合、`loading="eager"　 fetchpriority="high" `
  - 2 つ目のセクション以降の場合、`loading="lazy"`
- decoding="async"推奨

### アクセシビリティ

アクセシビリティのために ARIA 属性を使用。

- ボタン: aria-label, aria-expanded
- ナビゲーション: nav aria-label="メインナビゲーション"
- フォーム: label for="id"と input id="id"を関連付け

```html
<button aria-label="メニュー" aria-expanded="false" data-hamburger></button>
```

### パーシャルの読み込み（変数やモディファイアー）

**ejs**

```ejs
<%- include('../xxx/_p-(name)') %>
<%- include(baseUrlPath + '/common/_head', { baseUrl: baseUrlPath, page: json.pageData[pageData] }) %>
```

**php**

```php
<?php get_template_part('/parts/c-card-(name)', , ["post_id" => $post_id]); ?>
```

### パーツ側での受け取り

**ejs**

```ejs
<% const { variable } = typeof variable !== 'undefined' ? { variable } : { variable: ''}; %>
```

```ejs
<% const modifierClass = modifier ? ` (name)--${modifier}` : ''; %>
```

**php**

```php
<?php $variable = $args["variable"]; ?>
```

```php
<?php $modifierClass = $modifier ? ' (name)--' . $modifier : ''; ?>
```

---

## WordPress(PHP)のみに適用されるルール

### グローバル変数の使用

テーマ URI をグローバル変数で管理しているので、テンプレートファイル冒頭で変数を読み込む

```php
<?php global $theme_uri; ?>
```

### エスケープ関数の厳格な使用

出力時は必ず適切なエスケープ関数を使用。

| 関数             | 用途                                       |
| ---------------- | ------------------------------------------ |
| `esc_url()`      | URL                                        |
| `esc_html()`     | HTML テキスト                              |
| `esc_attr()`     | HTML 属性値                                |
| `wp_kses_post()` | 投稿コンテンツ（許可された HTML タグのみ） |

### WordPress ループとサブクエリ

カスタムクエリでループ処理。

```php
<?php
$post_type = 'post';
$num = wp_is_mobile() ? 3 : 3;
$args = [
  'post_type' => $post_type,
  'posts_per_page' => $num,
  'orderby' => 'date',
  'order' => 'DESC',
];
$the_query = new WP_query($args);
?>
<?php if ($the_query->have_posts()) : ?>
  <ul class="p-(name)__list">
    <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
      <li class="p-(name)__item">
        <?php $post_id = get_the_ID(); ?>
        <?php get_template_part('/parts/c-card-(name)', , ["post_id" => $post_id]); ?>
      </li>
    <?php endwhile; ?>
  </ul>
<?php else : ?>
  <p>記事が投稿されていません。</p>
<?php endif; ?>
<?php wp_reset_postdata(); ?>
```

### modular functions 読み込み

`functions.php`で機能ごとにファイルを分割して読み込む。

---

## 禁止
