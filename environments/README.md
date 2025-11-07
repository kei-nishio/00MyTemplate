# 環境変数設定

## 使用方法

1. `.env.sample`をコピーして`.env.local`ファイルを作成してください：

   ```bash
   cp .env.sample .env.local
   ```

2. `.env.local`ファイルを開き、プロジェクトに応じて値を設定してください。

3. Gulp タスクが自動的に`.env.local`から環境変数を読み込みます。

## 環境変数一覧

### プロジェクト設定

- `SITE_TITLE`: WordPress サイトタイトル (プロジェクト名)
- `THEME_NAME`: WordPress テーマファイル名
- `LOCAL_SITE_DOMAIN`: WordPress Local サイトドメイン

### ビルド設定

- `EJS_MODE`: EJS を使用する場合は`true`、静的コーディングのみの場合は`false`
- `WP_MODE`: WordPress の場合は`true`、静的コーディングのみの場合は`false`
- `WP_LOCAL_MODE`: WordPressLocal の内容を上書きする場合は`true`

### ディレクトリ設定

- `SRC_EJS_DIR`: EJS ファイルのディレクトリパス

### 画像圧縮設定

- `JPEG_QUALITY`: JPEG 圧縮品質 (0-100)

### ブラウザサポート設定

- `BROWSERS`: Browserslist の形式でサポートブラウザを指定

## 注意事項

- `.env.local`ファイルは個人の開発環境に固有な設定を含む可能性があるため、Git にコミットされません。
- チーム開発の際は、必要に応じて`.env.sample`を更新し、新しいメンバーに設定方法を共有してください。
