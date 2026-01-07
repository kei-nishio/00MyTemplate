// * 環境変数の読み込み
import dotenv from 'dotenv';
dotenv.config({ path: '../environments/.env.local' });

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
// * SSHデプロイ
import GulpSSH from 'gulp-ssh'; // SSH接続用
import { exec } from 'child_process'; // コマンド実行用
import { promisify } from 'util'; // Promise化用
// * システム・その他のユーティリティ
import os from 'os'; // OSモジュール

const execPromise = promisify(exec); // execをPromise化

// * その他の設定
const sass = gulpSassCreator(sassImplementation); // SCSSをCSSにコンパイルするためのモジュール
const browsers = process.env.BROWSERS?.split(',').map((b) => b.trim()) || [
  'last 2 versions',
  '> 5%',
  'ie >= 11',
  'not ie <= 10',
  'ios >= 8',
  'and_chr >= 5',
  'Android >= 5',
];
const userHomeDir = os.homedir(); // ホームディレクトリを取得：C:\Users\userName

// * パス設定
const ejsMode = process.env.EJS_MODE === 'true'; // ! EJSの場合はtrueにする（静的コーディングのみの場合はfalse）
const wpMode = process.env.WP_MODE === 'true'; // ! WordPressの場合はtrueにする（静的コーディングのみの場合はfalse）
const wpLocalMode = process.env.WP_LOCAL_MODE === 'true'; // ! WordPressLocalの内容を上書きする場合はtrueにする
const srcEjsDir = process.env.SRC_EJS_DIR || '../src/ejs'; // ! EJSファイルのディレクトリ
const siteTitle = process.env.SITE_TITLE || 'template'; // ! WordPress site title (project name)
const themeName = process.env.THEME_NAME || 'templatetheme'; // ! WordPress theme file name
const localSiteDomain = process.env.LOCAL_SITE_DOMAIN || 'template.local'; // ! WordPress Local Site Domain
const wpDirectory = `${userHomeDir}/Local Sites/${siteTitle}/app/public/wp-content/themes/${themeName}`;
const jpegQuality = parseInt(process.env.JPEG_QUALITY) || 80; // ! JPEG圧縮品質

// * 本番サーバー設定
const productionDeploy = process.env.PRODUCTION_DEPLOY === 'true'; // ! 本番デプロイを有効化
const productionHost = process.env.PRODUCTION_HOST || 'example.com'; // ! サーバーホスト名
const productionPort = parseInt(process.env.PRODUCTION_PORT) || 22; // ! SSHポート番号
const productionUser = process.env.PRODUCTION_USER || 'username'; // ! SSHユーザー名
const productionPrivateKeyPath = process.env.PRODUCTION_PRIVATE_KEY_PATH || '~/.ssh/id_rsa'; // ! SSH秘密鍵パス
const productionSiteRoot = process.env.PRODUCTION_SITE_ROOT || '/home/username/public_html/sitename'; // ! サイトルート
const productionRemotePath = `${productionSiteRoot}/wp-content/themes/${themeName}`; // テーマディレクトリへの完全パス

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
  base: `../distwp`,
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
    return Promise.resolve();
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
      .pipe(dest(destPath.css))
      .pipe(dest(destWpPath.css))
      .pipe(wpLocalMode ? dest(destWpLocalPath.css) : through2.obj());
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
        postcssPresetEnv({ browsers: browsers }), // 未来のCSS構文を使用可能にし、環境変数で指定されたブラウザをサポート
        autoprefixer({ grid: true }), // ベンダープレフィックスを自動で付与、グリッドレイアウトをサポート
        cssdeclsort({ order: 'alphabetical' }), // CSSプロパティをアルファベット順にソート
      ])
    )
    .pipe(mmq())
    .pipe(sourcemaps.write('./'))
    .pipe(dest(destPath.css))
    .pipe(wpMode ? dest(destWpPath.css) : through2.obj())
    .pipe(wpLocalMode && wpMode ? dest(destWpLocalPath.css) : through2.obj())
    .pipe(rename({ suffix: '.min' }))
    .pipe(cleanCSS())
    .pipe(dest(destPath.css))
    .pipe(wpMode ? dest(destWpPath.css) : through2.obj())
    .pipe(wpLocalMode && wpMode ? dest(destWpLocalPath.css) : through2.obj())
    .pipe(notify({ message: 'Sassをコンパイルしました！', onLast: true }));
};

