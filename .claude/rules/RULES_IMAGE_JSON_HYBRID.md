# 画像+JSON ハイブリッドコーディングルール

## 目的

Figma MCP から取得したデータは以下の特性を持つ:

- **JSON (design context)**: テキスト・色・フォントは正確だが、座標ベース（position: absolute 問題）
- **Screenshot**: レイアウトは視覚的に正確だが、文字・色情報が不正確

**両者の長所を組み合わせ、短所を補完する**ことで、保守性・レスポンシブ性・アクセシビリティに優れたコードを生成する。

---

## 使い分けマトリクス

### 基本ルール

| 情報                   | 使用データ    | 取得方法               | 理由                            |
| ---------------------- | ------------- | ---------------------- | ------------------------------- |
| **レイアウト構造**     | 📷 Screenshot | 視覚的判断             | flexbox/grid が自然に判断できる |
| **要素の配置**         | 📷 Screenshot | 視覚的判断             | 縦並び/横並び/重なりが明確      |
| **間隔（gap/margin）** | 📷 Screenshot | 視覚測定               | 視覚的な距離感が正確            |
| **テキスト内容**       | 📄 JSON       | extractedValues.texts  | OCR より正確                    |
| **色値**               | 📄 JSON       | extractedValues.colors | HEX/RGB が正確                  |
| **フォント情報**       | 📄 JSON       | extractedValues.fonts  | サイズ/ウェイトが正確           |
| **画像 URL**           | 📄 JSON       | extractedValues.images | アセットパスが正確              |

### Auto Layout 検出時の優先ルール

**JSON に Auto Layout 情報が含まれる場合は、レイアウト・配置も JSON を優先**

| 検出条件         | 情報               | 使用データ    | 取得方法                        | 理由                        |
| ---------------- | ------------------ | ------------- | ------------------------------- | --------------------------- |
| Auto Layout あり | **レイアウト構造** | 📄 JSON       | layoutMode, direction           | Figma の Auto Layout が正確 |
| Auto Layout あり | **要素の配置**     | 📄 JSON       | direction (horizontal/vertical) | 設計者の意図が明確          |
| Auto Layout あり | **間隔値**         | 📄 JSON       | itemSpacing, paddingTop など    | 正確な数値が取得可能        |
| Auto Layout なし | すべて             | 📷 Screenshot | 視覚的判断                      | 基本ルールに従う            |

**判断方法**: JSON に `layoutMode`, `direction`, `itemSpacing` などのプロパティが存在するかチェック

---

## 実装パターン

### パターン A: Auto Layout 対応（JSON 優先）

**適用条件**: JSON に `layoutMode`, `direction`, `itemSpacing` などが存在する場合

- **JSON から**: レイアウト方向（`direction: "HORIZONTAL"/"VERTICAL"`）、間隔（`itemSpacing`）、パディング値を取得
- **スクショから**: 全体的な視覚バランス、ネストした要素の関係性を確認
- **実装**: JSON の Auto Layout 情報を CSS の flexbox/grid に変換
  - `direction: "HORIZONTAL"` → `flex-direction: row`
  - `direction: "VERTICAL"` → `flex-direction: column`
  - `itemSpacing: 16` → `gap: r(16)`

### パターン 1: 水平配置要素（ナビゲーション型）

- **スクショから**: 横並び配置を確認 → `display: flex` + `gap` + `align-items`で実装
- **JSON から**: テキスト内容、色値、フォント情報を取得
- **HTML 構造**: 視覚的な横並びを `<ul><li>` 構造で実装、テキストは JSON から取得

---

### パターン 2: グリッドレイアウト（繰り返し要素型）

- **スクショから**: グリッド配置と列数を確認 → `display: grid` + `grid-template-columns` + `gap`
- **JSON から**: 各カード内のテキスト、色値、フォント情報を取得
- **HTML 構造**: 視覚的なグリッドを適切なセマンティック要素で実装

---

### パターン 3: 中央配置（垂直配置型）

- **スクショから**: 縦並び・中央配置を確認 → `flex` + `column` + `center` + `gap`
- **JSON から**: テキスト内容、色値、フォント情報を取得
- **追加判断**: テキストの中央揃えもスクショから確認

---

### パターン 4: 背景・装飾要素（position: absolute が許可される例）

- **スクショから**: 背景の配置を確認 → `position: relative/absolute` で実装
- **JSON から**: 背景画像 URL、透明度などの装飾プロパティを取得
- **注意**: これは背景・装飾要素のみに限定、コンテンツ要素は flex/grid で実装

