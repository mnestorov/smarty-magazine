<?php
/**
 * The template for displaying archive pages with first post highlighted and subsequent posts in two columns.
 *
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */
?>

<?php get_header(); ?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-9 col-md-9">
            <div class="sm-post-archive">
                <div id="primary" class="content-area">
                    <main id="main" class="site-main" role="main">
                        <?php if (have_posts()) : ?>
                            <header class="page-header mb-4">
                                <?php the_archive_title('<h1 class="page-title">', '</h1>'); ?>
                                <?php the_archive_description('<div class="taxonomy-description lead">', '</div>'); ?>
                            </header>
                            
                            <?php $count = 1; ?>
                            
                            <?php while (have_posts()) : the_post(); ?>
                                <?php if ($count == 1) : ?>
                                    <!-- First Post (Highlighted, Full Width) -->
                                    <div class="mb-5">
                                        <article id="post-<?php the_ID(); ?>" <?php post_class("card h-100 p-0 sm-news-post border-0"); ?> itemscope itemtype="https://schema.org/BlogPosting">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <a href="<?php the_permalink(); ?>" class="card-img-top">
                                                    <?php the_post_thumbnail('sm-featured-post-large', array('class' => 'card-img-top img-fluid rounded-0', 'itemprop' => 'image')); ?>
                                                </a>
                                            <?php else : ?>
                                                <div class="card-img-top">
                                                    <?php __smarty_magazine_post_img('1'); ?>
                                                </div>
                                            <?php endif; ?>

											<div class="text-center text-muted small">
                                                <?php __smarty_magazine_posted_on(); ?>
                                            </div>

                                            <div class="card-body py-0 px-2">
                                                <h3 class="card-title" itemprop="headline">
                                                    <a href="<?php the_permalink(); ?>" class="text-decoration-none"><?php the_title(); ?></a>
                                                </h3>
                                                <div class="card-text mb-4" itemprop="description">
                                                    <?php
                                                    $excerpt = get_the_excerpt();
                                                    $limit = 350;
                                                    $pad = "...";
                                                    if (strlen($excerpt) <= $limit) {
                                                        echo esc_html($excerpt);
                                                    } else {
                                                        $excerpt = substr($excerpt, 0, $limit) . $pad;
                                                        echo esc_html($excerpt);
                                                    }
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="card-footer bg-transparent border-0 p-0">
												<div class="d-grid gap-2">
													<a href="<?php the_permalink(); ?>" class="btn btn-primary rounded-0">
														<?php _e('Read More', 'smarty_magazine'); ?>
													</a>
												</div>
                                                <span class="search-icon position-absolute top-0 end-0 m-2">
                                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark" class="btn btn-sm btn-dark">
                                                        <i class="bi bi-search"></i>
                                                    </a>
                                                </span>
                                            </div>

                                            <meta itemprop="mainEntityOfPage" content="<?php echo esc_url(get_permalink()); ?>">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <meta itemprop="image" content="<?php echo esc_url(wp_get_attachment_url(get_post_thumbnail_id())); ?>">
                                            <?php endif; ?>
                                        </article>
                                    </div>
                                <?php else : ?>
                                    <!-- Subsequent Posts (Two Columns) -->
                                    <?php if ($count == 2) : ?>
                                        <div class="row row-cols-1 row-cols-md-2 g-3">
                                    <?php endif; ?>
                                    <div class="col">
                                        <article id="post-<?php the_ID(); ?>" <?php post_class("card h-100 p-0 sm-news-post border-0"); ?> itemscope itemtype="https://schema.org/BlogPosting">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <a href="<?php the_permalink(); ?>" class="card-img-top">
                                                    <?php the_post_thumbnail('sm-featured-post-medium', array('class' => 'card-img-top img-fluid rounded-0', 'itemprop' => 'image')); ?>
                                                </a>
                                            <?php else : ?>
                                                <div class="card-img-top">
                                                    <?php __smarty_magazine_post_img('1'); ?>
                                                </div>
                                            <?php endif; ?>

											<div class="text-center text-muted small">
                                                <?php __smarty_magazine_posted_on(); ?>
                                            </div>

											<div class="card-body py-0 px-2">
                                                <h3 class="card-title" itemprop="headline">
                                                    <a href="<?php the_permalink(); ?>" class="text-decoration-none"><?php the_title(); ?></a>
                                                </h3>
                                                <div class="card-text mb-4" itemprop="description">
                                                    <?php
                                                    $excerpt = get_the_excerpt();
                                                    $limit = 260;
                                                    $pad = "...";
                                                    if (strlen($excerpt) <= $limit) {
                                                        echo esc_html($excerpt);
                                                    } else {
                                                        $excerpt = substr($excerpt, 0, $limit) . $pad;
                                                        echo esc_html($excerpt);
                                                    }
                                                    ?>
                                                </div>
                                            </div>

                                            <div class="card-footer bg-transparent border-0 p-0">
												<div class="d-grid gap-2">
													<a href="<?php the_permalink(); ?>" class="btn btn-primary rounded-0">
														<?php _e('Read More', 'smarty_magazine'); ?>
													</a>
												</div>
                                                <span class="search-icon position-absolute top-0 end-0 m-2">
                                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark" class="btn btn-sm btn-dark">
                                                        <i class="bi bi-search"></i>
                                                    </a>
                                                </span>
                                            </div>

                                            <meta itemprop="mainEntityOfPage" content="<?php echo esc_url(get_permalink()); ?>">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <meta itemprop="image" content="<?php echo esc_url(wp_get_attachment_url(get_post_thumbnail_id())); ?>">
                                            <?php endif; ?>
                                        </article>
                                    </div>
                                <?php endif; ?>
                                <?php $count++; ?>
                            <?php endwhile; ?>
                            <?php if ($count > 2) : ?>
                                </div><!-- Close sm-news-post-list -->
                            <?php endif; ?>
                            <?php wp_reset_postdata(); ?>
							<div class="clearfix"></div>
                            <div class="sm-pagination-nav">
								<?php echo paginate_links(); ?>
							</div>
                        <?php else : ?>
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