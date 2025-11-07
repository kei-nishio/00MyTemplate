# MCP グローバルルール

このファイルは、Figma MCP もしくは Zeplin MCP を使用する全プロジェクトで共通のルールを定義します。

## 構造

project-root/
├─ \_gulp/ # ビルドシステム（既存）
│ ├─ gulpfile.js
│ ├─ package.json
│ └─ node_modules/
│
├─ environments/ # 環境設定（既存）
│ ├─ .env.local
│ └─ .env.sample
│
├─ src/ # ソースファイル（既存）
│ ├─ sass/
│ ├─ assets/
│ ├─ ejs/
│ └─ wp/
│
├─ dist/ # ビルド出力（既存）
├─ distwp/ # WordPress 出力（既存）
│
├─ .claude/ # Claude Code 設定（新規追加）
│ ├─ agents/ # サブエージェント定義
│ │ ├─ section-analyzer.md # セクション分析専門
│ │ ├─ section-orchestrator.md # 段階的処理調整
│ │ ├─ html-structure.md # HTML 構造生成
│ │ ├─ sass-flocss.md # FLOCSS Sass 生成
│ │ ├─ js-component.md # Vanilla JS コンポーネント
│ │ ├─ ejs-converter.md # EJS テンプレート変換
│ │ ├─ wp-converter.md # WordPress PHP 変換
│ │ ├─ image-optimizer.md # 画像最適化アドバイザー
│ │ └─ code-reviewer.md # プロジェクト固有レビュー
│ │
│ ├─ commands/ # カスタムコマンド
│ │ ├─ figma-analyze.md # Figma デザイン分析
│ │ ├─ figma-to-page.md # Figma→ 完全ページ生成
│ │ ├─ figma-section-by-section.md # 段階的生成
│ │ ├─ resume-section.md # 処理再開
│ │ ├─ add-section.md # セクション追加
│ │ └─ build-check.md # ビルド前チェック
│ │
│ └─ progress/ # 進捗管理（自動生成）
│ ├─ .gitkeep
│ ├─ figma-manifest.json # セクション構造マニフェスト
│ ├─ section-checklist.md # チェックリスト
│ ├─ section-progress.md # 進捗メモ付き
│ ├─ checkpoint.json # 最新チェックポイント
│ ├─ checkpoint-section-\*.json # 各セクションの CP
│ └─ completion-report.md # 完了レポート
│
└─ CLAUDE.md # プロジェクトコンテキスト

## 基本方針

- BEM + FLOCSS 命名規則
- data-\*属性で JavaScript フック
- セマンティック HTML5
- レスポンシブ: Mobile-first
- アクセシビリティ配慮
- SEO 最適化

## 利用可能サブエージェント

- section-analyzer: Figma / Zeplin デザイン分析
- section-orchestrator: 段階的コード生成
- html-structure: HTML 構造生成
- sass-flocss: Sass 生成
- js-component: JavaScript 生成
- ejs-converter: EJS 変換
- wp-converter: WordPress 変換
- code-reviewer: コードレビュー

## 利用可能コマンド

- /figma-analyze: デザイン分析
- /figma-section-by-section: 段階的生成
- /figma-to-page: 一括生成
- /resume-section: 処理再開
- /add-section: セクション追加
- /build-check: ビルドチェック

## 役割

### `.claude/agents/` - サブエージェント定義（9 ファイル）

