<div class="p-sidebar">
  <ul class="p-sidebar__list">
    <li class="p-sidebar__item">
      <p class="p-sidebar__heading">○○から探す</p>
      <?php
      $terms = get_terms(array(
        'taxonomy' => 'news-type',
        'hide_empty' => false, // 投稿がないタームも表示したい場合は false に
        'order' => 'ASC',
        // 'parent' => 0, // 親タームのみ取得
        // 'orderby' => 'description', // ID, name, slug, count, term_group, term_order, description
        // 'number' => 10, // 取得するタームの数
      ));
      ?>
      <?php if (!empty($terms) && !is_wp_error($terms)) : ?>
        <?php
        // タームの説明をもとにあいうえお順でソート
        usort($terms, function ($a, $b) {
          return strcmp($a->description, $b->description);
        });
        ?>
        <?php if (wp_is_mobile()): ?>
          <label for="news-type" style="display: none;">○○から探す</label>
          <select id="news-type" class="p-sidebar__sub-list" onchange="redirectToUrl(this)">
            <option value="" selected class="">ここから選択する</option>
            <?php foreach ($terms as $term) : ?>
              <option value="<?php echo esc_url(get_term_link($term)); ?>" class="p-sidebar__sub-item"><?php echo esc_html($term->name); ?></option>
            <?php endforeach; ?>
          </select>
        <?php else: ?>
          <ul class="p-sidebar__sub-list">
            <?php foreach ($terms as $term) : ?>
              <li class="p-sidebar__sub-item">
                <a href="<?php echo esc_url(get_term_link($term)); ?>" id="news-type-<?php echo esc_html($term->description) ?>">
                  <?php echo esc_html($term->name); ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      <?php endif; ?>
    </li>
  </ul>
</div>