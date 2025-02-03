<?php
/**
 * The home template file.
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

<div class="container">
	<div class="row">
		<div class="col-lg-9 col-md-9">
			<div id="primary" class="sm-archive-wrap">
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
					<?php wp_reset_postdata(); ?>

					<div class="clearfix"></div>

					<div class="sm-pagination-nav">
						<?php echo the_posts_pagination(); ?>
					</div>
				<?php else : ?>
					<p><?php _e('Sorry, no posts matched your criteria.', 'smarty_magazine'); ?></p>
				<?php endif; ?>
			</div>
		</div>
		<div class="col-lg-3 col-md-3">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>