| ファイル名              | 役割            | 出力先 / 条件 / タイミング | 主な機能                                 |
| ----------------------- | --------------- | -------------------------- | ---------------------------------------- |
| section-analyzer.md     | セクション分析  | 分析時                     | Figma デザインを解析してマニフェスト生成 |
| section-orchestrator.md | 処理調整        | 生成フロー                 | セクション単位での段階的生成を管理       |
| html-structure.md       | HTML 構造生成   | `src/index.html`           | ページの HTML 骨組みを生成               |
| sass-flocss.md          | Sass 生成       | `src/sass/**/*.scss`       | FLOCSS 準拠のスタイル生成                |
| js-component.md         | JavaScript 生成 | `src/assets/js/script.js`  | UI 動作やイベント処理を生成              |
| ejs-converter.md        | EJS 変換        | `EJS_MODE=true`のとき      | HTML を EJS テンプレートへ変換           |
| wp-converter.md         | WordPress 変換  | `WP_MODE=true`のとき       | テンプレートを WP テーマ構造へ変換       |
| image-optimizer.md      | 画像最適化      | 画像処理時                 | 画像の圧縮・サイズ調整                   |
| code-reviewer.md        | コードレビュー  | 生成後〜ビルド前           | 自動品質チェックと修正提案               |

### `.claude/commands/` - カスタムコマンド（6 ファイル）

| ファイル名                  | コマンド                  | 用途                               | 引数         |
| --------------------------- | ------------------------- | ---------------------------------- | ------------ |
| figma-analyze.md            | /figma-analyze            | Figma デザイン分析のみ             | Figma URL    |
| figma-to-page.md            | /figma-to-page            | Figma → 完全ページ生成（自動一括） | Figma URL    |
| figma-section-by-section.md | /figma-section-by-section | 段階的なセクション生成             | オプション   |
| resume-section.md           | /resume-section           | 中断から再開                       | section-id   |
| add-section.md              | /add-section              | 既存ページにセクション追加         | セクション名 |
| build-check.md              | /build-check              | ビルド前の総合チェック             | なし         |

### `.claude/progress/` - 進捗管理（自動生成）

| ファイル名                 | 形式     | 生成タイミング     | 用途                     |
| -------------------------- | -------- | ------------------ | ------------------------ |
| figma-manifest.json        | JSON     | 分析時             | セクション構造の詳細     |
| section-checklist.md       | Markdown | 分析時             | 人間が読むチェックリスト |
| section-progress.md        | Markdown | 生成開始時         | 進捗メモ・作業記録       |
| checkpoint.json            | JSON     | セクション完了時   | 最新の全体進捗状態       |
| checkpoint-section-\*.json | JSON     | 各セクション完了時 | セクション別の詳細状態   |
| completion-report.md       | Markdown | 全完了時           | 最終レポート             |

---

## 自動ワークフロー（重要）

### Figma/Zeplin URL 検出時の自動実行

ユーザーが以下のパターンでメッセージを送信した場合、**自動的に**ワークフローを開始する：

#### トリガーパターン

- Figma URL: `https://www.figma.com/design/*` または `https://figma.com/file/*`
- Zeplin URL: `https://app.zeplin.io/*`
- キーワード: "implement", "build", "create", "generate", "code from", "実装", "作成", "コーディング"
- コンテキスト: "design", "figma", "zeplin", "page", "デザイン", "ページ"

#### 自動実行フロー

**Step 1: URL 検出**
ユーザーメッセージから Figma/Zeplin URL を自動検出

**Step 2: section-analyzer 自動起動**

- 確認なしで即座に起動
- デザイン構造を分析
- マニフェスト生成

**Step 3: 戦略判定**

- `推定トークン < 30,000` かつ `セクション数 ≤ 5` → 一括生成
- それ以外 → 段階的生成（3 セクションごとにユーザー確認）

**Step 4: section-orchestrator 自動起動**

- 判定された戦略で実行
- グローバルコンポーネント → セクション生成

**Step 5: 完了処理**

- code-reviewer でレビュー
- ビルドテスト実行
- 完了レポート

#### 禁止事項（プロアクティブに動作）

以下の確認は**絶対にしない**：

- "Should I analyze the Figma design?"
- "Would you like me to use section-analyzer?"
- "Do you want me to start generating code?"
- "Figma を分析しますか？"

#### 正しい動作

URL 検出 → 即座にエージェント起動 → 自動処理開始

#### 例：ユーザー入力
