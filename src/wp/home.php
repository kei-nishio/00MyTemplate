<?php get_header(); ?>
<main class="main">
  <section class="sub-news sub-top">
    <div class="sub-top__inner l-inner">
      <div class="sub-top__heading">
        <h1 class="sub-top__title">news</h1>
      </div>

      <!-- breadcrumb -->
      <?php get_template_part('/parts/component/c-breadcrumb'); ?>

      <?php if (have_posts()) : ?>
        <!-- categories SP-->
        <div class="sub-news__category category-list u-mobile">
          <?php
          ?>
          <label for="news-category" style="display: none;">カテゴリから探す</label>
          <select id="news-category" class="category-list__select js-news-category" style="appearance: auto;" onchange="redirectToUrl(this)">
            <option value="" selected class="category-list__option">カテゴリから探す</option>
            <!-- ! category loop start -->
            <?php
            $categories = get_categories(array(
              'orderby' => 'ID',
              'order'   => 'ASC',
            ));
            if (!empty($categories)) {
              foreach ($categories as $category) {
                $add_link = esc_url(get_category_link($category->term_id));
                echo '<option value="' . $add_link . '" class="category-list__option">' . esc_html($category->name) . '</option>';
              }
            }
            ?>
            <!-- ! category loop end -->
          </select>
        </div>

        <!-- categories PC-->
        <div class="sub-news__category category-list u-desktop">
          <ul class="category-list__items">
            <?php
            $class1 = 'category-list__item';
            $class2 = 'category-list__link';

            // 通常投稿ページへのリンクを表示（現ページへのリンクはなし）
            $posts_page_id = get_option('page_for_posts'); // 投稿ページのIDを取得
            $posts_page_slug = get_post_field('post_name', $posts_page_id); // 投稿ページのスラッグを取得
            if (is_home()) :
              $add_class1 = $class1 . ' is-active';
              $add_tag = '<div class="' . $class2 . '">all</div>';
            else :
              $add_class1 = $class1;
              $add_link = esc_url(home_url('/' . $posts_page_slug)); // 投稿ページのURLを取得
              $add_tag = '<a href="' . $add_link . '" class="' . $class2 . '" title="View all posts">all</a>';
            endif;
            $home_link = '<li class="' . $add_class1 . '">' . $add_tag . '</li>';
            echo $home_link;
            ?>
            <?php
            // タクソノミーのリンク
            $current_category_id = get_queried_object_id();
            $categories = get_categories(array(
              'orderby' => 'ID',
              'order'   => 'ASC',
              // 'number'  => 3
            ));
            if ($categories) :
              foreach ($categories as $category) :
                $category_name = esc_html($category->name);
                if ($current_category_id === $category->term_id) :
                  $add_class1 = $class1 . ' is-active';
                  $add_tag = '<div class="' . $class2 . '">' . $category_name . '</div>';
                else :
                  $add_class1 = $class1;
                  $add_link = esc_url(get_category_link($category->term_id)); // カテゴリーページの場合
                  // $add_link = esc_url(get_term_link($category, $taxonomy)); // タクソノミーページの場合
                  $add_tag = '<a href="' . $add_link . '" class="' . $class2 . '" title="View posts in ' . $category_name . '">' . $category_name . '</a>';
                endif;
                $category_link = '<li class="' . $add_class1 . '">' . $add_tag . '</li>';
                echo $category_link;
              endforeach;
            endif;
            ?>
          </ul>
        </div>
      <?php endif; ?>

      <!-- post list -->
      <div class="sub-news__list list-news">
        <ul class="list-news__items">
          <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
              <?php if (get_the_title() !== "") : ?>
                <?php
                $cat = get_the_category();
                $cat = $cat[0]->cat_name;
                ?>
                <li class="list-news__item">
                  <a href="<?php the_permalink(); ?>" class="list-news__link">
                    <time class="list-news__time time-tag" datetime="<?php the_time("c"); ?>">
                      <?php if (wp_is_mobile()) : ?>
                        <?php the_time("y.m.d"); ?>
                      <?php else : ?>
                        <?php the_time("Y.m.d"); ?>
                      <?php endif; ?>
                    </time>
                    <span class="list-news__category category-tag"><?php echo $cat; ?></span>
                    <p class="list-news__title">
                      <?php if (wp_is_mobile()) : ?>
                        <?php echo esc_html(mb_strimwidth(get_the_title(), 0, 20 * 2 + 3, '...')); ?>
                      <?php else : ?>
                        <?php echo esc_html(mb_strimwidth(get_the_title(), 0, 25 * 2 + 3, '...')); ?>
                      <?php endif; ?>
                    </p>
                  </a>
                </li>
              <?php endif; ?>
            <?php endwhile; ?>
          <?php else : ?>
            <li>記事が投稿されていません</li>
          <?php endif; ?>
        </ul>
      </div>

      <!-- pagination -->
      <div class="sub-news__pagination">
        <?php
        $args = array(
          'mid_size' => 2,
          'prev_text' => '<img src="' . esc_url(get_theme_file_uri("/assets/images/common/arrow-black.svg")) . '" alt="" >',
          'next_text' => '<img src="' . esc_url(get_theme_file_uri("/assets/images/common/arrow-black.svg")) . '" alt="" >',
        );
        the_posts_pagination($args);
        ?>
      </div>

    </div>
  </section>
</main>
<?php get_footer(); ?>