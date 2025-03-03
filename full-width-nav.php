<?php
/**
 * Template Name: Full Width Nav
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

<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">
					<?php if (have_posts()) : ?>
						<?php if (is_home() && ! is_front_page()) : ?>
							<header>
								<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
							</header>
						<?php endif; ?>
						<?php while (have_posts()) : the_post(); ?>
							<!-- Main Content (Title and Description) -->
                            <?php get_template_part('template-parts/content', get_post_format()); ?>

                            <!-- Nav Pills and Tabbed Content -->
                            <?php get_template_part('template-parts/content', 'nav'); ?>
						<?php endwhile; ?>
						<?php the_posts_navigation(); ?>
					<?php else: ?>
						<?php get_template_part('template-parts/content', 'none'); ?>
					<?php endif; ?>
				</main>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
