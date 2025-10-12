# メモ

このベースでは css ファイルは除外しています。
reset.scss と style.scss をコンパイルして、
reset.css と style.css を生成して使ってください。


## gitignore について

このプロジェクトの.gitignore では以下を除外しています。

- Netbeans のプロジェクトファイル
- VSCode のプロジェクトファイル
- VSCode のワークスペースファイル
- Jetbrains IDE（PhpStorm など）のプロジェクトファイル
- CSS ファイル

## SCSS について

libsass(node-sass)ではなく sass(dart-sass)を利用しています。  
libsass バージョンを利用する場合は  
https://gitlab-ce.alta.co.jp/kit/html-scss-js-base/tags/libsass(node-sass)ver  
を利用してください

# コンパイル・CSSLint

## 前提環境

- Node.js

## 手順

### node_modules をインストールする

`npm i`

※node_modules と pacage-lock.json ができる

### コンパイル先を指定する

`"start": "node-sass -rw scss -o css -w",`

package.json の上記の箇所を書き換える

例）WordPress を使う場合

`"start": "node-sass -rw wp-content/themes/theme/scss -o wp-content/themes/theme/css -w",`

※/theme/のところは、各案件ごとに変わる可能性があります

## 実行

`npm test`

CSSLint を実行（おかしいところをチェック&簡単な自動整形）

### エラーについて

エラーが出たらできる限り修正しましょう。
ただ、min.css で出ているエラーは表示に問題が無ければスルーで大丈夫です。

### 各ファイルの説明

- package.json

コンパイルや CSSLint を行うために必要な記述がまとめてあります。
基本、コンパイル先を変更するとき以外は触る必要はありません。

- .stylelintrc.json

styleLint のルールをまとめてあります。
基本これだけのルールで事足りますが、もしルールを追加したかったり、不要なルールがあれば追加や削除は自由に行ってください。

- prettierrc.json

CSS を整形するときのルールが記載してあります。
基本触る必要はありません。

=================================================================================================

# watch について

拡張機能なしで scss のコンパイルができます。<br>
--watch オプションにより、起動している間は保存時に自動で動作します。

## 必要なパッケージ

sass

- sass

postcss

- postcss-cli
- autoprefixer

## 動作と実行方法

`npm run watch` <br>
sass コンパイルとベンダープレフィクスの付与を行います。

`npm run sass`

sass コンパイルのみを行います。

`npm run postcss`

ベンダープレフィクスの付与のみを行います。

### 動作を終了する場合

ctrl + C を 2 回

動作しない場合<br>
ファイルパスを確認してください

================================================================================================================

# imagesize について

## 必要なパッケージ

- image-size
- jsdom

## メモ

実は VSCode のコマンドパレットから「Emmet:イメージサイズの更新」が使用できます。

- 画像そのままのサイズがでます。
- 複数選択で、複数同時に挿入ができます。

## 動作

html ファイルの、すべての img タグ/source タグに対して、width、height を付与します。
※PHP 非対応

動作ファイル：`imagesize.mjs`

実行する前に下記をそれぞれ設定して下さい。

オプション

- プロジェクトパス：`root`
- 対象のファイル：`target_name`
- 画像の書き出し倍率：`magnification`

## 実行方法

`npm run imagesize`

============================================================================================================

# imagemin について

## 必要なパッケージ

- imagemin-keep-folder
- imagemin-mozjpeg
- imagemin-pngquant

## 動作

指定されたパスに存在する JPEG、PNG 画像を圧縮します。（非可逆圧縮）

動作ファイル：`imagemin.mjs`

実行する前に下記をそれぞれ設定して下さい。

オプション

- プロジェクトパス：`dirPath`

small フォルダを作成して、圧縮前のファイルを入れると使いやすいです。

## 注意

- JPEG 画像は XD から 100%の品質で書き出してください

## 各ファイルの説明

- imagemin.mjs

画像の圧縮後のクオリティを指定できます。

### JPEG

imageminMozjpeg : 80%<br>
80%の品質に圧縮（TinyPng と同等）

### PNG

imageminPngquant : [0.8,1.0] <br>
80%~100%の間の品質に圧縮

## 実行方法

`npm run imagemin`


# 画像圧縮について

・ライブラリインストール
npm install --save-dev imagemin imagemin-svgo glob

・画像圧縮を実行
npm run imagemin

を実行する