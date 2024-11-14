<?php get_header(); ?>
<main class="main">
  <?php // loading 
  ?>
  <div class="p-loading js-loading">
    <div class="p-loading__inner">
    </div>
  </div>
  <script>
    // const hasFirstVisited = sessionStorage.getItem('hasVisitedTopPage');
    // let loadingElements = document.querySelectorAll('.js-loading');
    // let loadingHideOnHTML = document.querySelectorAll('.js-loadingHide');
    // if (!hasFirstVisited) {
    //   // loading on
    //   if (loadingElements.length > 0) {
    //     loadingElements.forEach((element) => {
    //       element.classList.add("is-loading");
    //     });
    //   }
    //   if (loadingHideOnHTML.length > 0) {
    //     loadingHideOnHTML.forEach((element) => {
    //       element.style.visibility = 'hidden';
    //       element.style.opacity = '0';
    //     });
    //   }
    // } else {
    //   // loading off
    //   if (loadingElements.length > 0) {
    //     loadingElements.forEach((element) => {
    //       element.classList.remove("is-loading");
    //     });
    //   }
    // }
  </script>
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
                    <source srcset="<?php echo get_theme_file_uri(); ?>/assets/images/top/fv<?php echo $i ?>-sp.webp" type="image/webp" media="(max-width: 767px)" />
                    <source srcset="<?php echo get_theme_file_uri(); ?>/assets/images/top/fv<?php echo $i ?>-sp.jpg" type="image/jpeg" media="(max-width: 767px)" />
                    <source srcset="<?php echo get_theme_file_uri(); ?>/assets/images/top/fv<?php echo $i ?>-pc.webp" type="image/webp" media="(min-width: 768px)" />
                    <source srcset="<?php echo get_theme_file_uri(); ?>/assets/images/top/fv<?php echo $i ?>-pc.jpg" type="image/jpeg" media="(min-width: 768px)" />
                    <img src="<?php echo get_theme_file_uri(); ?>/assets/images/top/fv<?php echo $i ?>-pc.jpg" alt="" decoding="async" width="640" height="320" />
                  </picture>
                </p>
              </li>
            <?php endfor; ?>
          </ul>
        </div>
      </div>
      <div class="p-fv__heading"></div>
    </div>
  </section>

  <?php // sub query grid
  ?>
  <?php
  $num = wp_is_mobile() ? 3 : 3;
  $args = [
    'post_type' => 'news',
    'posts_per_page' => $num,
    'orderby' => 'date',
    'order' => 'DESC',
  ];
  $the_query = new WP_query($args);
  ?>
  <section class="l-section p-section-hoge">
    <div class="p-section-hoge__inner l-inner">
      <div class="p-section-hoge__heading">
        <?php get_template_part('/parts/c-section-title', "", ["title" => 'グリッド', "subtitle" => 'hoge', "modifier" => "hoge"]); ?>
      </div>
      <div class="p-section-hoge__content">
        <?php if ($the_query->have_posts()) : ?>
          <ul class="p-section-hoge__list">
            <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
              <li class="p-section-hoge__item">
                <?php $post_id = get_the_ID(); ?>
                <?php get_template_part('/parts/c-card', "", ["post_id" => $post_id]); ?>
              </li>
            <?php endwhile; ?>
          </ul>
          <div class="p-section-hoge__button">
            <div class="c-button-normal"><a href="<?php echo esc_url(home_url('/news')); ?>">もっと見る</a></div>
          </div>
        <?php else : ?>
          <p style="text-align: center;">記事が投稿されていません。</p>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
      </div>
    </div>
  </section>

  <?php // sub query swiper
  ?>
  <?php
  $num = wp_is_mobile() ? 3 : 6;
  $args = [
    'post_type' => 'news',
    'posts_per_page' => $num,
    'orderby' => 'date',
    'order' => 'DESC',
    'tax_query' => [ // タームを指定する場合
      [
        'taxonomy' => 'news-type',
        'terms' => 'type1',
        'field' => 'slug', // スラッグで指定する（変更不要）
      ],
    ]
  ];
  $the_query = new WP_query($args);
  ?>
  <section class="l-section p-section-fuga">
    <div class="p-section-fuga__inner l-inner">
      <div class="p-section-fuga__heading">
        <?php get_template_part('/parts/c-section-title', "", ["title" => 'スワイパー', "subtitle" => 'fuga', "modifier" => ""]); ?>
      </div>
      <div class="p-section-fuga__content">
        <?php if ($the_query->have_posts()) : ?>
          <div class="p-section-fuga__slide js-swiper">
            <div class="swiper">
              <ul class="swiper-wrapper">
                <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
                  <li class="swiper-slide">
                    <?php $post_id = get_the_ID(); ?>
                    <?php get_template_part('/parts/c-card', "", ["post_id" => $post_id]); ?>
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
          <div class="p-section-fuga__button">
            <div class="c-button-normal"><a href="<?php echo esc_url(home_url('/news')); ?>">もっと見る</a></div>
          </div>
        <?php else : ?>
          <p style="text-align: center;">記事が投稿されていません。</p>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
      </div>
    </div>
  </section>

  <?php // table
  ?>
  <section class="l-section p-section-piyo">
    <div class="p-section-piyo__inner l-inner">
      <div class="p-section-piyo__heading">
        <?php get_template_part('/parts/c-section-title', "", ["title" => 'テーブル', "subtitle" => 'piyo', "modifier" => "piyo"]); ?>
      </div>
      <div class="p-section-piyo__content">
        <?php
        $json_url =  get_theme_file_uri('/data/table.json');
        $json_data = file_get_contents($json_url);
        $data = json_decode($json_data, true);
        ?>
        <table class="p-section-piyo__table">
          <thead>
            <tr>
              <th scope="col"><?php echo esc_html($data['header']['name']); ?></th>
              <th scope="col"><?php echo esc_html($data['header']['age']); ?></th>
              <th scope="col"><?php echo esc_html($data['header']['country']); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data['body'] as $row) : ?>
              <tr>
                <th scope="row"><?php echo esc_html($row['name']); ?></th>
                <td><?php echo esc_html($row['age']); ?></td>
                <td><?php echo esc_html($row['country']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr>
              <th scope="row"><?php echo esc_html($data['footer']['result']); ?></td>
              <td><?php echo esc_html($data['footer']['total']); ?></td>
            </tr>
          </tfoot>
        </table>

        <div class="p-section-piyo__button">
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
          <div class="p-faq__item js-accordion">
            <dt class="p-faq__q"><span>ここに質問が入ります。ここに質問が入ります。ここに質問が入ります。</span></dt>
            <dd class="p-faq__a"><span>ここに回答が入ります。ここに回答が入ります。ここに回答が入ります。ここに回答が入ります。ここに回答が入ります。ここに回答が入ります。</span></dd>
          </div>
          <div class="p-faq__item js-accordion">
            <dt class="p-faq__q"><span>ここに質問が入ります。ここに質問が入ります。ここに質問が入ります。</span></dt>
            <dd class="p-faq__a"><span>ここに回答が入ります。ここに回答が入ります。ここに回答が入ります。ここに回答が入ります。ここに回答が入ります。ここに回答が入ります。</span></dd>
          </div>
        </dl>
      </div>
    </div>
  </section>

</main>
<?php get_footer(); ?>