<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */
?> 

<?php get_header(); ?>

<div class="container my-4">
	<div class="row">
		<div class="col-lg-9 col-md-9">
			<main id="main" class="site-main" role="main">
				<?php while (have_posts()) : the_post(); ?>
					<?php get_template_part('template-parts/content', 'page'); ?>
					<?php if (comments_open() || get_comments_number()) { comments_template(); } ?>
				<?php endwhile; ?>
			</main>
		</div>
		<div class="col-lg-3 col-md-3">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>