<?php
/**
 * Template part for displaying posts.
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
	<div class="entry-content">
		<?php
		the_content(sprintf(
			wp_kses(__('Continue reading %s <span class="meta-nav">&rarr;</span>', 'smarty_magazine'), array('span' => array('class' => array()))),
			the_title('<span class="screen-reader-text">"', '"</span>', false)
		));
		?>
		<?php
		wp_link_pages(array(
			'before' => '<div class="page-links">' . esc_html__('Pages:', 'smarty_magazine'),
			'after'  => '</div>',
		));
		?>
	</div>
	<footer class="entry-footer">
		<?php __smarty_magazine_entry_footer(); ?>
	</footer>
</article>