<?php
/**
 * Template Name: Front Page
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */
?>

<?php get_header(); ?>

<?php if ('page' == get_option('show_on_front')) : ?> 
	<?php if (is_active_sidebar('sm-news-ticker')) : ?>
		<div class="container bt-news-ticker-wrap">
			<div class="row">
				<div class="col-lg-12 col-md-12">
					<?php dynamic_sidebar('sm-news-ticker'); ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<div class="container">
		<div class="row">
			<?php if (is_active_sidebar('sm-featured-news-slider')) : ?>
				<div class="col-lg-6 col-md-6">
					<?php dynamic_sidebar('sm-featured-news-slider'); ?>
				</div>
			<?php endif; ?>
			<?php if (is_active_sidebar('sm-highlighted-news')) : ?>
				<div class="col-lg-6 col-md-6">
					<?php dynamic_sidebar('sm-highlighted-news'); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-lg-9 col-md-9">
				<div class="sm-news-main">
					<?php if (is_active_sidebar('sm-front-top-section-news')) : ?>
						<?php dynamic_sidebar('sm-front-top-section-news'); ?>
					<?php else: ?>
						<div id="primary" class="content-area">
							<main id="main" class="site-main" role="main">
								<?php if (have_posts()) : ?>
									<?php if (is_home() && ! is_front_page()) : ?>
										<header>
											<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
										</header>
									<?php endif; ?>
									<?php while (have_posts()) : the_post(); ?>
										<?php
										/**
										 * Include the Post-Format-specific template for the content.
										 * If you want to override this in a child theme, then include a file
										 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
										 */
										get_template_part('template-parts/content', get_post_format());
										?>
									<?php endwhile; ?>
									<?php the_posts_navigation(); ?>
								<?php else : ?>
									<?php get_template_part('template-parts/content', 'none'); ?>
								<?php endif; ?>
							</main>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="col-lg-3 col-md-3 mt-lg-5 mt-0">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>
<?php else : ?>
	<div class="container">
		<div class="row">
			<div class="col-lg-9 col-md-9">
				<?php if (have_posts()) : ?> 
					<?php while (have_posts()) : the_post(); ?>
						<div <?php post_class('sm-archive-post'); ?>>
							<?php if (has_post_thumbnail()) : ?>
								<figure>
									<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('sm-featured-post-medium'); ?></a>
								</figure>
							<?php endif; ?>
							<article>
								<header class="entry-header">
									<?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>
								</header>
								<div class="sm-archive-post-content">
									<?php the_excerpt(); ?>
								</div>
							</article>

							<div class="clearfix"></div>
							
						</div>
					<?php endwhile; ?>
				<?php endif; ?>

				<?php wp_reset_postdata(); ?>

				<div class="clearfix"></div>

				<div class="sm-pagination-nav">
					<?php echo the_posts_pagination(); ?>
				</div>
			</div>
			<div class="col-lg-3 col-md-3">
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php get_footer(); ?>