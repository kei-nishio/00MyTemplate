<?php global $theme_uri; ?>
<?php get_header(); ?>
<main class="main">
  <?php // loading 
  ?>
  <div class="p-loading js-loading">
    <div class="p-loading__inner">
    </div>
  </div>
  <?php // FV 
  ?>
  <section class="l-fv p-fv">
    <div class="p-fv__inner">
      <div class="p-fv__slide js-swiper-fv">
        <div class="swiper">
          <ul class="swiper-wrapper">
            <?php for ($i = 1; $i <= 2; $i++) : ?>
              <li class="swiper-slide">
                <p class="p-fv__img">
                  <picture>
                    <source srcset="<?php echo esc_url($theme_uri . '/assets/images/top/fv' . $i . '-sp.webp'); ?>" type="image/webp" media="(max-width: 767px)" />
                    <source srcset="<?php echo esc_url($theme_uri . '/assets/images/top/fv' . $i . '-sp.jpg'); ?>" type="image/jpeg" media="(max-width: 767px)" />
                    <source srcset="<?php echo esc_url($theme_uri . '/assets/images/top/fv' . $i . '-pc.webp'); ?>" type="image/webp" media="(min-width: 768px)" />
                    <source srcset="<?php echo esc_url($theme_uri . '/assets/images/top/fv' . $i . '-pc.jpg'); ?>" type="image/jpeg" media="(min-width: 768px)" />
                    <img src="<?php echo esc_url($theme_uri . '/assets/images/top/fv' . $i . '-pc.jpg'); ?>" alt="" decoding="async" width="640" height="320" />
                  </picture>
                </p>
              </li>
            <?php endfor; ?>
          </ul>
        </div>
      </div>
      <h2 class="p-fv__heading">fvfvfvfvfvfvfvfvfvfv</h2>
    </div>
  </section>

  <script>
    // Flash of Unstyled Content（FOUC）対策
    // const hasVisited = sessionStorage.getItem('hasVisited');
    const hasVisited = false; // for debug
    const loading = document.querySelector('.js-loading');
    const loadingHides = document.querySelectorAll('.js-loadingHide');

    if (!hasVisited) {
      loading?.classList.add('is-active');
      loadingHides.forEach((el) => {
        el.style.visibility = 'hidden';
        el.style.opacity = '0';
      });
    } else {
      loading?.classList.remove('is-active');
    }
  </script>

  <?php // sub query grid
  ?>
  <?php
  $post_type = 'post';
  $num = wp_is_mobile() ? 3 : 3;
  $args = [
    'post_type' => $post_type,
    'posts_per_page' => $num,
    'orderby' => 'date',
    'order' => 'DESC',
  ];
  $the_query = new WP_query($args);
  ?>
  <section class="l-section p-section-grid">
    <div class="p-section-grid__inner l-inner">
      <div class="p-section-grid__heading">
        <?php get_template_part('/parts/c-section-title', "", ["title" => 'グリッド', "subtitle" => 'hoge', "modifier" => "hoge"]); ?>
      </div>
      <div class="p-section-grid__content">
        <?php if ($the_query->have_posts()) : ?>
          <ul class="p-section-grid__list">
            <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
              <li class="p-section-grid__item">
                <?php $post_id = get_the_ID(); ?>
                <?php get_template_part('/parts/c-card', $post_type, ["post_id" => $post_id]); ?>
              </li>
            <?php endwhile; ?>
          </ul>
          <div class="p-section-grid__button">
            <div class="c-button-normal"><a href="<?php echo esc_url(home_url('/news')); ?>">もっと見る</a></div>
          </div>
        <?php else : ?>
          <p style="text-align: center; margin-top: 20px; ">記事が投稿されていません。</p>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
      </div>
    </div>
  </section>

  <?php // sub query swiper
  ?>
  <?php
  $post_type = 'post'; // default : post
  $num = wp_is_mobile() ? 3 : 6;
  $args = [
    'post_type' => $post_type,
    'posts_per_page' => $num,
    'orderby' => 'date',
    'order' => 'DESC',
    'tax_query' => [ // タームを指定する場合
      [
        'taxonomy' => 'news-type', // default : category
        'terms' => 'type1',
        'field' => 'slug', // スラッグで指定する（変更不要）
      ],
    ]
  ];
  $the_query = new WP_query($args);
  ?>
  <section class="l-section p-section-slide">
    <div class="p-section-slide__inner l-inner">
      <div class="p-section-slide__heading">
        <?php get_template_part('/parts/c-section-title', "", ["title" => 'スワイパー', "subtitle" => 'fuga', "modifier" => ""]); ?>
      </div>
      <div class="p-section-slide__content">
        <?php if ($the_query->have_posts()) : ?>
          <div class="p-section-slide__slide js-swiper">
            <div class="swiper">
              <ul class="swiper-wrapper">
                <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
                  <li class="swiper-slide">
                    <?php $post_id = get_the_ID(); ?>
                    <?php get_template_part('/parts/c-card', $post_type, ["post_id" => $post_id]); ?>
                  </li>
                <?php endwhile; ?>
              </ul>
            </div>
            <div class="swiper-equipment">
              <div class="swiper-button-prev"></div>
              <div class="swiper-scrollbar"></div>
              <div class="swiper-pagination"></div>
              <div class="swiper-button-next"></div>
            </div>
          </div>
          <div class="p-section-slide__button">
            <div class="c-button-normal"><a href="<?php echo esc_url(home_url('/news')); ?>">もっと見る</a></div>
          </div>
        <?php else : ?>
          <p style="text-align: center; margin-top: 20px; ">記事が投稿されていません。</p>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
      </div>
    </div>
  </section>

  <?php // table
  ?>
  <section class="l-section p-section-table">
    <div class="p-section-table__inner l-inner">
      <div class="p-section-table__heading">
        <?php get_template_part('/parts/c-section-title', "", ["title" => 'テーブル', "subtitle" => 'piyo', "modifier" => "piyo"]); ?>
      </div>
      <div class="p-section-table__content">
        <table class="p-section-table__table">
          <thead>
            <tr>
              <th scope="col">名前</th>
              <th scope="col">年齢</th>
              <th scope="col">出身国</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">山田太郎</th>
              <td>20</td>
              <td>日本</td>
            </tr>
            <tr>
              <th scope="row">John Smith</th>
              <td>30</td>
              <td>USA</td>
            </tr>
            <tr>
              <th scope="row">Jean Dupont</th>
              <td>40</td>
              <td>France</td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <th scope="row">合計</th>
              <td>3</td>
            </tr>
          </tfoot>
        </table>

        <div class="p-section-table__button">
          <div class="c-button-normal c-button-normal--white"><a href="<?php echo esc_url(home_url('/news')); ?>">もっと見る</a></div>
        </div>
      </div>
    </div>
  </section>

  <?php // faq
  ?>
  <section class="p-faq">
    <div class="p-faq__inner l-inner">
      <div class="p-faq__heading">
        <?php get_template_part('/parts/c-section-title', "", ["title" => 'よくある質問', "subtitle" => 'FAQ', "modifier" => ""]); ?>
      </div>
      <div class="p-faq__content">
        <dl class="p-faq__list">
          <div class="p-faq__item js-slide-toggle">
            <dt class="p-faq__q"><span>ここに質問が入ります。ここに質問が入ります。ここに質問が入ります。</span></dt>
            <dd class="p-faq__a"><span>ここに回答が入ります。ここに回答が入ります。ここに回答が入ります。ここに回答が入ります。ここに回答が入ります。ここに回答が入ります。</span></dd>
          </div>
          <div class="p-faq__item js-slide-toggle">
            <dt class="p-faq__q"><span>ここに質問が入ります。ここに質問が入ります。ここに質問が入ります。</span></dt>
            <dd class="p-faq__a"><span>ここに回答が入ります。ここに回答が入ります。ここに回答が入ります。ここに回答が入ります。ここに回答が入ります。ここに回答が入ります。</span></dd>
          </div>
        </dl>
      </div>
    </div>
  </section>

</main>
<?php get_footer(); ?>