<?php
// ! メンテナンスモード

/**
 * メンテナンスモード用テンプレート
 * 
 * 【静的コーディングサイト向けのメンテナンスモード設定】
 * 静的サイトの場合は、以下の手順でメンテナンスモードを設定してください:
 * 
 * 1. maintenance.html ファイルをルートディレクトリに配置
 * 2. .htaccess に以下を記述:
 * 
 * RewriteEngine On
 * RewriteCond %{REQUEST_URI} !/maintenance.html$
 * RewriteRule ^(.*)$ /maintenance.html [R=503,L]
 * RewriteRule ^(.*)$ /maintenance.html [L] ＊サーバーによってはこっちを使う場合もあります
 * 
 */

add_action('template_redirect', function () {
    if (is_admin() || wp_doing_ajax() || wp_doing_cron()) return;

    if (!current_user_can('manage_options')) {
        status_header(503);
        nocache_headers();
        include get_theme_file_path('maintenance.php');
        exit;
    }
});
