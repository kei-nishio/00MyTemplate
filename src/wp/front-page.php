<?php get_header(); ?>
<main class="main">

  <!-- //! 通常投稿の新着一覧 -->
  <article class="">
    <ul class="">
      <!-- 記事のループ処理開始 -->
      <?php
      if (wp_is_mobile()) {
        $num = 3; // スマホの表示数(全件は-1)
      } else {
        $num = 5; // PCの表示数(全件は-1)
      }
      $args = [
        'post_type' => 'post', // 投稿タイプのスラッグ(通常投稿は'post')
        'posts_per_page' => $num, // 表示件数
      ];
      $the_query = new WP_Query($args);
      if ($the_query->have_posts()) :
        while ($the_query->have_posts()) : $the_query->the_post();
      ?>
          <li class="">
            <!-- 記事へのリンク -->
            <a href="<?php the_permalink(); ?>" class="">
              <!-- アイキャッチ -->
              <div class="">
                <?php the_post_thumbnail('post-thumbnail', array('alt' => the_title_attribute('echo=0'))); ?>
              </div>
              <p class="">
                <!-- 投稿日 -->
                <time datetime="<?php the_time('Y-m-d'); ?>">
                  <?php the_time('Y.m.d'); ?>
                </time>
              </p>
              <div class="">
                <!-- カテゴリー1件表示(カテゴリー順の上にある方が表示される) -->
                <?php
                $category = get_the_category();
                echo '<span class="' . $category->slug . '">' . $category[0]->name . '</span>';
                ?>
                <!-- カテゴリー全部表示 -->
                <?php
                $categories = get_the_category();
                foreach ($categories as $cat) {
                  echo '<span class="' . $cat->slug . '">' . $cat->name . '</span>';
                }
                ?>
              </div>
              <h3 class="">
                <!-- タイトル -->
                <?php the_title(); ?>
              </h3>
              <div class="">
                <!-- 本文の抜粋 -->
                <?php the_excerpt(); ?>
              </div>
            </a>
          </li>
        <?php endwhile;
      else : ?>
        <p>まだ記事がありません</p>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
      <!-- 記事のループ処理終了 -->
    </ul>
  </article>

  <!-- //! カスタム投稿の新着一覧 -->
  <article class="">
    <ul class="">
      <!-- 記事のループ処理開始 -->
      <?php
      if (wp_is_mobile()) {
        $num = 4; // スマホの表示数(全件は-1)
      } else {
        $num = 8; // PCの表示数(全件は-1)
      }
      // 投稿タイプのみ指定する場合
      $args = [
        'post_type' => 'blog', // 投稿タイプのスラッグ
        'posts_per_page' => $num, // 表示件数（変更不要）
        // カテゴリー(ターム)を指定する場合はここを追記↓
        'tax_query' => array(
          array(
            'taxonomy' => 'blog_category', // タクソノミーのスラッグ
            'terms' => 'recommend', // タームのスラッグ
            'field' => 'slug', // ターム名をスラッグで指定する（変更不要）
          ),
        )
        // 追記はここまで↑
      ];
      $the_query = new WP_Query($args);
      if ($the_query->have_posts()) :
        while ($the_query->have_posts()) : $the_query->the_post();
      ?>
          <li class="">
            <!-- 投稿へのリンク -->
            <a href="<?php the_permalink(); ?>" class="">
              <!-- アイキャッチ -->
              <div>
                <?php the_post_thumbnail('post-thumbnail', array('alt' => the_title_attribute('echo=0'))); ?>
              </div>
              <p class="">
                <!-- 投稿日 -->
                <time datetime="<?php the_time('Y-m-d'); ?>">
                  <?php the_time('Y.m.d'); ?>
                </time>
              </p>
              <div class="">
                <!-- カテゴリー(ターム)を全部表示 -->
                <?php
                $taxonomy_terms = get_the_terms($post->ID, 'タクソノミースラッグ');
                foreach ($taxonomy_terms as $taxonomy_term) {
                  echo '<span class="' . $taxonomy_term->slug . '">' . $taxonomy_term->name . '</span>';
                }
                ?>
                <!-- カテゴリー(ターム)を指定して表示 -->
                <?php
                $taxonomy_terms = get_the_terms($post->ID, 'タクソノミースラッグ');
                foreach ($taxonomy_terms as $taxonomy_term) {
                  if (!in_array($taxonomy_term->slug, array('表示したいタームスラッグ', '表示したいタームスラッグ')))
                    continue;
                  echo '<span class="' . $taxonomy_term->slug . '">' . $taxonomy_term->name . '</span>';
                }
                ?>
                <!-- カテゴリー(ターム)を除外して表示 -->
                <?php
                $taxonomy_terms = get_the_terms($post->ID, 'タクソノミースラッグ');
                foreach ($taxonomy_terms as $taxonomy_term) {
                  if (in_array($taxonomy_term->slug, array('除外したいタームスラッグ', '除外したいタームスラッグ')))
                    continue;
                  echo '<span class="' . $taxonomy_term->slug . '">' . $taxonomy_term->name . '</span>';
                }
                ?>
              </div>
              <h3 class="">
                <!-- タイトル -->
                <?php the_title(); ?>
              </h3>
              <div class="">
                <!-- 本文の抜粋 -->
                <?php the_excerpt(); ?>
              </div>
            </a>
          </li>
        <?php endwhile;
      else : ?>
        <p>まだ記事がありません</p>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
      <!-- 記事のループ処理終了 -->
    </ul>
  </article>

</main>
<?php get_footer(); ?>