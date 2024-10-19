<?php get_header(); ?>
<main class="main">
  <?php get_template_part('/parts/p-lower-heading', "", ["subtitle" => "news", "title" => "ニュース"]); ?>

  <?php // get_template_part('/parts/c-breadcrumb'); 
  ?>

  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
      <?php
      $post_id = $args["post_id"];
      $thumbnail_id = get_post_thumbnail_id($post_id);
      $thumbnail_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
      $thumbnail_alt = !empty($thumbnail_alt) ? $thumbnail_alt : 'アイキャッチ';
      $terms = get_the_terms($post->ID, $tax_slug);
      $author_id = get_the_author_meta('ID');
      $author_name = esc_html(get_the_author_meta('display_name', $author_id));
      $first_name = esc_html(get_the_author_meta('first_name', $author_id));
      $last_name = esc_html(get_the_author_meta('last_name', $author_id));
      ?>
      <?php
      // $custom_field = get_field('');
      // $image = get_field('acf_customer_image');
      // $image_url = esc_url($image['url']);
      // $image_alt = esc_attr($image['alt']);
      // $images = get_field('acf_images');
      // if ($images) :
      //   $image = $images[0];
      //   if ($image):
      //     $image_url = esc_url($image['url']);
      //     $image_alt = esc_attr($image['alt']);
      //   endif;
      // endif;
      ?>
      <article class="l-lower-top p-sub-single">
        <div class="p-sub-single__inner l-inner">
          <?php // eye catch 
          ?>
          <p class="p-sub-single__eye-catch">
            <?php if (has_post_thumbnail()) : ?>
              <img src="<?php the_post_thumbnail_url("full"); ?>" alt="<?php echo esc_attr($thumbnail_alt); ?>">
            <?php else : ?>
              <img src="<?php echo esc_url(get_theme_file_uri("/assets/images/common/noimage.jpg")); ?>" alt="no image" />
            <?php endif; ?>
          </p>
          <?php // title 
          ?>
          <h2 class="p-sub-single__title"><?php the_title() ?></h2>
          <?php // date 
          ?>
          <time class="p-sub-single__date" datetime="<?php the_time("c"); ?>">
            <?php the_time("Y.m.d"); ?>
          </time>
          <?php // content 
          ?>
          <div class="p-sub-single__content">
            <div class="p-content-single"><?php the_content(); ?></div>
          </div>
        </div>
        <div class="p-sub-single__button">
          <div class="c-button-normal"><a href="<?php echo esc_url(home_url('/column')); ?>">コラム一覧に戻る</a></div>
        </div>
      </article>
    <?php endwhile; ?>
  <?php else : ?>
    <p style="text-align: center;">記事が投稿されていません。</p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>