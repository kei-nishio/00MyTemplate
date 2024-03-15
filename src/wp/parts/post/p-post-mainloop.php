<!-- p-post-mainloop.php -->

<?php
/**
 * Main loop for displaying posts.
 * 
 * Parameters:
 * - 'class': CSS class for the list container.
 * - 'list': CSS class prefix for list items.
 * - 'card': Slug of the card template to use for each post.
 */
?>
<ul class="<?php echo esc_attr($args['class'] . " " . $args['list'] . (!empty($args['list_mdf']) ? " " . $args['list_mdf'] : '')); ?>">
	<?php
	if (have_posts()) :
		while (have_posts()) : the_post();
	?>
			<li class="<?php echo esc_attr($args['list']); ?>__item">
				<?php get_template_part('parts/card/p-' . $args['card'], null, $args); ?>
			</li>
		<?php
		endwhile;
	else :
		?>
		<li class="<?php echo esc_attr($args['list']) ?>__have-no-post">
			該当の記事はありません。
		</li>
	<?php endif; ?>
</ul>