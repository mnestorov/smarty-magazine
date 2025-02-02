<?php
/**
 * The template for displaying archive pages.
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
			<div class="sm-category-wrap">
				<div id="primary" class="content-area">
					<main id="main" class="site-main" role="main">
						<?php if (have_posts()) : ?>
							<header class="page-header">
								<?php the_archive_title('<h1 class="page-title">', '</h1>'); ?>
								<?php the_archive_description('<div class="taxonomy-description">', '</div>'); ?>
							</header>
							<div class="sm-category-posts">
								<?php $count = 1; ?>
								<?php while (have_posts()) : the_post(); ?>
									<?php 
									if ($count == 1) {
										$img_size = 'sm-featured-post-medium';
									} else {
										$img_size = 'sm-featured-post-small';
									} ?>
									<?php if ($count == 1) {
										echo '<div class="sm-news-post-highlighted">';
									} elseif ($count == 2) {
										echo '<div class="sm-news-post-list">';
									} 
									?>
									<div class="sm-news-post">
										<figure class="sm-news-post-img">
											<?php 
											if (has_post_thumbnail()) { 
												$image = '';
												$title_attribute = get_the_title($post->ID);
												$image .= '<a href="' . esc_url(get_permalink()) . '" title="' . the_title('', '', false) . '">';

												if ($count == 1) {
													$image .= get_the_post_thumbnail($post->ID, 'sm-featured-post-large', array('title' => esc_attr($title_attribute), 'alt' => esc_attr($title_attribute))) . '</a>';
												} else {
													$image .= get_the_post_thumbnail($post->ID, 'sm-featured-post-medium', array('title' => esc_attr($title_attribute), 'alt' => esc_attr($title_attribute))) . '</a>';
												}
												echo $image;
											} else {
												__smarty_magazine_post_img('1');
											}
											?>
											<a href="<?php echo esc_url(get_permalink()); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><span class="transition35"> <i class="bi bi-search transition35"></i></span></a>
										</figure>
										<div class="sm-news-post-content">
											<div class="sm-news-post-meta">
												<span class="sm-news-post-date"><i class="bi bi-calendar"></i> <?php the_time(get_option('date_format')); ?></span>
												<span class="sm-news-post-comments"><i class="bi bi-chat"></i> <?php comments_number('No Responses', 'one Response', '% Responses'); ?></span>
											</div>
											<h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="text-decoration-none"><?php the_title(); ?></a></h3>
											<?php if ($count == 1) : ?>
												<div class="sm-news-post-desc">
													<?php
														$excerpt = get_the_excerpt();
														$limit   = "350";
														$pad     = "...";

														if (strlen($excerpt) <= $limit) {
															echo esc_html($excerpt);
														} else {
															$excerpt = substr($excerpt, 0, $limit) . $pad;
															echo esc_html($excerpt);
														}
													?>
												</div>
											<?php else: ?>
												<div class="sm-news-post-desc">
													<?php
													$excerpt = get_the_excerpt();
													$limit   = "260";
													$pad     = "...";

													if (strlen($excerpt) <= $limit) {
														echo esc_html($excerpt);
													} else {
														$excerpt = substr($excerpt, 0, $limit) . $pad;
														echo esc_html($excerpt);
													}
													?>
												</div>
											<?php endif; ?>
										</div>
										<div class="sm-category-post-readmore">
											<a class="transition35" href="<?php echo esc_url(get_permalink()); ?>" title="<?php the_title_attribute(); ?>" class="text-decoration-none"><?php _e('Read more', 'smarty_magazine'); ?></></a>
										</div>
									</div>
									<?php if ($count == 1) { echo '</div>'; } ?>
								<?php $count++; endwhile; ?>
								<?php wp_reset_postdata(); ?>
							</div>
							<div class="clearfix"></div>
							<div class="sm-pagination-nav">
								<?php echo paginate_links(); ?>
							</div>
						<?php else: ?>
							<p><?php _e('Sorry, no posts matched your criteria.', 'smarty_magazine'); ?></p>
						<?php endif; ?>
					</main>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-3">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>