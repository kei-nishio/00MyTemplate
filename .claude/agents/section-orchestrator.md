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

### layout-converter への指示

```
【入力ファイル】
- pages/{pageId}/section-XX.json

【処理内容】
- 座標データを機械的に分析
- position absolute → flexbox/grid 変換情報を生成

【出力ファイル】
- pages/{pageId}/section-XX-layout.json

【重要原則】
- 推測禁止: MCPデータに存在する要素のみを対象
- 要素追加禁止: 新しい要素を追加しない
- 機械的判定のみ: 座標値から数学的に計算
```

### html-structure / sass-flocss への指示

各エージェントに以下のファイルパスを渡す:

**入力:**
- `pages/{pageId}/page-info.json` - ページ情報と出力パス
- `pages/{pageId}/section-XX.json` - セクションデータ（extractedValues）
- `pages/{pageId}/section-XX-layout.json` - レイアウト変換情報
- `.claude/progress/design-manifest.json` - ビルドモードとグローバル設定

**出力:** page-info.jsonから取得（outputEjs, outputScss）

**注**: 詳細な実装ルールは各エージェントファイル（html-structure.md, sass-flocss.md）と .claude/rules/RULES_LAYOUT.md を参照

### Phase 4: セクション順次処理

page-info.jsonの `sections` 配列をループ処理:

```
sections.forEach(section => {
  1. layout-converter を起動
     入力: pages/{pageId}/section-XX.json
     出力: pages/{pageId}/section-XX-layout.json

  2. html-structure を起動
     入力: section-XX.json + section-XX-layout.json + page-info.json
     出力: src/ejs/project/_p-xxx.ejs (またはWP/静的HTML)

  3. sass-flocss を起動
     入力: section-XX.json + section-XX-layout.json
     出力: src/sass/object/project/_p-xxx.scss

  4. js-component を起動（必要時）
     入力: section-XX.json + section-XX-layout.json
     出力: src/assets/js/script.js に追加

  5. 進捗更新（section-XX.json の status を "completed" に）
  6. コンテキストクリア
})
```

**重要**: layout-converterは position absolute 問題を解決するための専任エージェント

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
