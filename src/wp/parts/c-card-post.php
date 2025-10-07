<?php global $theme_uri; ?>
<?php
$tax_slug = "category"; // default : category
$post_id = $args["post_id"];
$thumbnail_id = get_post_thumbnail_id($post_id);
$thumbnail_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
$thumbnail_alt = !empty($thumbnail_alt) ? $thumbnail_alt : 'アイキャッチ';
$terms = get_the_terms($post_id, $tax_slug);
$author_id = get_the_author_meta('ID');
$author_name = esc_html(get_the_author_meta('display_name', $author_id));
$first_name = esc_html(get_the_author_meta('first_name', $author_id));
$last_name = esc_html(get_the_author_meta('last_name', $author_id));
$description = nl2br(esc_html(get_the_author_meta('description')));
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
// $acfItems = [
//   'cfItem1',
//   'cfItem2',
// ];
// foreach ($jobItems as $item):
//   $field = get_field_object($item);
//   if ($field['value']):
//     echo $field['label'];
//     echo $field['value'];
//   endif;
// endforeach;
?>
<a href="<?php the_permalink(); ?>" class="c-card-post">
  <?php if (false): // eye catch 
  ?>
    <figure class="c-card-post__img">
      <?php if (has_post_thumbnail()) : ?>
        <img src="<?php the_post_thumbnail_url("full"); ?>" alt="<?php echo esc_attr($thumbnail_alt); ?>">
      <?php else : ?>
        <img src="<?php echo esc_url($theme_uri . "/assets/images/common/noimage.webp"); ?>" alt="no image" />
      <?php endif; ?>
    </figure>
  <?php endif; ?>
  <?php if (true): // title 
  ?>
    <h3 class="c-card-post__title"><?php echo esc_html(mb_strimwidth(get_the_title(), 0, 18 * 2 + 3, '...')); ?></h3>
  <?php endif; ?>
  <?php if (true): // date 
  ?>
    <time class="c-card-post__date" datetime="<?php the_time("c"); ?>">
      <?php the_time("Y.m.d"); ?>
    </time>
  <?php endif; ?>
  <?php if (true): // taxonomy 
  ?>
    <?php if (!empty($terms) && !is_wp_error($terms)) : ?>
      <ul class="c-card-post__term-list">
        <?php if (true): // 複数のタームを出力する 
        ?>
          <?php if (true): // 全てのタームを出力する 
          ?>
            <?php foreach ($terms as $term) : ?>
              <li class="c-card-post__term-item">
                <p class="c-card-post__term"><?php echo  esc_html($term->name); ?></p>
              </li>
            <?php endforeach; ?>
          <?php else:  // n個のタームを出力する 
          ?>
            <?php for ($i = 0; $i < 1; $i++): ?>
              <li class="c-card-post__term-item">
                <p class="c-card-post__term"><?php echo  esc_html($term[$i]->name); ?></p>
              </li>
            <?php endfor; ?>
          <?php endif; ?>
        <?php else: // 一番目のタームを出力する  
        ?>
          <p class="c-card-post__term"><?php echo  esc_html($term[0]->name); ?></p>
        <?php endif; ?>
      </ul>
    <?php endif; ?>
  <?php endif; ?>
  <?php if (true): // excerpt 
  ?>
    <p class="c-card-post__excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
  <?php endif; ?>
  <?php if (true): // content 
  ?>
    <p class="c-card-post__content"><?php echo esc_html(mb_strimwidth(strip_tags(get_the_content()), 0, 100 * 2 + 3, '...')); ?></p>
  <?php endif; ?>
</a>