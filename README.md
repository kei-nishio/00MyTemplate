## ファイルの特徴

- html、ejs、php(WordPress)のコーディングが可能です。
- src 内の情報は、html や ejs は`dist`に、php(WordPress)は`distwp`に反映されます。

## このコーディングファイルの使い方

まず、以下に書いてある内容を必ずお読みください。
この中身で分かることは以下のとおりです。

- 使用環境
- 使い方および操作方法
- 注意点

## 動作確認済み環境

- Node.js バージョン 20.18.1 以上
- npm バージョン 11.0.0 以上
- バージョン確認方法：※ターミナル上でコマンドを入力してください。
  - `node -v`
  - `npm -v`
- コマンドを入力後にでてくる数字を確認してください。

## 使い方および操作方法

1. ターミナルを開く。
2. `npm i`をターミナルへ入力。
3. 必要なパッケージファイルのダウンロードが始まります。
4. `npx gulp`でタスクランナーが起動します

### - npm i コマンドでダウンロードが始まる仕組み

- `package.json`ファイルから情報を参照して必要なパッケージをダウンロードします。
- Gulp を動かすのに必要な情報になりますので削除は厳禁です。

### - Gulp の使い方

- `environments/.env.local`のパス設定をしてください。
  - ejs の場合は、`EJS_MODE`を`true`にして、`WP_MODE`と`WP_LOCAL_MODE`を`false`にしてください。
  - php(WordPress)の場合は、`EJS_MODE`を`false`にして、`WP_MODE`を`true`にしてください。また、Local by Flywheel で作成した Local データ内のテーマを上書きする場合は、`WP_LOCAL_MODE`を`true`にしてください。`SITE_TITLE`などは Local by Flywheel に合わせてください。
- `npx gulp` ないしは `gulp` でタスクランナーが起動します。
  - コーディング中はずっと動かしたままにしてください。
- `npx gulp build`でフォルダ内を整理することができます。

## コーディング時の操作方法

- `src`内のみを触る
- `src`内に入力した情報は自動的に`dist`や`distwp`に反映されます。
- `dist`や`distwp`はアップロードするファイルなので編集は厳禁です。

## ファイルの特徴

- スマホファーストおよびパソコンファーストどちらも設定が可能です。`（変数管理）`
  - src/sass/global/\_breakpoints.scss にある変数を`pc` or `sp`に設定します。（初期値：`sp`）

## 注意点

- `base`は変更を加えないでください。
- ほかの方に共有する場合は、`node_modules`を削除してください（重いため）。
- コーディング完了時は`npx gulp build`コマンドを入力し、フォルダ内を整理してください。

## gulp と package.json

- `npm install -g npm-check-updates`：`ncu`を利用するためにインストールする
- `ncu`：package.json 内のプラグインが最新になっているか確認する
- `ncu -u`：package.json 内のプラグインを最新にする
- `npm audit`：インストールしたプラグインのエラー情報を取得する
- `npm audit fix`：インストールしたプラグインのエラーを修正する（安全）
- `npm audit fix --force`：インストールしたプラグインのエラーを修正する（強制）
- `npm outdated`：package.json の依存関係が最新かどうかチェックする

# Kei NISHIO
