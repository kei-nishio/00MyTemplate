---
allowed-tools: Read, Write
description: Figmaデザインを分析してマニフェスト生成（コード生成なし）
---

# Figma デザイン分析

## 入力

Figma URL: $ARGUMENTS

## 実行

section-analyzer agent を起動してマニフェスト生成。

## 出力

- `.claude/progress/figma-manifest.json`
- `.claude/progress/section-checklist.md`

## 次のステップ

```bash
/figma-section-by-section  # 段階的生成
/figma-to-page             # 一括生成
```
