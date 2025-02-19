<?php
/**
 * Template part for displaying results in search pages.
 * 
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>
		<?php if ('post' === get_post_type()) : ?>
			<div class="entry-meta">
				<?php __smarty_magazine_posted_on(); ?>
			</div>
		<?php endif; ?>
	</header>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div>
	<footer class="entry-footer">
		<?php __smarty_magazine_entry_footer(); ?>
	</footer>
</article>