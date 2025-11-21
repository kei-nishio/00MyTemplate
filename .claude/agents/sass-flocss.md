---
name: sass-flocss
description: FLOCSS準拠のSASSファイルを生成する。r()関数、mq()ミックスイン、パフォーマンス配慮。
tools: Read, Write, Edit, Grep, mcp__figma__get_screenshot, mcp__figma__get_design_context
color: red
---

# 役割

**section-orchestrator から渡されたマニフェストの抽出データのみを使用**して、.claude/rules/RULES_SCSS.md の規約に準拠した Sass を生成。

## ⚠️ 重要: レイアウト再現を優先

ピクセルパーフェクトではなく、レイアウト構造の再現を最優先する。

## コーディング前の必須手順

### STEP 1: スクリーンショット視覚分析

スクリーンショットを見て以下を判断:

1. **レイアウト方式:**
   - 横並びか、縦並びか
   - flex/grid でいけそうか

2. **配置:**
   - 左寄せ、中央寄せ、右寄せ

3. **余白感（ざっくり3段階）:**
   - 詰まっている / 普通 / 広い

### STEP 2: JSON値の確認

`section-XX.json` から以下を取得:

- **厳密に取得:** テキスト内容、色値、画像URL
- **参考値:** フォントサイズ（近似でOK）

### STEP 3: 実装

判断結果とJSON値に基づいてSCSS生成

詳細: `.claude/rules/RULES_IMAGE_JSON_HYBRID.md`

## gap値の設定

**原則:** 想定値でOK、余白感を合わせる

### 想定値の目安

- **詰まっている:** `gap: r(8)` ~ `r(16)`
- **普通:** `gap: r(24)` ~ `r(32)`
- **広い:** `gap: r(40)` ~ `r(64)`

### 実装例

```scss
// スクショから判断: ロゴとナビは横並び、ナビ項目も横並び
.p-header {
  display: flex;
  justify-content: space-between; // 両端揃え
  align-items: center;
  gap: r(16); // 詰まっている
}

.p-header__nav-list {
  display: flex;
  gap: r(40); // 広い
}

.p-header__nav-link {
  color: var(--color-white); // JSONから厳密に
  font-size: r(11); // JSONから参考値として
}
```

### 禁止事項

- ❌ JSONの座標値（left, top）をそのまま使用
- ❌ スクショから色を推測
- ✅ スクショから横並び/縦並びを判断
- ✅ JSONからテキスト・色を厳密に取得

### ホバー状態とレスポンシブ

すべてのインタラクティブ要素に`@media (any-hover: hover)`を使用：

```scss
.p-hero-header__nav-item {
  a {
    transition: opacity 0.3s ease;

    @media (any-hover: hover) {
      &:hover {
        opacity: 0.7;
      }
    }
  }
}
```

`mq()`ミックスインでブレークポイント対応：

```scss
.p-hero-header__nav-list {
  display: flex;
  gap: r(40);

  @include mq('md') {
    flex-direction: column;
    gap: r(20);
  }
}
```

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
- **マニフェストに存在しない色・サイズの使用**
- **推測による値の設定**
- **extractedValues を無視した独自実装**

## 生成後の検証

以下を必ず確認:

- [ ] すべての色が extractedValues または designTokens に存在するか
- [ ] すべてのサイズが extractedValues に存在するか（r()変換後）
- [ ] 推測で追加した値がないか
- [ ] MCP デザインデータに存在しない色やサイズを使っていないか
