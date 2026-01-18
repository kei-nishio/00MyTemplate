<?php
$title = $args["title"];
$sub_title = $args["subtitle"];
$modifier = esc_html($args["modifier"]);
$add_class = $modifier ? ' c-section-title--' . $modifier : '';
?>
<hgroup class="c-section-title<?php echo $add_class ?>">
	<h2 class="c-section-title__main"><?php echo $title ?></h2>
	<p class="c-section-title__sub"><?php echo $sub_title ?></p>
</hgroup>