---

## 禁止パターン

### ❌ やってはいけないこと

1. **JSON の座標値使用**: `left: 120px; top: 340px;` などの絶対配置
2. **スクショからの色推測**: 見た目で色を推測して `color: #333;` など
3. **推測での要素追加**: JSON に存在しないテキストやボタンを勝手に追加

---

## ✅ 正しいアプローチ

**基本原則**: スクショで構造・配置を判断 + JSON で具体的な内容・スタイル値を取得

- **HTML 構造**: スクショの視覚的配置から適切なセマンティック要素を選択、テキスト内容は JSON から取得
- **CSS 実装**: レイアウト（flex/grid）と間隔はスクショから、色・フォント・テキストは JSON から
- **レスポンシブ**: デスクトップ表示を基準に、モバイル向けに配置方向を調整

---

## gap 値の視覚測定ガイドライン

スクショから間隔を測定する際の目安:

- **密接**: `gap: r(8)` - ほぼくっついている
- **近い**: `gap: r(16)` - 少し余白
- **通常**: `gap: r(24)` - 標準的な余白
- **広い**: `gap: r(40)` - 明確な分離
- **非常に広い**: `gap: r(64)` - セクション間レベル

**注**: デザイントークンで gap 値が定義されている場合はそちらを優先。

---

## レスポンシブ対応

スクショは通常デスクトップサイズのため:

- **基本**: デスクトップレイアウトをスクショから判断
- **モバイル調整**: 横並び → 縦並び、間隔値調整、必要に応じてフォントサイズ調整

---

## position: absolute が許可される例外

以下の要素のみ `position: absolute` の使用を許可:

1. **背景画像**: セクション背後の装飾的な画像
2. **装飾要素**: ドット、線、図形などの視覚的装飾
3. **オーバーレイ**: モーダル、ツールチップなど
4. **バッジ**: "NEW"、"SALE" などの小さなラベル

**それ以外のコンテンツ要素はすべて flexbox/grid で実装**

---

## 検証チェックリスト

各エージェントは生成後に確認:

### 事前確認

- [ ] JSON に Auto Layout 情報（`layoutMode`, `direction`, `itemSpacing`）が存在するか確認したか
- [ ] Auto Layout の有無に応じて適切な判断基準を選択したか

### HTML 生成時

- [ ] レイアウト構造（flex/grid）を適切なデータソース（Auto Layout ありなら JSON、なしなら Screenshot）から判断したか
- [ ] テキスト内容を JSON から取得したか
- [ ] 推測で要素を追加していないか
- [ ] すべてのテキストが extractedValues に存在するか

### SCSS 生成時

- [ ] レイアウト構造を適切なデータソース（Auto Layout ありなら JSON、なしなら Screenshot）から判断したか
- [ ] gap/margin 値を適切なデータソース（Auto Layout ありなら JSON の itemSpacing、なしなら Screenshot 測定）から取得したか
- [ ] 色値を JSON から取得したか
- [ ] フォント情報を JSON から取得したか
- [ ] JSON の座標値（left/top）を使用していないか（Auto Layout の場合も座標は使わない）
- [ ] `position: absolute` を最小限に抑えたか（背景・装飾のみ）
- [ ] すべての px 値を `r()` 関数で変換したか

---

## まとめ

### 基本ルール（Auto Layout なし）

| 作業               | Screenshot | JSON |
| ------------------ | ---------- | ---- |
| レイアウト構造決定 | ✅         | ❌   |
| gap/margin 値測定  | ✅         | ❌   |
| テキスト取得       | ❌         | ✅   |
| 色値取得           | ❌         | ✅   |
| フォント情報取得   | ❌         | ✅   |
| 画像 URL 取得      | ❌         | ✅   |

### Auto Layout 対応（layoutMode, direction, itemSpacing 等が存在）

| 作業               | Screenshot | JSON |
| ------------------ | ---------- | ---- |
| レイアウト構造決定 | △（補助）  | ✅   |
| gap/margin 値測定  | △（補助）  | ✅   |
| テキスト取得       | ❌         | ✅   |
| 色値取得           | ❌         | ✅   |
| フォント情報取得   | ❌         | ✅   |
| 画像 URL 取得      | ❌         | ✅   |

**両方のデータを活用することで、正確かつメンテナンス性の高いコードを生成できる。**
**Auto Layout 情報がある場合は、Figma の設計意図を尊重して JSON データを優先する。**
