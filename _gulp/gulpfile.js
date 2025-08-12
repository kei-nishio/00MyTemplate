// * 基本機能
import { src, dest, watch, series, parallel } from 'gulp'; // Gulpの基本関数
import plumber from 'gulp-plumber'; // エラーが続行するためのモジュール
import notify from 'gulp-notify'; // エラーやタスク完了の通知
import changed from 'gulp-changed'; // 変更されたファイルのみを対象にする
import { deleteAsync } from 'del'; // ファイルやディレクトリを削除
import through2 from 'through2'; // gulpの処理を通す
import rename from 'gulp-rename'; // ファイル名変更
import browserSync from 'browser-sync'; // ブラウザの自動リロード
import replace from 'gulp-replace'; // 文字列置換
// * CSS/Sass処理
import gulpSassCreator from 'gulp-sass';
import * as sassImplementation from 'sass';
import sassGlob from 'gulp-sass-glob-use-forward'; // SCSSのインポートを簡略化
import mmq from 'gulp-merge-media-queries'; // メディアクエリをマージ
import postcss from 'gulp-postcss'; // CSS変換処理
import autoprefixer from 'autoprefixer'; // ベンダープレフィックスを自動的に追加
import cssdeclsort from 'css-declaration-sorter'; // CSS宣言をソート
import postcssPresetEnv from 'postcss-preset-env'; // 最新のCSS構文を使用可能に
import cleanCSS from 'gulp-clean-css'; // css圧縮
import sourcemaps from 'gulp-sourcemaps'; // ソースマップ作成
// * 画像圧縮
import imagemin from 'gulp-imagemin'; // 画像を最適化
import imageminMozjpeg from 'imagemin-mozjpeg'; // JPEG最適化
import imageminPngquant from 'imagemin-pngquant'; // PNG最適化
import imageminSvgo from 'imagemin-svgo'; // SVG最適化
import webp from 'gulp-webp'; // WebP変換
// * js圧縮
import babel from 'gulp-babel'; // ES6+のJavaScriptをES5に変換
import uglify from 'gulp-uglify'; // JavaScript圧縮
// * ejs処理
import ejs from 'gulp-ejs'; // EJSをHTMLに変換
import htmlbeautify from 'gulp-html-beautify'; // HTML整形
import fs from 'fs'; // JSONファイル操作用
// * システム・その他のユーティリティ
import os from 'os'; // OSモジュール

// * その他の設定
const sass = gulpSassCreator(sassImplementation); // SCSSをCSSにコンパイルするためのモジュール
const browsers = ['last 2 versions', '> 5%', 'ie = 11', 'not ie <= 10', 'ios >= 8', 'and_chr >= 5', 'Android >= 5'];
const userHomeDir = os.homedir(); // ホームディレクトリを取得：C:\Users\userName

// * パス設定
const ejsMode = false; // ! EJSの場合はtrueにする（静的コーディングのみの場合はfalse）
const wpMode = true; // ! WordPressの場合はtrueにする（静的コーディングのみの場合はfalse）
const wpLocalMode = true; // ! WordPressLocalの内容を上書きする場合はtrueにする
const srcEjsDir = '../src/ejs'; // ! EJSファイルのディレクトリ
const siteTitle = 'template'; // ! WordPress site title (project name)
const themeName = 'templatetheme'; // ! WordPress theme file name
const localSiteDomain = 'template.local'; // ! WordPress Local Site Domain
const wpDirectory = `${userHomeDir}/Local Sites/${siteTitle}/app/public/wp-content/themes/${themeName}`;

// * 読み込み先
const srcPath = {
  sass: '../src/sass/**/*.scss',
  css: '../src/assets/css/**/*',
  js: '../src/assets/js/**/*',
  img: '../src/assets/images/**/*',
  others: ['../src/assets/**/*', '!../src/assets/images/**/*', '!../src/assets/js/**/*', '!../src/assets/css/**/*'],
  ejs: '../src/ejs/**/*.ejs',
  html: ['../src/**/*.html', '!./node_modules/**'],
  php: ['../src/wp/**/*.php', '../src/wp/style.css', '../src/wp/screenshot.*', '../src/wp/**/*.json'],
};

