<?php
/**
 * The sidebar containing the news widget area.
 *
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */
?>

<?php if (!is_active_sidebar('sm-news-sidebar')) { return; } ?>

<div id="secondary" class="widget-area sm-sidebar" role="complementary">
	<?php dynamic_sidebar('sm-news-sidebar'); ?>
</div>