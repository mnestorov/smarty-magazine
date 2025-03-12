<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title('<h1 class="entry-title">', '</h1>'); ?>
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
	<footer class="entry-footer text-center fw-bold my-4">
        <?php __smarty_magazine_entry_footer(); ?>
    </footer>
</article>