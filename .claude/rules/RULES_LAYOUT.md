# レイアウト変換ルール（全エージェント共通）

**対象エージェント:** layout-converter, html-structure, sass-flocss

このドキュメントは、MCPデザインデータ（座標ベースのabsolute配置）をモダンなレイアウト手法に変換する際の共通ルールを定義します。

---

## ⚠️ 重要: コード例について

**このファイル内のすべてのコード例は参考例です。**

- テキスト内容（"HOME", "PRODUCTS", "YOUR COMPANY"など）は**例示**
- 座標値（left: 666px, top: 54pxなど）は**サンプル**
- クラス名（.p-hero-header, .p-content-sectionなど）は**命名例**

**実装時は以下を必ず遵守:**
1. MCPデザインデータから抽出した実際の値を使用
2. マニフェスト（section-XX.json）の extractedValues を参照
3. コード例の値をそのまま使用しない
4. 推測や汎用的なプレースホルダーを使用しない

---

## 基本原則

### 1. 値は保持、配置方法を変換

✅ **保持するもの:**
- フォントサイズ、行高、文字間隔
- 色、背景色
- テキスト内容
- 画像URL
- 要素のサイズ（width, height）

✅ **変換するもの:**
- 座標値（left, top） → gap/margin/padding
- absolute配置 → flexbox/grid/margin-auto
- 要素間の距離 → gap値

### 2. Flexbox/Grid優先、Absolute最小化

**レイアウト手法の優先順位:**

1. **Flexbox** - 1次元レイアウト（ナビゲーション、縦/横並び）
2. **CSS Grid** - 2次元レイアウト（カードグリッド、複雑な配置）
3. **margin/padding** - 単純なスペーシング、センタリング
4. **position: absolute** - 背景・オーバーレイ・装飾のみ（最終手段）

### 3. 座標値をgap/margin/paddingに変換

要素間の距離を計算してレスポンシブ対応:

**水平方向:**
```
gap = 次の要素のleft - (現在の要素のleft + width)
```

**垂直方向:**
```
gap = 次の要素のtop - (現在の要素のtop + height)
```

---

## 変換パターン集

### パターン1: ナビゲーション（水平リスト）

#### MCPデザインデータ（座標ベース）

```html
<!-- ❌ これをそのまま使ってはいけない -->
<div style="position: relative; width: 454px; height: 10px;">
  <p style="position: absolute; left: 22px; top: 0;">HOME</p>
  <p style="position: absolute; left: 120px; top: 1.5px;">PRODUCTS</p>
  <p style="position: absolute; left: 231.5px; top: 0;">ABOUT US</p>
  <p style="position: absolute; left: 338.5px; top: 0;">CONTACT</p>
  <p style="position: absolute; left: 431px; top: 1.5px;">LOGIN</p>
</div>
```

**座標分析:**
```
要素1 (HOME): left: 22, width: 45.1
要素2 (PRODUCTS): left: 120
gap = 120 - (22 + 45.1) = 52.9px

要素2 (PRODUCTS): left: 120, width: 81
要素3 (ABOUT US): left: 231.5
gap = 231.5 - (120 + 81) = 30.5px

平均gap = (52.9 + 30.5 + ...) / n ≈ 40px
```

#### 変換後のHTML（✅ 推奨）

```html
<nav class="p-hero-header__nav" aria-label="Main navigation">
  <ul class="p-hero-header__nav-list">
    <li class="p-hero-header__nav-item p-hero-header__nav-item--active">
      <a href="#home" class="p-hero-header__nav-link">HOME</a>
    </li>
    <li class="p-hero-header__nav-item">
      <a href="#products" class="p-hero-header__nav-link">PRODUCTS</a>
    </li>
    <li class="p-hero-header__nav-item">
      <a href="#about" class="p-hero-header__nav-link">ABOUT US</a>
    </li>
    <li class="p-hero-header__nav-item">
      <a href="#contact" class="p-hero-header__nav-link">CONTACT</a>
    </li>
    <li class="p-hero-header__nav-item">
      <a href="#login" class="p-hero-header__nav-link">LOGIN</a>
    </li>
  </ul>
</nav>
```

