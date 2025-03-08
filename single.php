<?php
/**
 * The template for displaying all single posts.
 *
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */
?>

<?php get_header(); ?>

<div class="sm-default-single-page">
	<div class="container">
		<div class="row">
			<div class="col-lg-9 col-md-9">
				<main id="main" class="site-main" role="main">
					<?php while (have_posts()) : the_post(); ?>
						<?php get_template_part('template-parts/content-single', 'page'); ?>
						<?php the_post_navigation(); ?>
						<?php if (comments_open() || get_comments_number()) : ?>
							<?php comments_template(); ?>
						<?php endif; ?>
					<?php endwhile; ?>
				</main>
			</div>
			<div class="col-lg-3 col-md-3">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>