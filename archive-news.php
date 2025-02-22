<?php
/**
 * The template for displaying the news archive with carousel for priority sections and normal posts.
 * 
 * @since 1.0.0
 * 
 * @package Smarty_Magazine
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */

get_header(); ?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="sm-news-archive">
                <div id="primary" class="content-area">
                    <main id="main" class="site-main" role="main">
                        <?php if (have_posts()) : ?>
                            <header class="page-header mb-4">
                                <?php the_archive_title('<h1 class="page-title">', '</h1>'); ?>
                                <?php the_archive_description('<div class="taxonomy-description lead">', '</div>'); ?>
                                <?php if (is_tax('news_category')) : ?>
                                    <div class="taxonomy-description lead">
                                        <?php echo term_description(); ?>
                                    </div>
                                <?php endif; ?>
                            </header>

                            <?php
                            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                            $is_first_page = $paged === 1;
                            $statuses_priority = ['breaking', 'featured', 'sponsored']; // Priority statuses
                            $main_statuses = ['breaking', 'featured']; // Only breaking and featured for main content
                            $posts_by_status = [];
                            $items_per_slide = wp_is_mobile() ? 1 : 2; // TODO: Change the number of items per slide dynamically
                            ?>

                            <!-- Priority Sections with Carousel (Breaking and Featured only) -->
                            <?php if ($is_first_page) : ?>
                                <div class="row">
                                    <div class="col-lg-9 col-md-9">
                                        <?php
                                        $priority_args = array(
                                            'post_type' => 'news',
                                            'posts_per_page' => -1,
                                            'meta_query' => array(
                                                array(
                                                    'key' => '_news_status',
                                                    'value' => $statuses_priority,
                                                    'compare' => 'IN',
                                                ),
                                            ),
                                        );
                                        $priority_query = new WP_Query($priority_args);

                                        if ($priority_query->have_posts()) :
                                            while ($priority_query->have_posts()) : $priority_query->the_post();
                                                $status = __smarty_magazine_get_news_status(get_the_ID());
                                                $posts_by_status[$status][] = get_post(); // Clone the post object
                                            endwhile;
                                            wp_reset_postdata();
                                        endif;

                                        // Render Breaking and Featured in main content
                                        foreach ($main_statuses as $status) :
                                            if (!empty($posts_by_status[$status])) :
                                                __smarty_magazine_render_news_carousel($posts_by_status[$status], $status, $items_per_slide);
                                            endif;
                                        endforeach;
                                        ?>
                                    </div>
                                    <div class="col-lg-3 col-md-3 mb-5">
                                        <?php 
                                        // Sponsored Section in Sidebar
                                        if (!empty($posts_by_status['sponsored'])) { ?>
                                            <div class="mb-5">
                                                <?php __smarty_magazine_render_news_carousel($posts_by_status['sponsored'], 'sponsored', 1); ?>
                                            </div><?php 
                                        }

                                        get_sidebar('news');
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Normal Posts Section -->
                            <?php
                            $posts_per_page = get_theme_mod('__smarty_magazine_posts_per_page', 6); // Get posts per page from customizer
                            $normal_args = array(
                                'post_type'      => 'news',
                                'posts_per_page' => $posts_per_page,
                                'paged'          => $paged,
                                'meta_query'     => array(
                                    'relation'   => 'OR',
                                    array('key'  => '_news_status', 'compare' => 'NOT EXISTS'),
                                    array('key'  => '_news_status', 'value' => '', 'compare' => '='),
                                    array('key'  => '_news_status', 'value' => $statuses_priority, 'compare' => 'NOT IN'),
                                ),
                            );
                            $normal_query = new WP_Query($normal_args);
                            ?>

                            <?php if ($normal_query->have_posts()) : ?>
                                <section class="news-section news-normal">
                                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 g-3">
                                        <?php while ($normal_query->have_posts()) : $normal_query->the_post(); ?>
                                            <div class="col">
                                                <?php __smarty_magazine_render_news_post(get_the_ID(), null); ?>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                </section>
                            <?php endif; ?>
                            <?php wp_reset_postdata(); ?>
							<div class="clearfix"></div>
                            <div class="sm-pagination-nav">
								<?php echo paginate_links(); ?>
							</div>
                        <?php else : ?>
                            <div class="alert alert-info text-center">
                                <?php _e('Sorry, no news items matched your criteria.', 'smarty_magazine'); ?>
                            </div>
                        <?php endif; ?>
                    </main>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>