// * html反映用
const destPath = {
  all: '../dist/**/*',
  sass: '../dist/src/sass/',
  css: '../dist/assets/css/',
  js: '../dist/assets/js/',
  img: '../dist/assets/images/',
  others: '../dist/assets/',
  html: '../dist/',
};

// * WordPress反映用
const destWpPath = {
  all: `../distwp/**/*`,
  sass: `../distwp/src/sass/`,
  css: `../distwp/assets/css/`,
  js: `../distwp/assets/js/`,
  img: `../distwp/assets/images/`,
  others: `../distwp/assets/`,
  php: `../distwp/`,
};

// * WordPressLocal反映用
const destWpLocalPath = {
  all: `${wpDirectory}/`, // all: `${wpDirectory}/**/*` が効かないため
  sass: `${wpDirectory}/src/sass/`,
  css: `${wpDirectory}/assets/css/`,
  js: `${wpDirectory}/assets/js/`,
  img: `${wpDirectory}/assets/images/`,
  others: `${wpDirectory}/assets/`,
  php: `${wpDirectory}/`,
};

// * HTMLファイルのコピー
const htmlCopy = () => {
  if (ejsMode) {
    return Promise.resolve(); // trueの場合は何も実行せず、Promiseを返す
  } else {
    return src(srcPath.html).pipe(dest(destPath.html));
  }
};

// * othersファイルのコピー
const othersCopy = () => {
  if (wpMode) {
    return src(srcPath.others, { encoding: false })
      .pipe(dest(destWpPath.others)) // WordPress反映用
      .pipe(wpLocalMode ? dest(destWpLocalPath.others) : through2.obj()); // WordPressLocal反映用
  } else {
    return src(srcPath.others, { encoding: false }).pipe(dest(destPath.others));
  }
};

// * SASSファイルのコピー
const sassCopy = () => {
  if (wpMode) {
    return src(srcPath.sass, { encoding: false })
      .pipe(dest(destWpPath.sass)) // WordPress反映用
      .pipe(wpLocalMode ? dest(destWpLocalPath.sass) : through2.obj()); // WordPressLocal反映用
  } else {
    return src(srcPath.sass, { encoding: false }).pipe(dest(destPath.sass));
  }
};

// * CSSファイルのコピー
const cssCopy = () => {
  if (wpMode) {
    return src(srcPath.css, { encoding: false })
      .pipe(dest(destPath.css)) // 静的コーディング反映用
      .pipe(dest(destWpPath.css)) // WordPress反映用
      .pipe(wpLocalMode ? dest(destWpLocalPath.css) : through2.obj()); // WordPressLocal反映用
  } else {
    return src(srcPath.css, { encoding: false }).pipe(dest(destPath.css));
  }
};

// * PHPファイルのコピー
const phpCopy = () => {
  if (wpMode) {
    return src(srcPath.php, { encoding: false })
      .pipe(dest(destWpPath.php)) // WordPress反映用
      .pipe(wpLocalMode ? dest(destWpLocalPath.php) : through2.obj()); // WordPressLocal反映用
  } else {
    return Promise.resolve(); // falseの場合は何も実行せず、Promiseを返す
  }
};