// * 画像圧縮（webpのみ保存、ただしSVGは元画像を保存）
const imgImageminWebpOnly = () => {
  return src(srcPath.img, { encoding: false })
    .pipe(changed(destPath.img)) // 画像の変更を監視
    .pipe(
      imagemin(
        [
          imageminMozjpeg({ quality: jpegQuality }),
          imageminPngquant(),
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
          }),
        ],
        {
          verbose: true,
        }
      )
    )
    .pipe(
      through2.obj(function (file, enc, cb) {
        // SVGはそのまま通す、それ以外はWebP変換用のストリームに流す
        if (file.extname === '.svg') {
          this.push(file);
        }
        cb(null, file);
      })
    )
    .pipe(dest(destPath.img)) // SVGを保存
    .pipe(wpMode ? dest(destWpPath.img) : through2.obj())
    .pipe(wpLocalMode && wpMode ? dest(destWpLocalPath.img) : through2.obj())
    .pipe(
      through2.obj(function (file, enc, cb) {
        // SVG以外のみWebP変換
        if (file.extname !== '.svg') {
          this.push(file);
        }
        cb();
      })
    )
    .pipe(webp()) // JPEG/PNGのみwebpに変換
    .pipe(dest(destPath.img))
    .pipe(wpMode ? dest(destWpPath.img) : through2.obj())
    .pipe(wpLocalMode && wpMode ? dest(destWpLocalPath.img) : through2.obj());
};

