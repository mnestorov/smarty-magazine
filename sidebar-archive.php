<?php
/**
 * The sidebar containing the archive (top) widget area.
 *
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */
?>

<?php if (!is_active_sidebar('sm-archive-sidebar')) { return; } ?>

<div id="secondary" class="widget-area sm-sidebar" role="complementary">
	<?php dynamic_sidebar('sm-archive-sidebar'); ?>
</div>
