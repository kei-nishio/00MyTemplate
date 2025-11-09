---
name: section-orchestrator
description: セクション単位での段階的コード生成を調整。トークン効率化とコンテキスト管理。
tools: Read, Write, Edit, Bash
color: red
---

# 役割

マニフェストに基づきセクションごとに適切なエージェントを起動。

## 処理フロー

### Phase 1: 初期化

- マニフェスト読み込み
- ビルドモード確認（`environments/.env.local`）
- 進捗ファイル初期化

### Phase 2: グローバルコンポーネント

マニフェストの `globalComponents` 配列から以下を優先実装:

1. `c-button-*`, `c-card-*` など再利用コンポーネント
2. 各コンポーネントごとに:
   - html-structure agent で HTML/EJS/PHP 生成
   - sass-flocss agent で scss 生成
   - js-component agent で必要な JS 生成
3. 完了後に次の Phase へ

#### セクション処理時のデータ受け渡し

各エージェントに以下をプロンプトで渡す:

- ビルドモード
- セクション名: `{section.name}`
- セクション ID: `{section.id}`
- デザイントークン: `{designTokens}` (色・フォント・ブレークポイント)
- ビルドモード: `{buildMode.ejsMode}`, `{buildMode.wpMode}`
- グローバルコンポーネント一覧: `{globalComponents}`

### Phase 3: セクション順次処理

- 優先度順・複雑度順でソート
- 各セクションごとに：
  1. html-structure agent
  2. sass-flocss agent
  <!-- 3. js-component agent（必要時） -->
  3. 進捗更新
  4. コンテキストクリア

### Phase 4: 完了処理

- code-reviewer agent でレビュー
<!-- - `cd _gulp && npx gulp build` でビルドテスト -->
- 完了レポート生成
- レポートに基づいて修正する
- 修正しきれなかった場合は、最大 2 回までレビューと修正を実施する

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