// * SASSファイルのコンパイル
const cssSass = () => {
  return src(srcPath.sass)
    .pipe(sourcemaps.init()) // ソースマップを初期化
    .pipe(plumber({ errorHandler: notify.onError('Error:<%= error.message %>') })) // エラーが発生してもタスクを続行
    .pipe(sassGlob()) // Sassのパーシャル（_ファイル）を自動的にインポート
    .pipe(sass.sync({ includePaths: ['src/sass'], outputStyle: 'expanded' })) // コンパイル後のCSSの書式（expanded or compressed）
    .pipe(
      postcss([
        postcssPresetEnv({ browsers: 'last 2 versions' }), // 未来のCSS構文を使用可能にし、対象ブラウザを最新2バージョンに限定
        autoprefixer({ grid: true }), // ベンダープレフィックスを自動で付与、グリッドレイアウトをサポート
        cssdeclsort({ order: 'alphabetical' }), // CSSプロパティをアルファベット順にソート
      ])
    )
    .pipe(mmq())
    .pipe(sourcemaps.write('./')) // ソースマップをここで書き出す
    .pipe(dest(destPath.css)) // 非圧縮CSSを出力
    .pipe(wpMode ? dest(destWpPath.css) : through2.obj())
    .pipe(wpLocalMode && wpMode && wpMode ? dest(destWpLocalPath.css) : through2.obj())
    .pipe(rename({ suffix: '.min' })) // ファイル名に.minを追加
    .pipe(cleanCSS()) // CSSを圧縮
    .pipe(dest(destPath.css)) // 圧縮CSSを出力
    .pipe(wpMode ? dest(destWpPath.css) : through2.obj())
    .pipe(wpLocalMode && wpMode && wpMode ? dest(destWpLocalPath.css) : through2.obj())
    .pipe(notify({ message: 'Sassをコンパイルしました！', onLast: true })); // 通知を表示
};

// * 画像圧縮
const imgImagemin = () => {
  return src(srcPath.img, { encoding: false })
    .pipe(changed(destPath.img)) // 画像の変更を監視
    .pipe(
      imagemin(
        [
          imageminMozjpeg({ quality: 80 }), // JPEG圧縮品質（0〜100）
          imageminPngquant(), // PNG圧縮品質（0〜1）
          imageminSvgo({
            plugins: [
              {
                name: 'preset-default',
                params: {
                  overrides: {
                    removeViewBox: false,
                  },
                },
              },
            ],
          }), // SVG画像 viewBox属性を削除しない
        ],
        {
          verbose: true, // 圧縮情報を表示
        }
      )
    )
    .pipe(dest(destPath.img))
    .pipe(wpMode ? dest(destWpPath.img) : through2.obj())
    .pipe(wpLocalMode && wpMode ? dest(destWpLocalPath.img) : through2.obj())
    .pipe(webp()) //webpに変換
    .pipe(dest(destPath.img))
    .pipe(wpMode ? dest(destWpPath.img) : through2.obj())
    .pipe(wpLocalMode && wpMode ? dest(destWpLocalPath.img) : through2.obj());
};

// * js圧縮
const jsBabel = () => {
  return src(srcPath.js)
    .pipe(plumber({ errorHandler: notify.onError('Error: <%= error.message %>') })) // エラーが発生してもタスクを続行
    .pipe(babel({ presets: ['@babel/preset-env'] })) // ES6+のJavaScriptをES5に変換
    .pipe(dest(destPath.js))
    .pipe(wpMode ? dest(destWpPath.js) : through2.obj())
    .pipe(wpLocalMode && wpMode ? dest(destWpLocalPath.js) : through2.obj())
    .pipe(rename({ suffix: '.min' }))
    .pipe(uglify()) //js圧縮
    .pipe(dest(destPath.js))
    .pipe(wpMode ? dest(destWpPath.js) : through2.obj())
    .pipe(wpLocalMode && wpMode ? dest(destWpLocalPath.js) : through2.obj());
};

