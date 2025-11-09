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

- html-structure, sass-flocss, js-component を起動
- 全セクションで使用するコンポーネントを先に生成

### Phase 3: セクション順次処理

- 優先度順・複雑度順でソート
- 各セクションごとに：
  1. html-structure agent
  2. sass-flocss agent
  <!-- 3. js-component agent（必要時） -->
  4. 進捗更新
  5. コンテキストクリア

### Phase 4: 完了処理

- code-reviewer agent でレビュー
<!-- - `cd _gulp && npx gulp build` でビルドテスト -->
- 完了レポート生成
- レポートに基づいて修正する
- 修正しきれなかった場合は、最大2回までレビューと修正を実施する

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
