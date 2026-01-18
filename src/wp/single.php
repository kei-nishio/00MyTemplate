<?php get_header(); ?>
<main class="main">
	<div class="l-lower-heading">
		<?php get_template_part('/parts/p-lower-heading', "", ["subtitle" => "news", "title" => "ニュース記事"]); ?>
	</div>

	<?php // get_template_part('/parts/c-breadcrumb'); 
	?>

	<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<?php
			$tax_slug = "news-type"; // default : category
			$post_id = get_the_ID();
			$thumbnail_id = get_post_thumbnail_id($post_id);
			$thumbnail_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
			$thumbnail_alt = !empty($thumbnail_alt) ? $thumbnail_alt : 'アイキャッチ';
			$terms = get_the_terms($post->ID, $tax_slug);
			$author_id = get_the_author_meta('ID');
			$author_name = esc_html(get_the_author_meta('display_name', $author_id));
			$first_name = esc_html(get_the_author_meta('first_name', $author_id));
			$last_name = esc_html(get_the_author_meta('last_name', $author_id));
			$description = nl2br(esc_html(get_the_author_meta('description')));
			$prev_url = !empty($prev = get_previous_post()) ? esc_url(get_permalink($prev->ID)) : '';
			$next_url = !empty($next = get_next_post()) ? esc_url(get_permalink($next->ID)) : '';
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
			<article class="l-lower-top p-single">
				<div class="p-single__inner l-inner">
					<?php // eye catch 
					?>
					<p class="p-single__eye-catch">
						<?php if (has_post_thumbnail()) : ?>
							<img src="<?php the_post_thumbnail_url("full"); ?>" alt="<?php echo esc_attr($thumbnail_alt); ?>">
						<?php else : ?>
							<img src="<?php echo esc_url($theme_uri . "/assets/images/common/noimage.webp"); ?>" alt="no image" />
						<?php endif; ?>
					</p>
					<?php // title 
					?>
					<h2 class="p-single__title"><?php the_title() ?></h2>
					<?php // date 
					?>
					<time class="p-single__date" datetime="<?php the_time("c"); ?>">
						<?php the_time("Y.m.d"); ?>
					</time>
					<?php // content 
					?>
					<div class="p-single__content">
						<div class="p-content"><?php the_content(); ?></div>
					</div>

					<?php // ページネーション一体型 
					?>
					<ul class="p-single__pagination">
						<li class="prev <?php echo empty($prev) ? 'hidden' : ''; ?>">
							<div class="c-button-arrow c-button-arrow--reverse"><a href="<?php echo $prev_url; ?>">前の記事</a></div>
						</li>
						<li class="index">
							<div class="c-button-text"><a href="<?php echo esc_url(home_url('/news')); ?>">一覧に戻る</a></div>
						</li>
						<li class="next <?php echo empty($next) ? 'hidden' : ''; ?>">
							<div class="c-button-arrow"><a href="<?php echo $next_url; ?>">次の記事</a></div>
						</li>
					</ul>

				</div>

				<?php // ページネーション分離型 
				?>
				<ul class="p-single__pagination">
					<?php if (!empty($prev)) : ?>
						<li class="prev"><a href="<?php echo $prev_url; ?>">前の記事</a></li>
					<?php endif; ?>
					<?php if (!empty($next)) : ?>
						<li class="next"><a href="<?php echo $next_url; ?>">次の記事</a></li>
					<?php endif; ?>
				</ul>


				<div class="p-single__button">
					<div class="c-button-normal"><a href="<?php echo esc_url(home_url('/news')); ?>">一覧に戻る</a></div>
				</div>
			</article>
		<?php endwhile; ?>
	<?php else : ?>
		<p style="text-align: center; margin-top: 20px; ">記事が投稿されていません。</p>
	<?php endif; ?>
</main>
<?php get_footer(); ?>