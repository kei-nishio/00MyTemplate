# 独自コーディングルール

このドキュメントは、独自の SCSS コーディングルールをまとめたものです。

## 必須ルール

### 先頭行

パーシャルファイルの先頭行に以下を記述する

```scss
@use 'global' as *;
```

### 独自関数による単位

ピクセル値を`r()`関数で rem 変換

- `r()`関数必須（例: `r(16)`, `r(24)`）
- 生の px 値は禁止とする（\_setting.scss を除く）

### レスポンシブ対応

モバイルファーストコーディングとする
ミックスインでレスポンシブ対応（生のメディアクエリは禁止）

```scss
.classname {
  // for sp style
  @include mq('md') {
    // for pc style
  }
}
```

```scss
$breakpoints: (
  sm: 600,
  md: 768,
  lg: 1024,
  xl: 1440,
);
```

### CSS 変数

`:root`で CSS 変数を定義。

```scss
:root {
  // fonts
  --font-jp: YakuHanJP, 'Zen Kaku Gothic New', sans-serif;
  --font-en: 'Jost', YakuHanJP, sans-serif;

  // z-index
  --zi-loading: 9000;
  --zi-modal: 7000;
  --zi-header: 5000;
  --zi-drawer: 3000;
  --zi-mask: 2000;
  --zi-floating: 1000;
  --zi-default: 1;

  // colors
  --color-white: #fff;
  --color-black: #000;
}
```

### Sass 変数

基本設定を Sass 変数で管理。

```scss
$width-sp: 375px; // SPのデザイン画面幅
$width-pc: 1366px; // PCのデザイン画面幅
$height-sp: 667px; // SPのデザイン画面高さ
$height-pc: 768px; // PCのデザイン画面高さ
$inner: 1080px; // コンテンツのインナー幅（基本値）
$inner-1200: 1200px; // コンテンツのインナー幅（特異値）
$maxWidth-tb: 600px; // タブレット時最大幅
$padding-sp: 15px; // SPの余白
$padding-pc: 25px; // PCの余白
$headerHeight-sp: 80px; // SPのヘッダー高さ
$headerHeight-pc: 110px; // PCのヘッダー高さ
```

### inner の設定

**基本値の場合**

.l-inner クラスがあるためそのままで問題ない

**特異値の場合**

```scss
.classname__inner.l-inner {
  @include mq('md') {
    width: vwLinear(768, 768, calc($inner-(specific number) + 2 * $padding-pc), $width-pc);
    max-width: calc($inner-(specific number) + $padding-pc * 2);
  }
}
```

### hover 対策（タッチデバイス）

`@media (any-hover: hover)`でタッチデバイスを除外。

```scss
@media screen and (any-hover: hover) {
  .classname:hover {
    opacity: 0.9;
  }
}
```

### パフォーマンス

- `will-change`: アニメーション要素のみ
- `transform`, `opacity` でアニメーション（reflow 回避）

### 論理プロパティの使用

`padding-inline`, `margin-inline`などを使用。

### calc()の利用

**line-height の分数表記**

```scss
font-size: r(14);
line-height: calc(20 / 14);
```

**ビューポート全幅に拡張**

```scss
margin-inline: calc(50% - 50vw);
```

### aspect-ratio の使用

画像やコンテナのアスペクト比を定義。

```scss
.classname__img {
  width: 100%;
  aspect-ratio: width_for_sp / height_for_sp;
  overflow: hidden;

  @include mq('md') {
    aspect-ratio: width_for_pc / height_for_pc;
  }

  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: 50% 50%;
  }
}
```

### BEM ネスティング

BEM 構造のネストは基本的に禁止する。

**例外的にネストを許可する事例**

- ソース読み込みタグ：img, iframe, video
- 擬似要素：before, after
- メディアクエリ：@include mq('md') {}

```scss
.classname {
  img {
  }
  &::before {
  }
  @include mq('md') {
  }
}
```

### モディファイアのスタイル

マルチクラスにして詳細度を上げる

```scss
.classname.classname--modifier {
}
```

### 空セレクター残存

将来のスタイル追加のため、空セレクターも残す。

```scss
.classname {
}
```

## 禁止

- !important（特別な理由なく）
- ID セレクタ
- 深いネスト（3 階層まで）
