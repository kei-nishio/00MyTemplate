---
allowed-tools: Read, Write, Edit, Bash
description: Figma URLから完全ページを一括生成（小規模デザイン向け）
---

# Figma→ 完全ページ一括生成

## 入力

Figma URL: $ARGUMENTS

## 実行

1. section-analyzer で分析
2. 総トークンが 30,000 以下なら一括生成
3. 超える場合は `/figma-section-by-section` を推奨

## フロー

- グローバルコンポーネント
- 全セクション一括生成
- レビュー＆ビルド
