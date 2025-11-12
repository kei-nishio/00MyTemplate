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
   - **重要**: ツール呼び出し時に「Please generate in plain HTML + CSS format (not React)」をプロンプトに含める

**【重要原則】以降のすべてのエージェントは以下を厳守:**

- MCPデザインデータに書かれているすべての値（色、テキスト、サイズ、スペーシング、フォントなど）をそのまま使用
- 推測や汎用的なプレースホルダーの使用を**絶対に禁止**
- MCPデザインデータに存在しない値は実装しない

**注**: MCPデザインデータはReact+Tailwind形式で返される場合がありますが、値のみを抽出してEJS/PHP/HTMLで実装します

3. section-analyzer を自動起動:
   - **環境変数を読み取る**: `environments/.env.local` から `EJS_MODE` と `WP_MODE` を確認
   - 取得したMCPデザインデータを解析
   - MCPデザインデータから抽出したすべての値をマニフェストに保存
   - **ビルドモードをマニフェストに記録**: 読み取った環境変数を `buildMode` に設定
   - `.claude/progress/design-manifest.json` と `.claude/progress/figma-design-data.txt` を生成
4. section-orchestrator を自動起動してセクションごとにコーディングをする:
   - **重要**: html-structure エージェントは初期ファイルのサンプルセクションを削除してからデザインのセクションを配置する
   - マニフェストに定義されていないセクションは必ず削除する
   - Figmaデザインに存在するセクションのみを順序通りに配置する
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

## データ使用原則（全エージェント共通）

### データソースの優先順位

1. **最優先**: `mcp__figma__get_design_context` で取得したMCPデザインデータ
2. **次点**: section-analyzer が生成した `.claude/progress/design-manifest.json`
3. **禁止**: エージェント独自の推測や汎用値

### 必須ルール

すべてのエージェント（section-analyzer, section-orchestrator, html-structure, sass-flocss, js-component, code-reviewer）は以下を厳守:

1. **MCPデザインデータからの抽出のみ**: すべてのデザイン値をMCPデザインデータから抽出する
2. **推測禁止**: MCPデザインデータに存在しない値は使用しない
3. **プレースホルダー禁止**: 汎用的なテキスト（例: "Welcome to Our Site", "Learn More"）は絶対に使用しない
4. **網羅性**: MCPデザインデータに書かれているすべての値を使用する

### 禁止事項（全エージェント）

以下は**絶対に禁止**:

- MCPデザインデータに存在しないテキストの使用
- MCPデザインデータに存在しない色の使用
- 汎用的なプレースホルダーテキストの使用
- 推測による値の設定
- マニフェストの `extractedValues` を無視した独自実装

### 検証原則

各エージェントは生成後に以下を確認:

- [ ] すべての値がMCPデザインデータまたはマニフェストに存在するか
- [ ] 推測や汎用値を使っていないか
- [ ] MCPデザインデータと実装が一致しているか
- [ ] 初期ファイルのサンプルセクションが削除されているか（html-structure のみ）
- [ ] マニフェストに定義されていないセクションが残っていないか（html-structure のみ）

## エージェント一覧

- section-analyzer.md: Figma デザインを解析してマニフェスト生成
- section-orchestrator.md: セクション単位での段階的生成を管理
- html-structure.md:`src/index.html` ページの HTML 骨組みを生成
- sass-flocss.md: `src/sass/**/*.scss` FLOCSS 準拠のスタイル生成
- js-component.md: `src/assets/js/script.js` UI 動作やイベント処理を生成
- code-reviewer.md: 自動品質チェックと修正提案
