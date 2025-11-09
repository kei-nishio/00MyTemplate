# MCP グローバルルール

このファイルは、Figma MCP もしくは Zeplin MCP を使用する全プロジェクトで共通のルールを定義します。

## 自動ワークフローのトリガー

ユーザーが以下のパターンでメッセージを送信した場合、**自動的に**ワークフローを開始する：

**トリガーパターン**

- Figma URL: `https://www.figma.com/design/*` または `https://figma.com/file/*`
- Zeplin URL: `https://app.zeplin.io/*`
- キーワード: "implement", "build", "create", "generate", "code from", "実装", "作成", "コーディング"
- コンテキスト: "design", "figma", "zeplin", "page", "デザイン", "ページ"

## 自動実行フロー

1. ユーザーメッセージから Figma/Zeplin URL を自動検出
2. MCP 経由でデザインデータ取得:
   - `mcp__figma__get_screenshot` でスクリーンショット取得
   - `mcp__figma__get_design_context` でデザインコンテキスト取得
   - clientLanguages="html,css,javascript" を指定
   - clientFrameworks="vanilla" を指定
3. section-analyzer を自動起動して取得したデザインデータのデザイン構造を分析し、マニフェストを生成する。
4. section-orchestrator を自動起動してセクションごとにコーディングをする。
5. 「4.」を「3.」で分析した全てのセクションで実行する
6. すべてのコーディングが完了したら終了する

## エラーハンドリング

### デザインデータ取得失敗

- ユーザーに Figma URL の確認を依頼
- アクセス権限の確認を促す

### ビルドエラー（3 回修正失敗）

- エラー内容をレポートに記載
- ユーザーに手動修正を依頼して終了

## 禁止事項

以下の確認は**絶対にしない**：

- "Should I analyze the Figma design?"
- "Would you like me to use section-analyzer?"
- "Do you want me to start generating code?"
- "Figma を分析しますか？"

## 正しい動作

URL 検出 → 即座にエージェント起動 → 自動処理開始

## エージェント一覧

- section-analyzer.md: Figma デザインを解析してマニフェスト生成
- section-orchestrator.md: セクション単位での段階的生成を管理
- html-structure.md:`src/index.html` ページの HTML 骨組みを生成
- sass-flocss.md: `src/sass/**/*.scss` FLOCSS 準拠のスタイル生成
- js-component.md: `src/assets/js/script.js` UI 動作やイベント処理を生成
- code-reviewer.md: 自動品質チェックと修正提案
