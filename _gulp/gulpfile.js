const { src, dest, watch, series, parallel } = require("gulp"); // Gulpの基本関数をインポート
const os = require("os"); //osモジュールを読み込む
const sass = require("gulp-sass")(require("sass")); // SCSSをCSSにコンパイルするためのモジュール
const plumber = require("gulp-plumber"); // エラーが発生してもタスクを続行するためのモジュール
const notify = require("gulp-notify"); // エラーやタスク完了の通知を表示するためのモジュール
const sassGlob = require("gulp-sass-glob-use-forward"); // SCSSのインポートを簡略化するためのモジュール
const mmq = require("gulp-merge-media-queries"); // メディアクエリをマージするためのモジュール
const postcss = require("gulp-postcss"); // CSSの変換処理を行うためのモジュール
const autoprefixer = require("autoprefixer"); // ベンダープレフィックスを自動的に追加するためのモジュール
const cssdeclsort = require("css-declaration-sorter"); // CSSの宣言をソートするためのモジュール
const postcssPresetEnv = require("postcss-preset-env"); // 最新のCSS構文を使用可能にするためのモジュール
const sourcemaps = require("gulp-sourcemaps"); // ソースマップを作成するためのモジュール
const babel = require("gulp-babel"); // ES6+のJavaScriptをES5に変換するためのモジュール
const imageminSvgo = require("imagemin-svgo"); // SVGを最適化するためのモジュール
const browserSync = require("browser-sync"); // ブラウザの自動リロード機能を提供するためのモジュール
const imagemin = require("gulp-imagemin"); // 画像を最適化するためのモジュール
const imageminMozjpeg = require("imagemin-mozjpeg"); // JPEGを最適化するためのモジュール
const imageminPngquant = require("imagemin-pngquant"); // PNGを最適化するためのモジュール
const changed = require("gulp-changed"); // 変更されたファイルのみを対象にするためのモジュール
const del = require("del"); // ファイルやディレクトリを削除するためのモジュール
const webp = require("gulp-webp"); //webp変換
const rename = require("gulp-rename"); //ファイル名変更
const through2 = require("through2"); // gulpの処理を通す
const uglify = require('gulp-uglify'); // js圧縮
const cleanCSS = require('gulp-clean-css'); // css圧縮

const browsers = ["last 2 versions", "> 5%", "ie = 11", "not ie <= 10", "ios >= 8", "and_chr >= 5", "Android >= 5"];
const userHomeDir = os.homedir(); // ホームディレクトリを取得：C:\Users\userName

const wpMode = false; // ! WordPressの場合はtrueにする（静的コーディングのみの場合はfalse）
const siteTitle = "hogeTemplate"; // ! WordPress site title (project name)
const themeName = "hogeTemplateTheme"; // ! WordPress theme file name
const localSiteDomain = "hogeTemplate.local"; // ! WordPress Local Site Domain

const wpDirectory = `${userHomeDir}/Local Sites/${siteTitle}/app/public/wp-content/themes/${themeName}`;

// 読み込み先
const srcPath = {
  css: "../src/sass/**/*.scss",
  js: "../src/assets/js/**/*",
  img: "../src/assets/images/**/*",
  html: ["../src/**/*.html", "!./node_modules/**"],
  php: ["../src/wp/**/*.php", "../src/wp/style.css", "../src/wp/screenshot.*"],
};

