<?php
/**
 * The template for displaying author archives.
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
                                <h1 class="page-title"><?php _e('Author Archive', 'smarty_magazine'); ?></h1>
                            </header>

                            <div class="row">
                                <div class="col-lg-8 col-md-8 order-2 order-md-1">
                                    <div class="row row-cols-1 row-cols-md-2 g-3">
                                        <?php while (have_posts()) : the_post(); ?>
                                            <div class="col">
                                                <article id="post-<?php the_ID(); ?>" <?php post_class("card h-100 border-0 shadow-lg"); ?> itemscope itemtype="https://schema.org/BlogPosting">
                                                    <?php if (has_post_thumbnail()) : ?>
                                                        <a href="<?php the_permalink(); ?>" class="card-img-top">
                                                            <?php the_post_thumbnail('sm-featured-post-large', array('class' => 'img-fluid rounded-top', 'itemprop' => 'image')); ?>
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
                                                        <div class="card-text" itemprop="description">
                                                            <?php __smarty_magazine_posted_on(); ?>
                                                            <?php //the_excerpt(); ?>
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
                                        <?php endwhile; ?>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="sm-pagination-nav">
                                        <?php echo paginate_links(); ?>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 order-1 order-md-2">
                                    <?php
                                    // Display the Author Box
                                    if (function_exists('__smarty_magazine_author_box')) {
                                        __smarty_magazine_author_box();
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php else : ?>
                            <p><?php _e('Sorry, no posts matched your criteria.', 'smarty_magazine'); ?></p>
                        <?php endif; ?>
                    </main>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