#### 変換後のSCSS（✅ 推奨）

```scss
.p-hero-header__nav-list {
  display: flex;
  gap: r(40);  // 座標から計算した平均gap
  align-items: center;
  justify-content: flex-end;  // 右寄せ（元の配置が画面右端に近い場合）
}

.p-hero-header__nav-item {
  // 個別の配置不要（flexboxが自動配置）
}

.p-hero-header__nav-link {
  font-family: $font-lato;
  font-size: r(11);  // MCPデータから保持
  letter-spacing: r(2.35);  // MCPデータから保持
  color: var(--color-white);

  // アクティブ状態（モディファイア）
  .p-hero-header__nav-item--active & {
    color: var(--color-primary);
  }
}
```

---

### パターン2: カードグリッド（2次元レイアウト）

#### MCPデザインデータ（座標ベース）

```html
<!-- ❌ これをそのまま使ってはいけない -->
<div style="position: relative;">
  <div style="position: absolute; left: 0; top: 0; width: 300px; height: 400px;">Card 1</div>
  <div style="position: absolute; left: 340px; top: 0; width: 300px; height: 400px;">Card 2</div>
  <div style="position: absolute; left: 680px; top: 0; width: 300px; height: 400px;">Card 3</div>
  <div style="position: absolute; left: 0; top: 440px; width: 300px; height: 400px;">Card 4</div>
  <div style="position: absolute; left: 340px; top: 440px; width: 300px; height: 400px;">Card 5</div>
  <div style="position: absolute; left: 680px; top: 440px; width: 300px; height: 400px;">Card 6</div>
</div>
```

**座標分析:**
```
水平gap = 340 - (0 + 300) = 40px
垂直gap = 440 - (0 + 400) = 40px
カラム数 = 3
```

#### 変換後のHTML（✅ 推奨）

```html
<div class="p-card-grid">
  <article class="p-card-grid__item">Card 1</article>
  <article class="p-card-grid__item">Card 2</article>
  <article class="p-card-grid__item">Card 3</article>
  <article class="p-card-grid__item">Card 4</article>
  <article class="p-card-grid__item">Card 5</article>
  <article class="p-card-grid__item">Card 6</article>
</div>
```

#### 変換後のSCSS（✅ 推奨）

```scss
.p-card-grid {
  display: grid;
  grid-template-columns: repeat(3, r(300));  // 3カラム、各300px
  gap: r(40);  // 座標から計算したgap

  @include mq(md, max) {
    grid-template-columns: repeat(2, 1fr);  // タブレット: 2カラム
  }

  @include mq(sm, max) {
    grid-template-columns: 1fr;  // モバイル: 1カラム
  }
}

.p-card-grid__item {
  // カードのスタイル
}
```

---

### パターン3: センタリング（中央配置）

#### MCPデザインデータ（座標ベース）

```html
<!-- ❌ これをそのまま使ってはいけない -->
<div style="position: relative;">
  <h2 style="position: absolute; left: 601px; top: 166.5px; transform: translateX(-50%);">
    TITLE #1
  </h2>
</div>
```

**座標分析:**
```
left: 601px, transform: translateX(-50%)
→ 中央寄せ（セクション幅1200pxの約50%位置）
```

#### 変換後のHTML（✅ 推奨）

```html
<div class="p-content-section__content">
  <h2 class="p-content-section__title">TITLE #1</h2>
</div>
```

#### 変換後のSCSS（✅ 推奨）

