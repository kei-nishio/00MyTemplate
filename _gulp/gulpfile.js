// ** 基本機能
import { src, dest, watch, series, parallel } from 'gulp'; // Gulpの基本関数
import plumber from 'gulp-plumber'; // エラーが続行するためのモジュール
import notify from 'gulp-notify'; // エラーやタスク完了の通知
import sourcemaps from 'gulp-sourcemaps'; // ソースマップ作成
import changed from 'gulp-changed'; // 変更されたファイルのみを対象にする
import { deleteAsync } from 'del'; // ファイルやディレクトリを削除
import through2 from 'through2'; // gulpの処理を通す
import rename from 'gulp-rename'; // ファイル名変更
import browserSync from 'browser-sync'; // ブラウザの自動リロード
// ** CSS/Sass処理
import gulpSassCreator from 'gulp-sass';
import * as sassImplementation from 'sass';
import sassGlob from 'gulp-sass-glob-use-forward'; // SCSSのインポートを簡略化
import mmq from 'gulp-merge-media-queries'; // メディアクエリをマージ
import postcss from 'gulp-postcss'; // CSS変換処理
import autoprefixer from 'autoprefixer'; // ベンダープレフィックスを自動的に追加
import cssdeclsort from 'css-declaration-sorter'; // CSS宣言をソート
import postcssPresetEnv from 'postcss-preset-env'; // 最新のCSS構文を使用可能に
import cleanCSS from 'gulp-clean-css'; // css圧縮
// ** 画像圧縮
import imagemin from 'gulp-imagemin'; // 画像を最適化
import imageminMozjpeg from 'imagemin-mozjpeg'; // JPEG最適化
import imageminPngquant from 'imagemin-pngquant'; // PNG最適化
import imageminSvgo from 'imagemin-svgo'; // SVG最適化
import webp from 'gulp-webp'; // WebP変換
// ** js圧縮
import babel from 'gulp-babel'; // ES6+のJavaScriptをES5に変換
import uglify from 'gulp-uglify'; // JavaScript圧縮
// ** システム・その他のユーティリティ
import os from 'os'; // OSモジュール

// ** その他の設定
const sass = gulpSassCreator(sassImplementation); // SCSSをCSSにコンパイルするためのモジュール
const browsers = ['last 2 versions', '> 5%', 'ie = 11', 'not ie <= 10', 'ios >= 8', 'and_chr >= 5', 'Android >= 5'];
const userHomeDir = os.homedir(); // ホームディレクトリを取得：C:\Users\userName

// ** パス設定
const wpMode = true; // ! WordPressの場合はtrueにする（静的コーディングのみの場合はfalse）
const siteTitle = 'mytemplate'; // ! WordPress site title (project name)
const themeName = 'mytemplatetheme'; // ! WordPress theme file name
const localSiteDomain = 'mytemplate.local'; // ! WordPress Local Site Domain
const wpDirectory = `${userHomeDir}/Local Sites/${siteTitle}/app/public/wp-content/themes/${themeName}`;

// 読み込み先
const srcPath = {
  sass: '../src/sass/**/*.scss',
  css: '../src/assets/css/**/*',
  js: '../src/assets/js/**/*',
  img: '../src/assets/images/**/*',
  html: ['../src/**/*.html', '!./node_modules/**'],
  php: ['../src/wp/**/*.php', '../src/wp/style.css', '../src/wp/screenshot.*'],
};

// html反映用
const destPath = {
  all: '../dist/**/*',
  css: '../dist/assets/css/',
  js: '../dist/assets/js/',
  img: '../dist/assets/images/',
  html: '../dist/',
};

// WordPress反映用
const destWpPath = {
  all: `../distwp/**/*`,
  css: `../distwp/assets/css/`,
  js: `../distwp/assets/js/`,
  img: `../distwp/assets/images/`,
  php: `../distwp/`,
};

// WordPressLocal反映用
const destWpLocalPath = {
  all: `${wpDirectory}/`, //  all: `${wpDirectory}/**/*` が効かないため
  css: `${wpDirectory}/assets/css/`,
  js: `${wpDirectory}/assets/js/`,
  img: `${wpDirectory}/assets/images/`,
  php: `${wpDirectory}/`,
};

// * HTMLファイルのコピー
const htmlCopy = () => {
  return src(srcPath.html).pipe(dest(destPath.html));
};

// * CSSファイルのコピー
const cssCopy = () => {
  if (wpMode) {
    return src(srcPath.css, { encoding: false })
      .pipe(dest(destPath.css)) // 静的コーディング反映用
      .pipe(dest(destWpPath.css)) // WordPress反映用
      .pipe(dest(destWpLocalPath.css)); // WordPressLocal反映用
  } else {
    return src(srcPath.css, { encoding: false }).pipe(dest(destPath.css));
  }
};

// * PHPファイルのコピー
const phpCopy = () => {
  if (wpMode) {
    return src(srcPath.php, { encoding: false })
      .pipe(dest(destWpPath.php)) // WordPress反映用
      .pipe(dest(destWpLocalPath.php)); // WordPressLocal反映用
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
    .pipe(wpMode ? dest(destWpLocalPath.css) : through2.obj())
    .pipe(rename({ suffix: '.min' })) // ファイル名に.minを追加
    .pipe(cleanCSS()) // CSSを圧縮
    .pipe(dest(destPath.css)) // 圧縮CSSを出力
    .pipe(wpMode ? dest(destWpPath.css) : through2.obj())
    .pipe(wpMode ? dest(destWpLocalPath.css) : through2.obj())
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
    .pipe(wpMode ? dest(destWpLocalPath.img) : through2.obj())
    .pipe(webp()) //webpに変換
    .pipe(dest(destPath.img))
    .pipe(wpMode ? dest(destWpPath.img) : through2.obj())
    .pipe(wpMode ? dest(destWpLocalPath.img) : through2.obj());
};

// * js圧縮
const jsBabel = () => {
  return src(srcPath.js)
    .pipe(plumber({ errorHandler: notify.onError('Error: <%= error.message %>') })) // エラーが発生してもタスクを続行
    .pipe(babel({ presets: ['@babel/preset-env'] })) // ES6+のJavaScriptをES5に変換
    .pipe(dest(destPath.js))
    .pipe(wpMode ? dest(destWpPath.js) : through2.obj())
    .pipe(wpMode ? dest(destWpLocalPath.js) : through2.obj())
    .pipe(rename({ suffix: '.min' }))
    .pipe(uglify()) //js圧縮
    .pipe(dest(destPath.js))
    .pipe(wpMode ? dest(destWpPath.js) : through2.obj())
    .pipe(wpMode ? dest(destWpLocalPath.js) : through2.obj());
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
  return deleteAsync([destPath.all, destWpPath.all, destWpLocalPath.all], { force: true });
};

// * ファイルの監視
const watchFiles = () => {
  watch(srcPath.sass, series(cssSass, browserSyncReload));
  watch(srcPath.js, series(jsBabel, browserSyncReload));
  watch(srcPath.img, series(imgImagemin, browserSyncReload));
  watch(srcPath.html, series(htmlCopy, browserSyncReload));
  if (wpMode) {
    watch(srcPath.php, series(phpCopy, browserSyncReload));
  }
};

// ! ブラウザシンク付きの開発用タスク
export default series(
  series(cssSass, cssCopy, jsBabel, imgImagemin, htmlCopy, phpCopy),
  parallel(watchFiles, browserSyncFunc)
);

// ! 本番用タスク
export const build = series(clean, cssSass, cssCopy, jsBabel, imgImagemin, htmlCopy, phpCopy);
