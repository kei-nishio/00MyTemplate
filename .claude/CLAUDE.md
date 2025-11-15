# MCP グローバルルール

このファイルは、Figma MCP もしくは Zeplin MCP を使用する全プロジェクトで共通のルールを定義します。

## 自動ワークフローのトリガー

**Event-driven 要件:**

When ユーザーが以下のパターンでメッセージを送信した場合、システムは自動的にワークフローを開始すること：

**トリガーパターン:**
- Figma URL: `https://www.figma.com/design/*` または `https://figma.com/file/*`
- Zeplin URL: `https://app.zeplin.io/*`
- キーワード: "implement", "build", "create", "generate", "code from", "実装", "作成", "コーディング"
- コンテキスト: "design", "figma", "zeplin", "page", "デザイン", "ページ"

## 自動実行フロー

**実行シーケンス:**

### 1. デザインデータ取得

When Figma/Zeplin URL を検出した場合、システムは MCP 経由でデザインデータを取得すること：
- `mcp__figma__get_screenshot` でスクリーンショットを取得
- `mcp__figma__get_design_context` でデザインコンテキストを取得
- パラメータ: `clientLanguages="html,css,javascript"`, `clientFrameworks="vanilla"`
- プロンプト: "Please generate in plain HTML + CSS format (not React)"

### 2. section-analyzer 起動

システムは section-analyzer を起動し、以下を実行すること：
- `environments/.env.local` から `EJS_MODE` と `WP_MODE` を読み取る
- MCPデザインデータを解析し、ページ構造を識別
- 複数ページの場合、ページごとにディレクトリを分割
- 各ページ内をセクション単位に分割
- MCPデザインデータから抽出したすべての値を保存
- ビルドモードをマニフェストに記録

**出力ファイル:**
- `.claude/progress/design-manifest.json` - プロジェクト全体総括
- `.claude/progress/figma-design-data.txt` - MCPデータ全体
- `.claude/progress/pages/{pageId}/page-info.json` - ページメタ情報
- `.claude/progress/pages/{pageId}/section-XX.json` - セクション詳細（nodeId含む）

### 3. design-tokens 起動

システムは design-tokens を起動し、グローバル設定を反映すること：

**カラー設定:**
- マニフェストから `designTokens.colors` を読み込む
- `src/sass/global/_setting.scss` に CSS変数を追加

**フォント設定:**
When ビルドモードに応じて、以下のファイルにフォント読み込みコードを追加すること：
- EJSモード → `src/ejs/common/_head.ejs`
- WordPressモード → `src/wp/functions-lib/f-0base_script.php`
- 静的HTMLモード → `src/index.html`

### 4. section-orchestrator 起動

システムは section-orchestrator を起動し、ページ/セクションごとにコーディングを実行すること：

**ページループ:**
- マニフェストの `pages` 配列をループ処理

**セクションループ（各ページ内）:**

#### 4.1 スクショ取得【CRITICAL - 必須ステップ】

⚠️ **このステップは絶対にスキップしないこと**

システムは各セクションのスクリーンショットを取得すること：

- **ツール**: `mcp__figma__get_screenshot`
- **入力パラメータ**:
  - `nodeId`: `section-XX.json` の `nodeId` フィールド（例: "1:2691"）
  - `fileKey`: マニフェストの `figmaFileKey` フィールド（例: "8RgvtYxRNpz1L0ZoJWy7Bf"）
  - `clientLanguages`: "html,css,javascript"
  - `clientFrameworks`: "vanilla"
- **出力**: `.claude/progress/pages/{pageId}/section-XX-screenshot.png`

**検証要件**:
- [ ] スクリーンショットファイルが生成されたか
- [ ] ファイルサイズが0バイトより大きいか
- [ ] エラーが発生していないか

**エラー時の対応**:
When スクショ取得に失敗した場合、システムは以下を実行すること：
- nodeId と fileKey が正しいか確認
- Figma アクセス権限を確認
- ユーザーに報告して処理を中断

#### 4.2 html-structure 起動
- 入力: `section-XX.json` (テキスト・色情報) + `section-XX-screenshot.png` (レイアウト)
- Screenshot から: 要素の配置、間隔、視覚的階層を判断
- JSON から: テキスト内容、色値、フォント情報を取得
- 初期ファイルのサンプルセクションを削除

#### 4.3 sass-flocss 起動
- 入力: `section-XX.json` (正確な値) + `section-XX-screenshot.png` (レイアウト構造)
- Screenshot から: flexbox/grid構造、gap値を視覚的に判断
- JSON から: 正確な色値、フォントサイズを取得

#### 4.4 js-component 起動（必要時）

