<?php
/**
 * The template for displaying search results pages.
 *
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */
?>

<?php get_header(); ?>

<div class="container my-4">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">
					<header class="entry-header mb-4">
						<h1 class="page-title">
							<?php printf(esc_html__('Search Results for: %s', 'smarty_magazine'), '<span>' . get_search_query() . '</span>'); ?>
						</h1>
					</header>
					<div class="row">
						<!-- Main Content Area -->
						<div class="col-lg-9 col-md-9">
							<?php if (have_posts()) : ?>
								<div class="search-results-list">
									<?php while (have_posts()) : the_post(); ?>
										<article id="post-<?php the_ID(); ?>" <?php post_class('mb-4 pb-4 border-bottom'); ?>>
											<h2 class="search-entry-title">
												<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
											</h2>

											<?php __smarty_magazine_posted_on(); ?>

											<div class="entry-summary">
												<?php the_excerpt(); ?>
											</div>
										</article>
									<?php endwhile; ?>
								</div>

								<!-- Pagination -->
								<div class="sm-pagination-nav">
									<?php echo paginate_links(); ?>
								</div>

							<?php else : ?>
								<p class="no-results"><?php esc_html_e('No results found. Try a different search.', 'smarty_magazine'); ?></p>
								<?php get_search_form(); ?>
							<?php endif; ?>
						</div>

						<!-- Sidebar -->
						<div class="col-lg-3 col-md-3">
							<?php get_sidebar(); ?>
						</div>
					</div>
				</main>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>