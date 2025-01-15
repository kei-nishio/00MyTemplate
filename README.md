## ファイルの特徴
- html、ejs、php(WordPress)のコーディングが可能です。
- src内の情報は、htmlやejsは`dist`に、php(WordPress)は`distwp`に反映されます。

## このコーディングファイルの使い方
まず、以下に書いてある内容を必ずお読みください。
この中身で分かることは以下のとおりです。

- 使用環境
- 使い方および操作方法
- 注意点

## 動作確認済み環境
- Node.js バージョン20.18.1以上
- npm バージョン11.0.0以上
- バージョン確認方法：※ターミナル上でコマンドを入力してください。
  - `node -v`
  - `npm -v`
- コマンドを入力後にでてくる数字を確認してください。

## 使い方および操作方法
1. ターミナルを開く。
2. `cd _gulp`をターミナルに入力。（cdと_gulpの間には半角スペース要）
3. `npm i`をターミナルへ入力。
4. 必要なパッケージファイルのダウンロードが始まります。
5. `npx gulp`でタスクランナーが起動します

### - npm i コマンドでダウンロードが始まる仕組み
- `package.json`ファイルから情報を参照して必要なパッケージをダウンロードします。
- Gulpを動かすのに必要な情報になりますので削除は厳禁です。

### - Gulpの使い方
- `gulpfile.js`のパス設定をしてください。
  - ejsの場合は、`ejsMode`を`true`にして、`wpMode`と`wpLocalMode`を`false`にしてください。
  - php(WordPress)の場合は、`ejsMode`を`false`にして、`wpMode`を`true`にしてください。また、local wheelで作成したLocalデータ内のテーマを上書きする場合は、`wpLocalMode`を`true`にしてください。`siteTitle`などはLocal wheelに合わせてください。
- `npx gulp` ないしは `gulp` でタスクランナーが起動します。
  - コーディング中はずっと動かしたままにしてください。
- `npx gulp build`でフォルダ内を整理することができます。

## コーディング時の操作方法
- `src`内のみを触る
- `src`内に入力した情報は自動的に`dist`や`distwp`に反映されます。
- `dist`や`distwp`はアップロードするファイルなので編集は厳禁です。

## ファイルの特徴
- スマホファーストおよびパソコンファーストどちらも設定が可能です。`（変数管理）`
  - src/sass/global/_breakpoints.scssにある変数を`pc` or `sp`に設定します。（初期値：`sp`）
  
## 注意点
- `base`は変更を加えないでください。
- ほかの方に共有する場合は、`_gulp`内の`node_modules`を削除してください（重いため）。
- コーディング完了時はは`npx gulp build`コマンドを入力し、フォルダ内を整理してください。

## gulpとpackage.json
- `npm install -g npm-check-updates`：`ncu`を利用するためにインストールする
- `ncu`：package.json内のプラグインが最新になっているか確認する
- `ncu -u`：package.json内のプラグインを最新にする
- `npm audit`：インストールしたプラグインのエラー情報を取得する
- `npm audit fix`：インストールしたプラグインのエラーを修正する（安全）
- `npm audit fix --force`：インストールしたプラグインのエラーを修正する（強制）
- `npm outdated`：package.jsonの依存関係が最新かどうかチェックする

# Kei NISHIO