// html反映用
const destPath = {
  all: "../dist/**/*",
  css: "../dist/assets/css/",
  js: "../dist/assets/js/",
  img: "../dist/assets/images/",
  html: "../dist/",
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

// * PHPファイルのコピー
const phpCopy = () => {
  if (wpMode) {
    return (
      src(srcPath.php)
        .pipe(dest(destWpPath.php)) // WordPress反映用
        .pipe(dest(destWpLocalPath.php)) // WordPressLocal反映用
    );
  } else {
    return src(".").pipe(dest(".")); // falseの場合は何も実行せず、undefinedを返す
  }
};

// * SASSファイルのコンパイル
const cssSass = () => {
  return (
    src(srcPath.css)
      .pipe(sourcemaps.init()) // ソースマップを初期化
      .pipe(plumber({ errorHandler: notify.onError("Error:<%= error.message %>"), })) // エラーが発生してもタスクを続行
      .pipe(sassGlob()) // Sassのパーシャル（_ファイル）を自動的にインポート
      .pipe(sass.sync({ includePaths: ["src/sass"], outputStyle: "expanded", })) // コンパイル後のCSSの書式（expanded or compressed）
      .pipe(postcss([
        postcssPresetEnv({ browsers: 'last 2 versions' }), // 未来のCSS構文を使用可能にし、対象ブラウザを最新2バージョンに限定
        autoprefixer({ grid: true }), // ベンダープレフィックスを自動で付与、グリッドレイアウトをサポート
        cssdeclsort({ order: 'alphabetical' }) // CSSプロパティをアルファベット順にソート
      ]))
      .pipe(mmq()) // メディアクエリをマージ
      .pipe(dest(destPath.css))
      .pipe(wpMode ? dest(destWpPath.css) : through2.obj())
      .pipe(wpMode ? dest(destWpLocalPath.css) : through2.obj())
      .pipe(sourcemaps.write("./")) // ソースマップを書き出し
      .pipe(rename({ suffix: '.min' }))
      .pipe(cleanCSS()) //css圧縮
      .pipe(sourcemaps.write("./")) // ソースマップを書き出し
      .pipe(dest(destPath.css))
      .pipe(wpMode ? dest(destWpPath.css) : through2.obj())
      .pipe(wpMode ? dest(destWpLocalPath.css) : through2.obj())
      .pipe(notify({ message: "Sassをコンパイルしました！", onLast: true, })) // 通知を表示
  );
};

// * 画像圧縮
const imgImagemin = () => {
  return (
    src(srcPath.img)
      .pipe(changed(destPath.img)) // 画像の変更を監視
      .pipe(
        imagemin(
          [
            imageminMozjpeg({ quality: 80, }), // JPEG圧縮品質（0〜100）
            imageminPngquant(), // PNG圧縮品質（0〜1）
            imageminSvgo({ plugins: [{ removeViewbox: false, },], }), // SVG画像　viewBox属性を削除しない
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
      .pipe(wpMode ? dest(destWpLocalPath.img) : through2.obj())
  );
};

// * js圧縮
const jsBabel = () => {
  return (
    src(srcPath.js)
      .pipe(plumber({ errorHandler: notify.onError("Error: <%= error.message %>"), })) // エラーが発生してもタスクを続行
      .pipe(babel({ presets: ["@babel/preset-env"], })) // ES6+のJavaScriptをES5に変換
      .pipe(dest(destPath.js))
      .pipe(wpMode ? dest(destWpPath.js) : through2.obj())
      .pipe(wpMode ? dest(destWpLocalPath.js) : through2.obj())
      .pipe(rename({ suffix: '.min' }))
      .pipe(uglify()) //js圧縮
      .pipe(dest(destPath.js))
      .pipe(wpMode ? dest(destWpPath.js) : through2.obj())
      .pipe(wpMode ? dest(destWpLocalPath.js) : through2.obj())
  );
};

// * ブラウザシンクの設定
const browserSyncOption = {
  notify: false,
};
if (wpMode) {
  browserSyncOption.proxy = `http://${localSiteDomain}/`; // WordPressLocal反映用
} else {
  browserSyncOption.server = "../dist/"; // 静的コーディング反映用
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
  return del([destPath.all, destWpPath.all, destWpLocalPath.all], { force: true });
};

// * ファイルの監視
const watchFiles = () => {
  watch(srcPath.css, series(cssSass, browserSyncReload));
  watch(srcPath.js, series(jsBabel, browserSyncReload));
  watch(srcPath.img, series(imgImagemin, browserSyncReload));
  watch(srcPath.html, series(htmlCopy, browserSyncReload));
  if (wpMode) {
    watch(srcPath.php, series(phpCopy, browserSyncReload));
  }
};

// ! ブラウザシンク付きの開発用タスク
exports.default = series(
  series(cssSass, jsBabel, imgImagemin, htmlCopy, phpCopy),
  parallel(watchFiles, browserSyncFunc)
);

// ! 本番用タスク
exports.build = series(clean, cssSass, jsBabel, imgImagemin, htmlCopy, phpCopy);