// * EJSのコンパイル
export const ejsCompile = () => {
  if (ejsMode) {
    // JSONディレクトリからすべてのJSONファイルを読み込む
    const jsonDir = srcEjsDir + '/pageData';
    const jsonFiles = fs.readdirSync(jsonDir); // ディレクトリ内のファイル一覧を取得
    let jsonData = {}; // すべてのJSONデータを格納するオブジェクト

    jsonFiles.forEach((file) => {
      if (file.endsWith('.json')) {
        // ファイル名から拡張子を除いた部分を名前空間として使用
        const filePath = jsonDir + '/' + file;
        const fileData = JSON.parse(fs.readFileSync(filePath, 'utf8'));
        const namespace = file.replace('.json', '');
        jsonData[namespace] = fileData; // ファイル名をキーとしてデータをマージ
      }
    });
    // jsonDataの内容を確認するためにログを表示
    // console.log('jsonData:', JSON.stringify(jsonData, null, 2));
    return (
      src([srcEjsDir + '/**/*.ejs', '!' + srcEjsDir + '/**/_*.ejs']) // パーシャルファイルを除く
        // .pipe(
        //   plumber({
        //     // エラーハンドリングを設定
        //     errorHandler: notify.onError((error) => {
        //       return {
        //         message: `Error: ${error.message}`,
        //         sound: false,
        //       };
        //     }),
        //   })
        // )
        .pipe(ejs({ json: jsonData })) // 全てのJSONデータをEJSに渡す
        .pipe(rename({ extname: '.html' })) // 拡張子を.htmlに変更
        .pipe(replace(/^[ \t]*\n/gm, '')) // 空白行を削除
        .pipe(
          htmlbeautify({
            indent_size: 2, // インデントサイズ
            indent_char: ' ', // インデントに使う文字
            max_preserve_newlines: 0, // 連続する空行の最大数
            preserve_newlines: false, // 改行を削除
            extra_liners: [], // 余分な改行を削除
          })
        )
        .pipe(dest(destPath.html)) // コンパイル済みのHTMLファイルを出力先に保存
        .pipe(notify({ message: 'Ejsをコンパイルしました！', onLast: true }))
    ); // 通知を表示
  } else {
    return Promise.resolve(); // ejsModeがfalseの場合は何も実行せず、Promiseを返す
  }
};

// * ブラウザシンクの設定
const browserSyncOption = {
  notify: false,
};
if (wpMode) {
  browserSyncOption.proxy = `http://${localSiteDomain}/`; // WordPressLocal反映用
} else {
  browserSyncOption.server = '../dist/'; // 静的コーディング反映用
}
const browserSyncFunc = () => {
  browserSync.init(browserSyncOption);
};
const browserSyncReload = (done) => {
  browserSync.reload();
  done();
};

// * ファイルの削除
const clean = () => {
  if (wpLocalMode) {
    return deleteAsync([destPath.all, destWpPath.all, destWpLocalPath.all], { force: true });
  } else {
    return deleteAsync([destPath.all, destWpPath.all], { force: true });
  }
};

// * ファイルの監視
const watchFiles = () => {
  watch(srcPath.sass, series(cssSass, browserSyncReload)); // Sass
  watch(srcPath.js, series(jsBabel, browserSyncReload)); // JavaScript
  watch(srcPath.img, series(imgImagemin, browserSyncReload)); // 画像ファイル
  if (wpMode) {
    watch(srcPath.php, series(phpCopy, browserSyncReload)); // WordPressの場合
  } else if (ejsMode) {
    watch(srcPath.ejs, series(ejsCompile, browserSyncReload)); // EJSの場合
  } else {
    watch(srcPath.html, series(htmlCopy, browserSyncReload)); // 静的コーディングの場合
  }
};

// ! ブラウザシンク付きの開発用タスク
export default series(
  series(cssSass, cssCopy, othersCopy, sassCopy, jsBabel, imgImagemin, htmlCopy, ejsCompile, phpCopy),
  parallel(watchFiles, browserSyncFunc)
);

// ! 本番用タスク
export const build = series(
  clean,
  cssSass,
  cssCopy,
  othersCopy,
  sassCopy,
  jsBabel,
  imgImagemin,
  htmlCopy,
  ejsCompile,
  phpCopy
);
