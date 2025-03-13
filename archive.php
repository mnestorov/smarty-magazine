<?php
/**
 * The template for displaying archive pages with first post highlighted and subsequent posts in two columns.
 *
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */

get_header(); ?>

<div class="container my-4">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="sm-archive">
                <div id="primary" class="content-area">
                    <main id="main" class="site-main" role="main">
                        <?php if (have_posts()) : ?>
                            <header class="entry-header mb-5">
                                <?php the_archive_title('<h1 class="page-title fw-bold text-uppercase">', '</h1>'); ?>
                                <?php if (get_the_archive_description()) : ?>
                                    <div class="sm-taxonomy-description alert mt-3 p-3 rounded">
                                        <?php the_archive_description(); ?>
                                    </div>
                                <?php endif; ?>
                            </header>
                            
                            <?php $count = 1; ?>

                            <div class="row">
                                <div class="col-lg-9 col-md-9">
                                    <?php while (have_posts()) : the_post(); ?>
                                        <?php if ($count == 1) : ?>
                                            <!-- First Post (Highlighted, Full Width) -->
                                            <div class="mb-5">
                                                <article id="post-<?php the_ID(); ?>" <?php post_class("card h-100 p-0 sm-news-post"); ?> itemscope itemtype="https://schema.org/BlogPosting">
                                                    <?php if (has_post_thumbnail()) : ?>
                                                        <a href="<?php the_permalink(); ?>" class="card-img-top">
                                                            <?php the_post_thumbnail('sm-featured-post-large', array('class' => 'card-img-top img-fluid rounded-0', 'itemprop' => 'image')); ?>
                                                        </a>
                                                    <?php else : ?>
                                                        <div class="card-img-top">
                                                            <?php __smarty_magazine_post_img('1'); ?>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php __smarty_magazine_posted_on(); ?>

                                                    <div class="card-body p-2">
                                                        <h2 itemprop="headline">
                                                            <a href="<?php the_permalink(); ?>" class="text-decoration-none"><?php the_title(); ?></a>
                                                        </h2>
                                                        <div class="card-text mb-4" itemprop="description">
                                                            <?php the_excerpt(); ?>
                                                        </div>
                                                    </div>

                                                    <div class="card-footer bg-transparent border-0 p-0">
                                                        <div class="d-grid gap-2">
                                                            <a href="<?php the_permalink(); ?>" class="btn btn-primary rounded-0">
                                                                <?php _e('Read More', 'smarty_magazine'); ?>
                                                            </a>
                                                        </div>
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
                                                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                                            <?php endif; ?>
                                            <div class="col">
                                                <article id="post-<?php the_ID(); ?>" <?php post_class("card h-100 p-0 sm-news-post"); ?> itemscope itemtype="https://schema.org/BlogPosting">
                                                    <?php if (has_post_thumbnail()) : ?>
                                                        <a href="<?php the_permalink(); ?>" class="card-img-top">
                                                            <?php the_post_thumbnail('sm-featured-post-medium', array('class' => 'card-img-top img-fluid rounded-0', 'itemprop' => 'image')); ?>
                                                        </a>
                                                    <?php else : ?>
                                                        <div class="card-img-top">
                                                            <?php __smarty_magazine_post_img('1'); ?>
                                                        </div>
                                                    <?php endif; ?>

                                                    <?php __smarty_magazine_posted_on(); ?>

                                                    <div class="card-body p-2">
                                                        <h3 class="card-title" itemprop="headline">
                                                            <a href="<?php the_permalink(); ?>" class="text-decoration-none"><?php the_title(); ?></a>
                                                        </h3>
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
                            </div>
                            <div class="col-lg-3 col-md-3">
                                <?php get_sidebar(); ?>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>