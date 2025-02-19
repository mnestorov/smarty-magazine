<?php
/**
 * Template part for displaying single posts.
 *
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title('<h1 class="entry-title">', '</h1>'); ?>
		<div class="entry-meta">
			<?php __smarty_magazine_posted_on(); ?>
		</div>
	</header>
	<div class="entry-content">
		<?php the_content(); ?>
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
	<?php if (get_theme_mod('__smarty_magazine_related_posts_setting', 0) == 1) : ?>
		<?php get_template_part('template-parts/related-posts'); ?>
	<?php endif; ?>
</article>