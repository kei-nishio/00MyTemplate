# 以下のルールに従って、以下で指定する Figma デザインを HTML、CSS、JavaScript に変換してください

## 出力指示

- Figma デザインを**すべて完全に再現**してください。
- **セクションの順番を厳密に守って**HTML を構築してください。
- 画像も figma から書き出してください。
- HTML 構造・要素配置・テキスト内容・画像使用も忠実に反映してください。
- JavaScript 用のクラス（`js-xxx`）には CSS スタイルを適用しないでください。

## コーディングルール

- このプロジェクトに含まれているコーディングルールを定義した`RULES.md` と `coding-rules.json` に従ってください。
- JSON ファイルはルール定義だけでなく、整形ルール（インデント、カンマ、クォートなど）も記載していますので、出力や提案時にはそれらを遵守してください。

## フォルダ構成

- HTML ファイルはプロジェクトルートに生成してください。
- CSS ファイルは `assets/css/` に保存してください。
- 画像は `assets/images/` に保存し、拡張子は内容に応じて `.jpg` / `.png` を使い分けてください。
- フォルダが存在しない場合は**自動的に作成してください**。
- Widows の場合は、PowerShell を使用しているためフォルダをコマンドで作成するときは「mkdir -Force」または「New-Item」を使用してください。

## Figma URL

## for PC

<https://www.figma.com/design/xxxx>

## for sp

<https://www.figma.com/design/xxxx>
