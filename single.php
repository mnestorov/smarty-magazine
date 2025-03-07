<?php
/**
 * The template for displaying all single posts, including news posts.
 *
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */
?>

<?php get_header(); ?>

<div class="container my-4">
    <div class="row">
        <div class="col-lg-9 col-md-9">
            <main id="main" class="site-main" role="main">
                <?php while (have_posts()) : the_post(); ?>
                    <?php
                    // Dynamically load template part based on post type
                    if ('news' === get_post_type()) {
                        get_template_part('template-parts/content', 'news');
                    } else {
                        get_template_part('template-parts/content', 'single');
                    }
                    ?>
                    <?php
                    // Post navigation for 'news' post type
                    if ('news' === get_post_type()) {
                        __smarty_magazine_news_post_navigation();
                    } else {
                        the_post_navigation(array(
                            'screen_reader_text' => __('Post navigation', 'smarty_magazine'),
                        ));
                    }
                    ?>
                    <?php if (comments_open() || get_comments_number()) : ?>
                        <?php comments_template(); ?>
                    <?php endif; ?>
                <?php endwhile; ?>
            </main>
        </div>
        <div class="col-lg-3 col-md-3 mt-5">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>