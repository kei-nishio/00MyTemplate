# Frontend Development Template

HTML / EJS / WordPress（PHP）に対応したフロントエンド開発テンプレートです。
Gulp によるタスク自動化、Sass スタイリング、Babel による JavaScript トランスパイルを採用しています。

---

## 目次

- [動作環境](#動作環境)
- [セットアップ](#セットアップ)
- [ビルドモード設定](#ビルドモード設定)
- [コマンド一覧](#コマンド一覧)
- [ディレクトリ構成](#ディレクトリ構成)
- [環境変数](#環境変数)
- [デプロイ](#デプロイ)
- [開発ガイドライン](#開発ガイドライン)
- [注意事項](#注意事項)

---

## 動作環境

| 項目    | バージョン   |
| ------- | ------------ |
| Node.js | 20.18.1 以上 |
| npm     | 11.0.0 以上  |

```bash
# バージョン確認
node -v
npm -v
```

> **Note:** このプロジェクトは [Volta](https://volta.sh/) でバージョン管理されています。Volta をインストールすると自動的に正しいバージョンが使用されます。

---

## セットアップ

```bash
# 1. パッケージインストール
npm i

# 2. 環境変数ファイルの作成
cp environments/.env.sample environments/.env.local

# 3. .env.local を編集してビルドモードを設定

# 4. 開発サーバー起動
npx gulp
```

---

## ビルドモード設定

`environments/.env.local` でビルドモードを設定します。

### モード一覧

| モード            | EJS_MODE | WP_MODE | WP_LOCAL_MODE | 出力先                  |
| ----------------- | -------- | ------- | ------------- | ----------------------- |
| 静的 HTML         | `false`  | `false` | `false`       | `dist/`                 |
| EJS テンプレート  | `true`   | `false` | `false`       | `dist/`                 |
| WordPress         | `false`  | `true`  | `false`       | `distwp/`               |
| WordPress + Local | `false`  | `true`  | `true`        | `distwp/` + Local Sites |

### EJS モードの場合

```env
EJS_MODE=true
WP_MODE=false
WP_LOCAL_MODE=false
```

### WordPress モードの場合

```env
EJS_MODE=false
WP_MODE=true
WP_LOCAL_MODE=true
SITE_TITLE=your-site-name
THEME_NAME=your-theme-name
LOCAL_SITE_DOMAIN=your-site.local
```

> Local by Flywheel 連携時は `WP_LOCAL_MODE=true` にすると、自動的に `~/Local Sites/{SITE_TITLE}/app/public/wp-content/themes/{THEME_NAME}` へ同期されます。

---

## コマンド一覧

### 開発

| コマンド                | 説明                                            |
| ----------------------- | ----------------------------------------------- |
| `npx gulp`              | 開発サーバー起動＆ファイル監視                  |
| `npx gulp watch-deploy` | 自動デプロイ付き監視（⚠️ 本番直接反映・要注意） |

### ビルド

| コマンド                        | 説明                                 |
| ------------------------------- | ------------------------------------ |
| `npx gulp build`                | 本番ビルド（WebP のみ）              |
| `npx gulp build-with-original`  | 本番ビルド（元画像 + WebP）          |
| `npx gulp build-without-images` | 画像以外のみビルド（既存画像を保持） |
| `npx gulp clean`                | ビルドファイル削除（dist/distwp）    |

### デプロイ

| コマンド                        | 説明                                      |
| ------------------------------- | ----------------------------------------- |
| `npx gulp ssh_test`             | SSH 接続テスト                            |
| `npx gulp deploy`               | ビルド（WebP のみ）＋本番サーバー送信     |
| `npx gulp deploy-with-original` | ビルド（元画像 + WebP）＋本番サーバー送信 |
| `npx gulp deploy_only`          | distwp の内容のみ転送（ビルドなし）       |

### Lint / Format

| コマンド               | 説明                             |
| ---------------------- | -------------------------------- |
| `npm run lint`         | 全ファイル Lint（SCSS + JS）     |
| `npm run lint:fix`     | 全ファイル Lint 自動修正         |
| `npm run lint:scss`    | SCSS ファイル Lint               |
| `npm run lint:js`      | JavaScript ファイル Lint         |
| `npm run lint:html`    | HTML ファイル Lint（ビルド後）   |
| `npm run lint:php`     | PHP ファイル Lint（要 Composer） |
| `npm run format`       | Prettier でフォーマット          |
| `npm run format:check` | フォーマットチェック             |

### パッケージ管理

| コマンド                | 説明                                |
| ----------------------- | ----------------------------------- |
| `ncu`                   | 依存パッケージのバージョン確認      |
| `ncu -u`                | package.json を最新バージョンに更新 |
| `npm audit`             | 脆弱性チェック                      |
| `npm audit fix`         | 脆弱性修正（安全）                  |
| `npm audit fix --force` | 脆弱性修正（強制）                  |

---

## ディレクトリ構成

```
src/                           # ソースファイル（編集対象）
├─ ejs/                        # EJS テンプレート（EJS_MODE=true 時）
│   ├─ common/                 # 共通パーシャル（_head, _header, _footer）
│   ├─ component/              # 再利用コンポーネント（_c-*.ejs）
│   ├─ project/                # ページ固有セクション（_p-*.ejs）
│   └─ pageData/               # テンプレート用 JSON データ
│
├─ wp/                         # WordPress テーマ（WP_MODE=true 時）
│   ├─ functions-lib/          # 機能別 PHP ファイル（f-*.php）
│   └─ parts/                  # テンプレートパーツ
│
├─ sass/                       # Sass スタイルシート
│   ├─ foundation/             # リセット、ベーススタイル
│   ├─ layout/                 # レイアウト（_l-*.scss）
│   ├─ global/                 # 変数、ミックスイン、ブレークポイント
│   └─ object/                 # FLOCSS
│       ├─ component/          # 再利用コンポーネント（_c-*.scss）
│       ├─ project/            # ページ固有スタイル（_p-*.scss）
│       └─ utility/            # ユーティリティ（_u-*.scss）
│
├─ assets/
│   ├─ css/                    # プリコンパイル済み CSS
│   ├─ js/                     # JavaScript
│   └─ images/                 # 画像（自動最適化 + WebP 変換）
│
dist/                          # 静的/EJS ビルド出力（編集禁止）
distwp/                        # WordPress ビルド出力（編集禁止）
environments/                  # 環境変数設定
```

---

## 環境変数

`environments/.env.local` で設定します（`.env.sample` をコピーして作成）。

### プロジェクト設定

| 変数名              | 説明                                     | 例              |
| ------------------- | ---------------------------------------- | --------------- |
| `SITE_TITLE`        | WordPress サイトタイトル（Local 連携用） | `my-site`       |
| `THEME_NAME`        | WordPress テーマフォルダ名               | `my-theme`      |
| `LOCAL_SITE_DOMAIN` | Local by Flywheel ドメイン               | `my-site.local` |

### ビルド設定

| 変数名          | 説明                   | 値                |
| --------------- | ---------------------- | ----------------- |
| `EJS_MODE`      | EJS コンパイル         | `true` / `false`  |
| `WP_MODE`       | WordPress モード       | `true` / `false`  |
| `WP_LOCAL_MODE` | Local by Flywheel 同期 | `true` / `false`  |
| `JPEG_QUALITY`  | JPEG 圧縮品質          | `0` - `100`       |
| `BROWSERS`      | Browserslist 設定      | `last 2 versions` |

### 本番サーバー設定

| 変数名                        | 説明                     | 例                       |
| ----------------------------- | ------------------------ | ------------------------ |
| `PRODUCTION_DEPLOY`           | 自動デプロイ有効化       | `true` / `false`         |
| `PRODUCTION_HOST`             | サーバーホスト名         | `example.com`            |
| `PRODUCTION_PORT`             | SSH ポート               | `22`                     |
| `PRODUCTION_USER`             | SSH ユーザー名           | `username`               |
| `PRODUCTION_PRIVATE_KEY_PATH` | SSH 秘密鍵パス           | `~/.ssh/id_rsa`          |
| `PRODUCTION_SITE_ROOT`        | サイトルートディレクトリ | `/home/user/public_html` |

> `PRODUCTION_SITE_ROOT` には `/wp-content/themes/テーマ名` が自動付与されます。

---

## デプロイ

rsync による差分転送で、変更ファイルのみを高速転送します。

### セットアップ

```bash
# 1. .env.local で PRODUCTION_DEPLOY=true に設定

# 2. SSH 秘密鍵のパーミッション設定（重要）
chmod 600 ~/.ssh/id_rsa

# 3. SSH 接続テスト
npx gulp ssh_test

# 4. デプロイ実行
npx gulp deploy
```

### 転送ログ

- 📄 [更新] - 既存ファイルの変更
- 📁 [新規] - 新規ファイル追加
- 🗑️ [削除] - ファイル削除
- 📊 転送サマリー

### 画像保存形式

| コマンド                       | 保存形式          |
| ------------------------------ | ----------------- |
| `npx gulp build`               | WebP のみ（推奨） |
| `npx gulp build-with-original` | 元画像 + WebP     |

---

## 開発ガイドライン

### レスポンシブ設定

`src/sass/global/_breakpoints.scss` で設定：

```scss
$startFrom: sp; // 'sp'（モバイルファースト）または 'pc'（デスクトップファースト）
```

ブレークポイント：

| 名前 | 値       |
| ---- | -------- |
| `sm` | `600px`  |
| `md` | `768px`  |
| `lg` | `1024px` |
| `xl` | `1440px` |

```scss
.element {
	// SP スタイル
	@include mq('md') {
		// PC スタイル
	}
}
```

### 命名規則（BEM + FLOCSS）

| プレフィックス | 用途                   | 例                     |
| -------------- | ---------------------- | ---------------------- |
| `c-`           | Component（再利用 UI） | `c-button`, `c-card`   |
| `p-`           | Project（ページ固有）  | `p-header`, `p-fv`     |
| `l-`           | Layout（構造）         | `l-inner`, `l-section` |
| `u-`           | Utility（単一目的）    | `u-sp`, `u-pc`         |

### Sass ルール

- `r()` 関数で rem 変換（生の px 値は禁止）
- `mq()` ミックスインでレスポンシブ（生のメディアクエリは禁止）
- ネストは最大 3 階層まで
- CSS 変数は `:root` で定義

### JavaScript フック

クラスではなく `data-*` 属性を使用：

```html
<button data-hamburger aria-expanded="false">
	<div data-drawer>
		<div data-animation="fade-in"></div>
	</div>
</button>
```

---

## 注意事項

### 編集ルール

- ✅ `src/` 内のみ編集
- ❌ `dist/`、`distwp/` は自動生成のため編集禁止
- ❌ `base/` は変更禁止

### セキュリティ

- ⚠️ `.env.local` は絶対に Git にコミットしない
- ⚠️ SSH 秘密鍵は `chmod 600` 必須
- ⚠️ `watch-deploy` は本番直接反映のため要注意

### 共有時

- `node_modules/` を削除してから共有（`npm i` で再インストール可能）

### ビルド

- 納品前に `npx gulp build` を実行してフォルダを整理

---

## ライセンス

© Kei NISHIO
