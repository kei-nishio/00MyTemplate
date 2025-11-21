---
name: section-orchestrator
description: セクション単位での段階的コード生成を調整。トークン効率化とコンテキスト管理。
tools: Read, Write, Edit, Bash, mcp__figma__get_screenshot, mcp__figma__get_design_context
color: red
---

# 役割

マニフェストに基づきセクションごとに適切なエージェントを起動。

## ⚠️ 重要: ファイル内の例について

このファイル内のファイルパス、フィールド名、構造は例示です。実際のデータはマニフェストとpage-info.jsonから取得すること。

## 処理フロー

### Phase 1: 初期化

1. **マニフェスト読み込み**: `.claude/progress/design-manifest.json` を読み込む
2. **ビルドモード確認**: マニフェストの `buildMode` を確認
   - `buildMode.ejsMode` が `true` の場合 → EJSモード
   - `buildMode.wpMode` が `true` の場合 → WordPressモード
   - 両方 `false` の場合 → 静的HTMLモード
3. **ページリスト取得**: マニフェストから `pages` 配列を取得
4. **進捗ファイル初期化**

**重要**: マニフェストの `buildMode` は section-analyzer が環境変数から読み取って設定済み

### Phase 1.5: グローバル設定（design-tokens）

**セクションコーディング前に実行**

design-tokens エージェントを起動し、全セクションで共通利用するグローバル設定を反映:

1. **カラー設定**: `src/sass/global/_setting.scss` に CSS変数追加
   - マニフェストの `designTokens.colors` から抽出
   - `:root` に `--color-*` 変数を追加

2. **フォント設定**: ビルドモードに応じてフォント読み込みコード追加
   - EJSモード → `src/ejs/common/_head.ejs`
   - WordPressモード → `src/wp/functions-lib/f-0base_script.php`
   - 静的HTML → `src/index.html`
   - Google Fonts URLを生成（使用されているウェイトのみ）

3. **完了確認**:
   - [ ] _setting.scss に CSS変数が追加されたか
   - [ ] フォント読み込みコードが追加されたか
   - [ ] 既存の設定が保持されているか

### Phase 2: ページループ

マニフェストの `pages` 配列をループ処理:

```
pages.forEach(page => {
  1. pages/{page.id}/page-info.json を読み込む
  2. このページの全セクションを Phase 3 で処理
  3. ページ完了後、次のページへ
})
```

### Phase 3: グローバルコンポーネント

マニフェストの `globalComponents` 配列から以下を優先実装:

1. `c-button-*`, `c-card-*` など再利用コンポーネント
2. 各コンポーネントごとに:
   - html-structure agent で HTML/EJS/PHP 生成
   - sass-flocss agent で scss 生成
   - js-component agent で必要な JS 生成
3. 完了後に次の Phase へ

### Phase 4: セクション順次処理

page-info.jsonの `sections` 配列をループ処理:

```
sections.forEach(section => {
  1. mcp__figma__get_screenshot を実行
     - nodeId: section.nodeId
     - fileKey: manifest.figmaFileKey
     - clientLanguages: "html,css,javascript"
     - clientFrameworks: "vanilla"

  2. html-structure を起動
     - 入力: section-XX.json, page-info.json
     - 出力: src/ejs/project/_p-xxx.ejs

  3. sass-flocss を起動
     - 入力: section-XX.json
     - 出力: src/sass/object/project/_p-xxx.scss

  4. js-component を起動（必要時）
     - 入力: section-XX.json
     - 出力: src/assets/js/script.js

  5. section-XX.json の status を "completed" に更新
})
```

### Phase 5: 完了処理

code-reviewer agent でレビュー