// * 画像圧縮（元画像+webp両方を保存）
const imgImageminWithOriginal = () => {
  return src(srcPath.img, { encoding: false })
    .pipe(changed(destPath.img)) // 画像の変更を監視
    .pipe(
      imagemin(
        [
          imageminMozjpeg({ quality: jpegQuality }), // JPEG圧縮品質（環境変数から取得）
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
    .pipe(webp())
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
  browserSyncOption.proxy = `http://${localSiteDomain}/`;
} else {
  browserSyncOption.server = '../dist/';
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

// * 画像以外のファイルの削除
const cleanWithoutImages = () => {
  // 画像以外のディレクトリとファイルを明示的に指定して削除
  const deleteTargets = [
    // dist配下
    '../dist/assets/css/**',
    '../dist/assets/js/**',
    '../dist/src/**',
    '../dist/**/*.html',
    '../dist/**/*.php',
    // distwp配下
    '../distwp/assets/css/**',
    '../distwp/assets/js/**',
    '../distwp/src/**',
    '../distwp/**/*.php',
    '../distwp/**/*.json',
    '../distwp/style.css',
  ];

  if (wpLocalMode) {
    deleteTargets.push(
      // WordPressLocal配下
      `${destWpLocalPath.css}/**`,
      `${destWpLocalPath.js}/**`,
      `${destWpLocalPath.sass}/**`,
      `${destWpLocalPath.php}/**/*.php`,
      `${destWpLocalPath.php}/**/*.json`,
      `${destWpLocalPath.php}/style.css`
    );
  }

  return deleteAsync(deleteTargets, { force: true });
};

// * ファイルの監視
const watchFiles = () => {
  watch(srcPath.sass, series(cssSass, browserSyncReload));
  watch(srcPath.js, series(jsBabel, browserSyncReload));
  watch(srcPath.img, series(imgImageminWebpOnly, browserSyncReload));
  if (wpMode) {
    watch(srcPath.php, series(phpCopy, browserSyncReload)); // WordPressの場合
  } else if (ejsMode) {
    watch(srcPath.ejs, series(ejsCompile, browserSyncReload)); // EJSの場合
  } else {
    watch(srcPath.html, series(htmlCopy, browserSyncReload)); // 静的コーディングの場合
  }
};

// * ファイルの監視（自動デプロイ付き）
const watchFilesWithDeploy = () => {
  if (!productionDeploy || !wpMode) {
    console.log('⚠️  自動デプロイが無効です');
    console.log('   PRODUCTION_DEPLOY=true かつ WP_MODE=true に設定してください');
    return;
  }

  console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
  console.log('🚀 自動デプロイモード起動');
  console.log('   ⚠️  ファイル保存時に本番サーバーへ自動転送されます');
  console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

  watch(srcPath.sass, series(cssSass, deployToProductionRsync, browserSyncReload));
  watch(srcPath.js, series(jsBabel, deployToProductionRsync, browserSyncReload));
  watch(srcPath.img, series(imgImageminWebpOnly, deployToProductionRsync, browserSyncReload));
  if (wpMode) {
    watch(srcPath.php, series(phpCopy, deployToProductionRsync, browserSyncReload));
  }
};

// * 本番サーバーへのデプロイ（rsync - 推奨）
const deployToProductionRsync = async () => {
  if (!productionDeploy || !wpMode) {
    console.log('⚠️  本番デプロイがスキップされました');
    console.log('   PRODUCTION_DEPLOY=true かつ WP_MODE=true に設定してください');
    return Promise.resolve();
  }

  console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
  console.log('🚀 本番サーバーへのデプロイ開始（rsync - 高速版）');
  console.log(`📁 ローカル: ${destWpPath.base}`);
  console.log(`📁 リモート: ${productionUser}@${productionHost}:${productionRemotePath}`);
  console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

  const privateKeyPath = productionPrivateKeyPath.replace(/^~/, userHomeDir);

  const rsyncCommand = [
    'rsync',
    '-avzh',
    '--itemize-changes', // 変更の詳細を表示
    '--progress',
    '--delete',
    `--exclude='.DS_Store'`,
    `--exclude='node_modules'`,
    `--exclude='.git'`,
    `-e "ssh -p ${productionPort} -i ${privateKeyPath} -o StrictHostKeyChecking=no"`,
    `${destWpPath.base}/`,
    `${productionUser}@${productionHost}:${productionRemotePath}/`,
  ].join(' ');

  try {
    console.log('⏳ rsync実行中...\n');
    const { stdout, stderr } = await execPromise(rsyncCommand, {
      maxBuffer: 1024 * 1024 * 10, // 10MBバッファ
    });

    if (stdout) {
      const lines = stdout.trim().split('\n');

      // 転送されたファイルのみを抽出（.で始まる行以外）
      const transferredFiles = lines.filter((line) => {
        // rsyncの出力形式: 最初の文字が操作を示す（>:送信, c:変更, d:削除など）
        return line.match(/^[><ch.*][fdLDS]/);
      });

      if (transferredFiles.length > 0) {
        console.log('\n📤 転送されたファイル:');
        console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        transferredFiles.forEach((file) => {
          // itemize-changesの記号を日本語で説明
          const symbol = file.substring(0, 2);
          const fileName = file.substring(11).trim();
          let status = '';
          if (symbol.startsWith('>f')) status = '📄 [更新]';
          else if (symbol.startsWith('cd')) status = '📁 [新規]';
          else if (symbol.startsWith('*d')) status = '🗑️  [削除]';
          else status = '📝 [変更]';

          console.log(`${status} ${fileName}`);
        });
        console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        console.log(`✅ ${transferredFiles.length}個のファイルを転送しました\n`);
      } else {
        console.log('\n✅ 転送するファイルはありませんでした（すべて最新）\n');
      }

      // 最後のサマリー情報を表示
      const summaryLines = lines.slice(-5);
      console.log('📊 転送サマリー:');
      console.log(summaryLines.join('\n'));
    }

    if (stderr && !stderr.includes('Warning')) {
      console.warn('⚠️  警告:', stderr);
    }

    console.log('\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    console.log('✅ デプロイ完了！');
    console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

    notify({ message: '本番サーバーへデプロイしました（rsync）！', onLast: true });
    return Promise.resolve();
  } catch (error) {
    console.error('\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    console.error('❌ デプロイ中にエラーが発生しました:');
    console.error(error.message);
    if (error.stderr) {
      console.error('\n詳細エラー:', error.stderr);
    }
    console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    throw error;
  }
};

// * SSH接続テスト
const testSSHConnection = (done) => {
  if (!productionDeploy) {
    console.log('⚠️  PRODUCTION_DEPLOY=false のため、接続テストをスキップします');
    console.log('   .env.local で PRODUCTION_DEPLOY=true に設定してください');
    done();
    return;
  }

  console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
  console.log('🔌 SSH接続テスト開始');
  console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
  console.log(`📍 ホスト: ${productionHost}`);
  console.log(`🔌 ポート: ${productionPort}`);
  console.log(`👤 ユーザー: ${productionUser}`);
  console.log(`🔑 秘密鍵: ${productionPrivateKeyPath}`);
  console.log(`📁 アップロード先: ${productionRemotePath}`);
  console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

  try {
    const privateKeyPath = productionPrivateKeyPath.replace(/^~/, userHomeDir);

    const gulpSSH = new GulpSSH({
      ignoreErrors: false,
      sshConfig: {
        host: productionHost,
        port: productionPort,
        username: productionUser,
        privateKey: fs.readFileSync(privateKeyPath),
      },
    });

    const themesPath = `${productionSiteRoot}/wp-content/themes`;
    return gulpSSH.exec(['pwd', `ls -la ${themesPath}`], { filePath: 'test.log' }).on('finish', () => {
      console.log('✅ SSH接続に成功しました！');
      console.log(`📂 themesディレクトリ: ${themesPath}`);
      console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
      done();
    });
  } catch (error) {
    console.log('\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    console.log('❌ SSH接続に失敗しました');
    console.error(error.message);
    console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    done(error);
  }
};

// ! ブラウザシンク付きの開発用タスク
export default series(
  series(cssSass, cssCopy, othersCopy, sassCopy, jsBabel, imgImageminWebpOnly, htmlCopy, ejsCompile, phpCopy),
  parallel(watchFiles, browserSyncFunc)
);

// ! 本番用ビルドタスク（webpのみ保存）
export const build = series(
  clean,
  cssSass,
  cssCopy,
  othersCopy,
  sassCopy,
  jsBabel,
  imgImageminWebpOnly,
  htmlCopy,
  ejsCompile,
  phpCopy
);

// ! 本番用ビルドタスク（元画像+webp両方を保存）
const buildWithOriginal = series(
  clean,
  cssSass,
  cssCopy,
  othersCopy,
  sassCopy,
  jsBabel,
  imgImageminWithOriginal,
  htmlCopy,
  ejsCompile,
  phpCopy
);
export { buildWithOriginal as 'build-with-original' };

// ! 画像以外のビルドタスク（既存の画像を保持）
const buildWithoutImages = series(
  cleanWithoutImages,
  cssSass,
  cssCopy,
  othersCopy,
  sassCopy,
  jsBabel,
  htmlCopy,
  ejsCompile,
  phpCopy
);
export { buildWithoutImages as 'build-without-images' };

// ! デプロイタスク
export const deploy_only = deployToProductionRsync;
export const deploy = series(build, deployToProductionRsync);
const deployWithOriginal = series(buildWithOriginal, deployToProductionRsync);
export { deployWithOriginal as 'deploy-with-original' };

// ! ユーティリティタスク
export const ssh_test = testSSHConnection;
export { clean, cleanWithoutImages, cssSass, jsBabel, imgImageminWebpOnly, imgImageminWithOriginal };

// ! 自動デプロイ付き監視タスク（本番環境用）
const watchDeploy = series(
  series(cssSass, cssCopy, othersCopy, sassCopy, jsBabel, imgImageminWebpOnly, phpCopy),
  parallel(watchFilesWithDeploy, browserSyncFunc)
);
export { watchDeploy as 'watch-deploy' };
