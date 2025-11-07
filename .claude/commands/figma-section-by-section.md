---
allowed-tools: Read, Write, Edit, Bash
description: マニフェストに基づき段階的にページ生成
---

# セクション単位での段階的生成

## 前提

事前に `/figma-analyze` 実行済み。

## 実行

section-orchestrator agent を起動。

## フロー

1. グローバルコンポーネント生成
2. セクション順次処理
3. 3 セクションごとにユーザー確認
4. 完了後レビュー＆ビルド

## オプション

```bash
--auto-continue      # 確認なし自動継続
--start-from=section-03  # 指定セクションから開始
```
