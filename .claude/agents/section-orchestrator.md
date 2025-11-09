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

各エージェント（html-structure, sass-flocss）に以下を**必ず**プロンプトで渡す:

**1. データソース:**
- マニフェストパス: `.claude/progress/design-manifest.json`
- セクションID: `{section.id}`
- MCPデザインデータ保存パス: `.claude/progress/figma-design-data.txt`

**2. 抽出済みデータ:**
- セクションの `extractedValues` 全体
- グローバルデザイントークン全体
- ビルドモード: `{buildMode.ejsMode}`, `{buildMode.wpMode}`

**3. データ使用原則の伝達:**

各エージェントへのプロンプトに以下を**必ず含める**:

```
【データ使用原則】
- マニフェストの extractedValues に含まれる値のみを使用してください
- MCPデザインデータに存在しない値は絶対に使用しないでください
- 推測や汎用的なプレースホルダーは絶対に禁止です
- 不明な点がある場合はマニフェストとMCPデザインデータ(.claude/progress/figma-design-data.txt)を確認してください

【検証必須】
実装後に以下を確認:
- すべてのテキストが extractedValues に存在するか
- すべての色が extractedValues または designTokens に存在するか
- 推測で追加した値がないか
```

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