```scss
.p-content-section__content {
  display: flex;
  flex-direction: column;
  align-items: center;  // 水平中央寄せ
}

.p-content-section__title {
  font-family: $font-inter;
  font-size: r(50);  // MCPデータから保持
  color: var(--color-primary);
  text-align: center;

  // または margin-autoを使う方法
  // margin-inline: auto;
  // text-align: center;
}
```

---

### パターン4: 垂直スタック（縦並び）

#### MCPデザインデータ（座標ベース）

```html
<!-- ❌ これをそのまま使ってはいけない -->
<div style="position: relative;">
  <h1 style="position: absolute; left: 132px; top: 321px;">Title</h1>
  <p style="position: absolute; left: 279.5px; top: 469px;">Subtitle</p>
</div>
```

**座標分析:**
```
要素1 (Title): top: 321, height: 67.6
要素2 (Subtitle): top: 469
gap = 469 - (321 + 67.6) = 80.4px
```

#### 変換後のHTML（✅ 推奨）

```html
<div class="p-hero-header__hero">
  <h1 class="p-hero-header__title">Title</h1>
  <p class="p-hero-header__subtitle">Subtitle</p>
</div>
```

#### 変換後のSCSS（✅ 推奨）

```scss
.p-hero-header__hero {
  display: flex;
  flex-direction: column;
  align-items: center;  // 中央寄せ
  gap: r(80);  // 座標から計算したgap
}

.p-hero-header__title {
  font-family: $font-inter;
  font-size: r(59);
  color: var(--color-white);
}

.p-hero-header__subtitle {
  font-family: $font-lato;
  font-size: r(23);
  color: var(--color-text-light);
  opacity: 0.702;
}
```

---

## Absolute使用の許可条件

### ✅ Absoluteを使用してよいケース

#### 1. 背景画像（full-cover）

```scss
.p-hero-header__background {
  position: absolute;
  inset: 0;
  z-index: 1;
}

.p-hero-header__bg-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
```

**理由:** 背景は他の要素の下に敷かれるべきで、コンテンツフローに影響しない

#### 2. オーバーレイ（blend mode使用）

```scss
.p-hero-header__overlay {
  position: absolute;
  inset: 0;
  z-index: 2;
  mix-blend-mode: multiply;
}
```

**理由:** 視覚効果として背景に重ねる必要がある

#### 3. 装飾要素（非コンテンツ）

```scss
.p-content-section__decoration {
  position: absolute;
  inset: 0;
  z-index: 2;
  pointer-events: none;  // クリック不可
}

.p-content-section__decoration-square1 {
  position: absolute;
  left: r(101);
  top: r(1);
  width: r(1001);
  height: r(722);
}
```

**理由:** 純粋な装飾で、ユーザーインタラクションがない

---

### ❌ Absoluteを使用してはいけないケース

#### 1. ナビゲーション

```scss
// ❌ 禁止
.nav-item {
  position: absolute;
  left: r(666);
  top: r(54);
}

// ✅ 正解
.nav-list {
  display: flex;
  gap: r(40);
}
```

**理由:**
- レスポンシブ対応が困難
- 要素の追加/削除時に座標再計算が必要
- アクセシビリティに問題

#### 2. テキストコンテンツ

```scss
// ❌ 禁止
.title {
  position: absolute;
  left: r(601);
  top: r(166);
}

// ✅ 正解
.content {
  display: flex;
  flex-direction: column;
  align-items: center;
}
```

**理由:**
- 可読性・保守性が低い
- レスポンシブ対応が困難
- コンテンツ量が変わると崩れる

#### 3. ボタン・リンク

```scss
// ❌ 禁止
.button {
  position: absolute;
  left: r(519);
  top: r(571);
}

// ✅ 正解
.content {
  display: flex;
  flex-direction: column;
  gap: r(40);
  align-items: center;
}
```

**理由:**
- ユーザーインタラクション要素は自然なフローに配置すべき
- タップ領域の確保が困難

#### 4. フォーム

