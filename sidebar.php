<?php
/**
 * The sidebar containing the error widget area.
 *
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */
?>

<?php if (!is_active_sidebar('sm-right-sidebar')) { return; } ?>

<div id="secondary" class="widget-area sm-sidebar" role="complementary">
	<?php dynamic_sidebar('sm-right-sidebar'); ?>
</div>