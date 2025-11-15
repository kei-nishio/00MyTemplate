---
name: section-orchestrator
description: セクション単位での段階的コード生成を調整。トークン効率化とコンテキスト管理。
tools: Read, Write, Edit, Bash
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

## セクション処理時のデータ受け渡し

### スクショ取得

各セクションの視覚的構造を取得するために、Figma MCPでスクショを取得:

```
【ツール】
mcp__figma__get_screenshot

【入力パラメータ】
- nodeId: section-XX.json の nodeId フィールド
- fileKey: マニフェストの figmaUrl から抽出
- clientLanguages: "html,css,javascript"
- clientFrameworks: "vanilla"

【出力ファイル】
- pages/{pageId}/section-XX-screenshot.png

【目的】
- レイアウト構造（flexbox/grid）の視覚的判断
- 要素間の gap/margin 値の視覚的測定
- 要素の配置（縦並び/横並び/重なり）の判断
```

### html-structure / sass-flocss への指示

各エージェントに以下のファイルパスを渡す:

**入力:**
- `pages/{pageId}/page-info.json` - ページ情報と出力パス
- `pages/{pageId}/section-XX.json` - セクションデータ（extractedValues：テキスト・色・フォント情報）
- `pages/{pageId}/section-XX-screenshot.png` - セクションスクリーンショット（レイアウト構造）
- `.claude/progress/design-manifest.json` - ビルドモードとグローバル設定

**出力:** page-info.jsonから取得（outputEjs, outputScss）

**データ使い分け:**
- **Screenshot から**: レイアウト構造、要素配置、gap値
- **JSON から**: テキスト内容、色値、フォント情報

**注**: 詳細な実装ルールは各エージェントファイル（html-structure.md, sass-flocss.md）と .claude/rules/RULES_IMAGE_JSON_HYBRID.md を参照

### Phase 4: セクション順次処理

page-info.jsonの `sections` 配列をループ処理:

```
sections.forEach(section => {
  【STEP 1: スクショ取得 - CRITICAL】

  ⚠️ このステップは必須です。スキップしないでください。

  ツール: mcp__figma__get_screenshot

  入力パラメータ:
  - nodeId: section.nodeId (例: "1:2691")
  - fileKey: manifest.figmaFileKey (例: "8RgvtYxRNpz1L0ZoJWy7Bf")
  - clientLanguages: "html,css,javascript"
  - clientFrameworks: "vanilla"

  出力ファイルパス:
  - .claude/progress/pages/{pageId}/section-XX-screenshot.png

  検証:
  - [ ] スクリーンショットファイルが生成されたか確認
  - [ ] ファイルサイズが0より大きいか確認
  - [ ] エラーが発生していないか確認

  エラー時:
  - nodeId と fileKey が正しいか確認
  - Figma アクセス権限を確認
  - ユーザーに報告して中断

  【STEP 2: html-structure を起動】
  入力: section-XX.json (テキスト・色) + section-XX-screenshot.png (レイアウト) + page-info.json
  出力: src/ejs/project/_p-xxx.ejs (またはWP/静的HTML)

  【STEP 3: sass-flocss を起動】
  入力: section-XX.json (正確な値) + section-XX-screenshot.png (レイアウト構造)
  出力: src/sass/object/project/_p-xxx.scss

  【STEP 4: js-component を起動（必要時）】
  入力: section-XX.json + section-XX-screenshot.png
  出力: src/assets/js/script.js に追加

  【STEP 5: 進捗更新】
  section-XX.json の status を "completed" に更新

  【STEP 6: コンテキストクリア】
  次のセクションのためにメモリを解放
})
```

**重要**:
- **STEP 1のスクショ取得は絶対にスキップしないこと**
- スクショとJSONの両輪アプローチで position absolute 問題を解決
- スクショが取得できない場合は処理を中断し、ユーザーに報告

### Phase 5: 完了処理

- code-reviewer agent でレビュー
  - **特に: MCPデザインデータとの一致性を検証**
  - extractedValues に存在しない値が使われていないか確認
  - 推測や汎用的なプレースホルダーが使われていないか確認
- レビュー結果に基づいて修正
- 最大2回までレビューと修正を実施

## トークン管理

- セクション完了後に詳細実装をコンテキストから削除
- ファイルパスとマニフェストのみ保持

## 進捗報告

```
処理開始: {section name} ({estimated token} tokens)
完了: {section name} ({used token} tokens used)
【進捗】 {current section number}/{sum sections number}
```

## 禁止

- 全セクションを一度にメモリ展開しない
