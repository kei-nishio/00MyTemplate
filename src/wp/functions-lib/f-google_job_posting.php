<?php

/**
 * Googleしごと検索用の構造化データ（JSON-LD）を出力する関数
 * 
 * @param array $options オプション配列
 *   - 'description_field' string ACFフィールド名（募集内容）デフォルト: 'acf_google_work_job_description'
 *   - 'end_date_field' string ACFフィールド名（掲載終了日）デフォルト: 'acf_google_work_end_date'
 *   - 'location_group_field' string ACFグループフィールド名（勤務地）デフォルト: 'acf_google_work_location_group'
 *   - 'company_group_field' string ACFグループフィールド名（企業情報）デフォルト: 'acf_google_work_company_info_group'
 *   - 'post_id' int 投稿ID（省略時は現在の投稿）
 *   - 'show_debug_comment' bool デバッグコメントを表示するか デフォルト: true
 * 
 * @return void
 */
function output_google_job_posting_schema($options = array())
{
  // デフォルトオプション
  $defaults = array(
    'description_field' => 'acf_google_work_job_description',
    'end_date_field' => 'acf_google_work_end_date',
    'location_group_field' => 'acf_google_work_location_group',
    'company_group_field' => 'acf_google_work_company_info_group',
    'post_id' => get_the_ID(),
    'show_debug_comment' => true,
  );

  $options = wp_parse_args($options, $defaults);

  // ACFフィールドを取得
  $google_description = get_field($options['description_field'], $options['post_id']);
  $google_end_date = get_field($options['end_date_field'], $options['post_id']);
  $google_location = get_field($options['location_group_field'], $options['post_id']);
  $google_company = get_field($options['company_group_field'], $options['post_id']);

  // 最低限、募集内容があれば構造化データを出力
  if (!empty($google_description)) :
    // 住所の組み立て
    $posting_location_address = array(
      '@type' => 'PostalAddress',
    );

    // 国（デフォルト: 日本）
    if (!empty($google_location['acf_google_work_country'])) {
      $posting_location_address['addressCountry'] = $google_location['acf_google_work_country'];
    } else {
      $posting_location_address['addressCountry'] = 'JP';
    }

    // 郵便番号
    if (!empty($google_location['acf_google_work_postal_code'])) {
      $posting_location_address['postalCode'] = $google_location['acf_google_work_postal_code'];
    }

    // 都道府県
    if (!empty($google_location['acf_google_work_prefecture'])) {
      $posting_location_address['addressRegion'] = $google_location['acf_google_work_prefecture'];
    }

    // 市区町村
    if (!empty($google_location['acf_google_work_city'])) {
      $posting_location_address['addressLocality'] = $google_location['acf_google_work_city'];
    }

    // 町名番地
    if (!empty($google_location['acf_google_work_address'])) {
      $posting_location_address['streetAddress'] = $google_location['acf_google_work_address'];
    }

    // HTMLタグを除去してプレーンテキスト化（Googleの推奨に従う）
    $clean_description = wp_strip_all_tags($google_description);

    // 企業名
    $company_name = get_bloginfo('name');
    if (!empty($google_company['acf_google_work_company_name'])) {
      $company_name = $google_company['acf_google_work_company_name'];
    }

    // 企業URL
    $company_url = home_url();
    if (!empty($google_company['acf_google_work_company_url'])) {
      $company_url = $google_company['acf_google_work_company_url'];
    }

    // 投稿日
    $date_posted = get_the_date('c', $options['post_id']);

    $schema = array(
      '@context' => 'https://schema.org/',
      '@type' => 'JobPosting',
      'title' => get_the_title($options['post_id']),
      'description' => $clean_description,
      'datePosted' => $date_posted,
      'hiringOrganization' => array(
        '@type' => 'Organization',
        'name' => $company_name,
        'sameAs' => $company_url,
      ),
      'jobLocation' => array(
        '@type' => 'Place',
        'address' => $posting_location_address,
      ),
    );

    // ロゴ画像（推奨）
    if (!empty($google_company['acf_google_work_logo_url'])) {
      $schema['hiringOrganization']['logo'] = $google_company['acf_google_work_logo_url'];
    }

    // 掲載終了日（推奨）
    if (!empty($google_end_date)) {
      // 日本時間で23:59:59まで有効
      $end_datetime = new DateTime($google_end_date . ' 23:59:59', new DateTimeZone('Asia/Tokyo'));
      $schema['validThrough'] = $end_datetime->format('c');
    }

    echo '<!-- Googleしごと検索用の構造化データ 開始 -->' . "\n";
    echo '<script type="application/ld+json">' . "\n";
    echo wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    echo "\n" . '</script>' . "\n";
    echo '<!-- /Googleしごと検索用の構造化データ 終了 -->' . "\n";
  else :
    // デバッグ用コメント
    if ($options['show_debug_comment']) {
      echo '<!-- Googleしごと検索: 募集内容(' . esc_html($options['description_field']) . ')が入力されていません -->' . "\n";
    }
  endif;
}