```scss
// ❌ 禁止
.form-input {
  position: absolute;
  left: r(100);
  top: r(200);
}

// ✅ 正解
.form {
  display: flex;
  flex-direction: column;
  gap: r(20);
}
```

**理由:**
- アクセシビリティ（スクリーンリーダー対応）
- フォーカス順序の保証
- バリデーション表示の配置

---

## レイアウト判定チャート

```
要素タイプを確認
│
├─ 背景・オーバーレイ・装飾？
│  └─ YES → position: absolute OK
│
├─ 水平に並んでいる？
│  └─ YES → display: flex; flex-direction: row; gap: X
│
├─ 垂直に並んでいる？
│  └─ YES → display: flex; flex-direction: column; gap: X
│
├─ グリッド状に配置？
│  └─ YES → display: grid; grid-template-columns: X
│
├─ 中央寄せ？
│  └─ YES → margin-inline: auto または align-items: center
│
└─ その他
   └─ 再度パターンを検討（通常はflexboxで対応可能）
```

---

## Gap値の計算方法

### 水平方向のgap

```
gap = 次の要素のleft - (現在の要素のleft + width)
```

**例:**
```
要素A: left: 22px, width: 45.1px
要素B: left: 120px

gap = 120 - (22 + 45.1) = 52.9px
```

### 垂直方向のgap

```
gap = 次の要素のtop - (現在の要素のtop + height)
```

**例:**
```
要素A: top: 321px, height: 67.6px
要素B: top: 469px

gap = 469 - (321 + 67.6) = 80.4px
```

### gap値の平均化

複数の要素間でgapが異なる場合、平均値を使用するか、デザイン意図を考慮:

```
gaps = [52.9, 30.5, 40.2, 38.1]
averageGap = (52.9 + 30.5 + 40.2 + 38.1) / 4 = 40.4px

→ gap: r(40) を使用（四捨五入）
```

---

## エージェント別の責務

### layout-converter

- 座標データからパターンを識別
- gap値を計算
- 推奨レイアウト方式を提案（`flexbox-row`, `grid` など）
- **出力しない:** 具体的なCSSプロパティ、HTML構造

### html-structure

- パターンに基づいてセマンティックHTML生成
- BEM命名規則に従う
- アクセシビリティ対応（ARIA属性）
- **参照:** このRULES_LAYOUT.mdの変換パターン

### sass-flocss

- パターンに基づいてSCSS生成
- FLOCSS構造に従う
- 推奨レイアウトをCSSで実装
- gap値をr()関数で適用
- **参照:** このRULES_LAYOUT.mdの変換パターン

---

## 検証チェックリスト

コード生成後、以下を確認:

- [ ] ナビゲーション・コンテンツにabsoluteを使っていないか
- [ ] gap値が座標データから計算されているか
- [ ] flexbox/gridを優先しているか
- [ ] absoluteは背景・オーバーレイ・装飾のみか
- [ ] フォントサイズ・色・テキストがMCPデータと一致しているか
- [ ] レスポンシブ対応（mq()ミックスイン）があるか
- [ ] BEM命名規則に従っているか
- [ ] アクセシビリティ対応があるか

---

## まとめ

**絶対に守るべきこと:**

1. ✅ **値は保持** - フォント、色、テキスト、画像URL
2. ✅ **配置方法を変換** - 座標 → gap/margin/padding
3. ✅ **Flexbox/Grid優先** - モダンなレイアウト手法
4. ✅ **Absolute最小化** - 背景・オーバーレイ・装飾のみ
5. ✅ **gap値を計算** - 座標差分から数学的に算出
6. ✅ **レスポンシブ対応** - mq()ミックスインで適応
7. ✅ **アクセシビリティ** - セマンティックHTML、ARIA属性

**このルールを守ることで:**
- 保守性の高いコード
- レスポンシブ対応が容易
- アクセシビリティ確保
- デザイン値の正確性
- チーム開発の一貫性