#### 4.5 code-reviewer 起動
- 品質チェックと修正を実施

### 5. 完了

When すべてのページ・セクションのコーディングが完了した場合、システムは処理を終了すること。

## データ使用原則

**Ubiquitous 要件:**

すべてのエージェント（section-analyzer, design-tokens, section-orchestrator, html-structure, sass-flocss, js-component, code-reviewer）は以下を遵守すること：

### データソース優先順位

1. **最優先**: `mcp__figma__get_design_context` で取得したMCPデザインデータ
2. **次点**: section-analyzer が生成した `.claude/progress/design-manifest.json`

### 必須動作

システムは以下を実行すること：
- MCPデザインデータから抽出した値のみを使用
- MCPデザインデータに記載されているすべての値を使用（色、テキスト、サイズ、スペーシング、フォント等）

**Unwanted Behavior（禁止動作）:**

システムは以下を実行してはならない：
- MCPデザインデータに存在しない値の使用
- 推測による値の設定
- 汎用的なプレースホルダーテキストの使用（例: "Welcome to Our Site", "Learn More"）
- マニフェストの `extractedValues` を無視した独自実装

## 画像+JSONハイブリッドアプローチ

**原則:**

MCPデザインデータは以下の特性を持つ：
- **JSON (design context)**: テキスト・色・フォントは正確だが、座標ベース（position: absolute問題）
- **Screenshot**: レイアウトは視覚的に正確だが、文字・色情報が不正確

システムは両者の長所を組み合わせ、保守性・レスポンシブ性・アクセシビリティに優れたコードを生成すること。

**詳細: `.claude/rules/RULES_IMAGE_JSON_HYBRID.md`**

### 使い分けルール

| 判断項目 | 使用データ | 理由 |
|---------|-----------|------|
| レイアウト構造 | 📷 Screenshot | flexbox/gridが視覚的に判断可能 |
| 要素の配置 | 📷 Screenshot | 縦並び/横並び/重なりが明確 |
| 間隔（gap/margin） | 📷 Screenshot | 視覚的な距離感が正確 |
| テキスト内容 | 📄 JSON | OCRより正確 |
| 色値 | 📄 JSON | HEX/RGBが正確 |
| フォント情報 | 📄 JSON | サイズ/ウェイトが正確 |
| 画像URL | 📄 JSON | アセットパスが正確 |

### 禁止動作

システムは以下を実行してはならない：
- JSONの座標値（left, top）をそのまま使用
- `position: absolute` の多用（背景・装飾のみ許可）
- スクショから色・テキストを推測

### 許可動作

システムは以下を実行すること：
- スクショから視覚的にflexbox/gridを判断
- JSONから正確な値を取得

### 検証要件

各エージェントは生成後に以下を確認すること：
- [ ] すべての値がMCPデザインデータまたはマニフェストに存在する
- [ ] 推測や汎用値を使用していない
- [ ] MCPデザインデータと実装が一致している
- [ ] 初期ファイルのサンプルセクションが削除されている（html-structure のみ）
- [ ] マニフェストに定義されていないセクションが残っていない（html-structure のみ）

## エラーハンドリング

**Unwanted Behavior:**

### デザインデータ取得失敗時

When デザインデータ取得が失敗した場合、システムは以下を実行すること：
- ユーザーに Figma URL の確認を依頼
- アクセス権限の確認を促す

### ビルドエラー時

When ビルドエラーが3回連続で発生した場合、システムは以下を実行すること：
- エラー内容をレポートに記載
- ユーザーに手動修正を依頼して終了

## ユーザーインタラクション制約

**Unwanted Behavior:**

システムは以下の確認を実行してはならない：
- "Should I analyze the Figma design?"
- "Would you like me to use section-analyzer?"
- "Do you want me to start generating code?"
- "Figma を分析しますか？"

**正しい動作:**
URL 検出 → 即座にエージェント起動 → 自動処理開始

## エージェント一覧

| エージェント | 責務 |
|-------------|------|
| **section-analyzer** | Figma デザインを解析してページ/セクション分割、マニフェスト生成（nodeId記録） |
| **design-tokens** | マニフェストのdesignTokensをsrcディレクトリに反映（カラー・フォント等のグローバル設定） |
| **section-orchestrator** | ページ/セクション単位での段階的生成を管理（スクショ取得を含む） |
| **html-structure** | HTML/EJS/PHP の骨組みを生成（スクショ+JSON両輪アプローチ） |
| **sass-flocss** | FLOCSS 準拠のスタイル生成（スクショ+JSON両輪アプローチ） |
| **js-component** | UI 動作やイベント処理を生成 |
| **code-reviewer** | 自動品質チェックと修正提案 |
