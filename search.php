<?php
/**
 * Search Results Template
 * 
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */

get_header(); ?>

<?php if (have_posts()) : ?>
	<div class="container my-4">
		<header class="entry-header mb-5">
			<h1 class="page-title">
				<?php printf(__('Search Results for: %s', 'smarty_magazine'), '<span class="text-warning">' . get_search_query() . '</span>'); ?>
			</h1>
		</header>
		<!-- Start the Bootstrap grid with two columns -->
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <?php while (have_posts()) : the_post(); ?>
                <div class="col">
					<div class="sm-archive">
						<article id="post-<?php the_ID(); ?>" <?php post_class("card h-100 border-0 shadow-lg"); ?> itemscope itemtype="https://schema.org/BlogPosting">
							<?php if (has_post_thumbnail()) : ?>
								<a href="<?php the_permalink(); ?>" class="card-img-top">
									<?php the_post_thumbnail('sm-featured-post-large', ['class' => 'img-fluid rounded-top', 'itemprop' => 'image']); ?>
								</a>
								<?php else : ?>
									<div class="card-img-top">
										<?php __smarty_magazine_post_img('1'); ?>
									</div>
								<?php endif; ?>

							<div class="card-body px-0">
								<h3 class="card-title" itemprop="headline">
									<a href="<?php the_permalink(); ?>" class="text-decoration-none"><?php the_title(); ?></a>
								</h3>
								<div class="card-text" itemprop="headline">
									<?php __smarty_magazine_posted_on(); ?>
									<?php //echo str_ireplace(get_search_query(), '<mark>' . get_search_query() . '</mark>', get_the_excerpt()); ?>
								</div>
							</div>

							<meta itemprop="mainEntityOfPage" content="<?php echo esc_url(get_permalink()); ?>">
							<?php if (has_post_thumbnail()) : ?>
								<meta itemprop="image" content="<?php echo esc_url(wp_get_attachment_url(get_post_thumbnail_id())); ?>">
							<?php endif; ?>

							<div class="card-footer bg-white text-center">
								<a href="<?php the_permalink(); ?>" class="btn btn-primary mt-3">
									<?php _e('Read More', 'smarty_magazine'); ?>
								</a>
							</div>
						</article>
					</div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <?php wp_reset_postdata(); ?>
                                    
        <div class="clearfix"></div>
        <div class="sm-pagination-nav">
            <?php echo paginate_links(); ?>
        </div>
	</div>
<?php else : ?>
	<div class="container d-flex flex-column justify-content-center align-items-center vh-75 text-center py-5">
		<div class="row">
			<div class="col-12">
				<h1 class="display-1 fw-bold text-warning"><?php esc_html_e("Oops... :)", "smarty_magazine"); ?></h1>
				<h2 class="fw-bold text-dark"><?php esc_html_e("Oops! No search results found", "smarty_magazine"); ?></h2>
				<p class="lead text-muted">
					<?php printf(__('Sorry, but nothing matched your search term(s): %s', 'smarty_magazine'), '<span class="fw-bold text-warning">' . get_search_query() . '</span>'); ?>
				</p>
				<a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-lg btn-warning mt-3">
					<i class="bi bi-arrow-return-left me-2"></i><?php esc_html_e("Back to Home", "smarty_magazine"); ?>
				</a>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php get_footer(); ?>