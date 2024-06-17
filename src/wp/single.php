<?php get_header(); ?>
<main class="main">
  <section class="single sub-top">
    <div class="sub-top__inner inner">
      <div class="sub-top__heading">
        <h1 class="sub-top__title">news</h1>
      </div>

      <!-- breadcrumb -->
      <?php get_template_part('/parts/common/p-breadcrumb'); ?>


      <div class="single__flex">
        <!-- post list -->
        <article class="single__main">
          <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
              <?php
              $cat = get_the_category();
              $cat = $cat[0]->cat_name;
              ?>
              <time class="single__time time-tag time-tag--large " datetime="<?php the_time("c"); ?>">
                <?php if (wp_is_mobile()) : ?>
                  <?php the_time("y.m.d"); ?>
                <?php else : ?>
                  <?php the_time("Y.m.d"); ?>
                <?php endif; ?>
              </time>
              <span class="single__category category-tag"><?php echo $cat; ?></span>
              <h2 class="single__title"><?php the_title() ?></h2>
              <div class="single__body"><?php the_content(); ?></div>
            <?php endwhile; ?>
          <?php else : ?>
            <li>記事が投稿されていません</li>
          <?php endif; ?>

          <!-- to archive -->
          <div class="single__button">
            <a href="<?php echo esc_url(home_url('/news')); ?>" class="button-archive">一覧へもどる</a>
          </div>
        </article>

        <!-- categories -->
        <aside class="single__aside aside u-desktop">
          <p class="aside__heading">category</p>
          <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
              <?php
              // タクソノミーのリンク
              $current_category_id = get_queried_object_id();
              $categories = get_categories(array(
                'orderby' => 'ID',
                'order'   => 'ASC',
                // 'number'  => 3
              ));
              ?>
              <ul class="aside__category-items">
                <?php if ($categories) : ?>
                  <?php foreach ($categories as $category) : ?>
                    <li class="aside__category-item">
                      <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>"><?php echo esc_html($category->name); ?></a>
                    </li>
                  <?php endforeach; ?>
                <?php else : ?>
                  <li>カテゴリーがありません</li>
                <?php endif; ?>
              </ul>
            <?php endwhile; ?>
          <?php endif; ?>
        </aside>
      </div>

    </div>
  </section>
</main>
<?php get_footer(); ?>