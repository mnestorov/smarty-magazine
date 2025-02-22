<?php
/**
 * Template helper functions.
 * 
 * @since 1.0.0
 * 
 * @package Smarty_Magazine
 */

if (!function_exists('__smarty_magazine_render_news_carousel')) {
    /**
     * Render news carousel.
     * 
     * @since 1.0.0
     * 
     * @param [type] $posts
     * @param [type] $status
     * @param integer $items_per_slide
     * 
     * @return void
     */
    function __smarty_magazine_render_news_carousel($posts, $status, $items_per_slide = '') {
        $carousel_id = "news-$status-carousel";
        ?>
        <section class="news-section news-<?php echo esc_attr($status); ?> mb-5">
            <div class="d-flex justify-content-between align-items-center mb-2 px-2">
                <h2 class="section-title <?php echo esc_attr("text-$status"); ?>">
                    <?php echo esc_html(sprintf(__('%s', 'smarty_magazine'), ucfirst($status))); ?>
                </h2>
                <?php if (count($posts) > 1) : ?>
                    <div class="carousel-controls">
                        <div class="d-inline-block">
                            <button class="carousel-control-prev" type="button" data-bs-target="#<?php echo esc_attr($carousel_id); ?>" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden"><?php _e('Previous', 'smarty_magazine'); ?></span>
                            </button>
                        </div>
                        <div class="d-inline-block">
                            <button class="carousel-control-next" type="button" data-bs-target="#<?php echo esc_attr($carousel_id); ?>" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden"><?php _e('Next', 'smarty_magazine'); ?></span>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div id="<?php echo esc_attr($carousel_id); ?>" class="carousel slide" data-bs-ride="true">
                <div class="carousel-inner">
                    <?php
                    $items = array_chunk($posts, $items_per_slide);
                    foreach ($items as $index => $group) :
                        $active_class = $index === 0 ? ' active' : ''; ?>
                        <div class="carousel-item<?php echo $active_class; ?>" data-bs-interval="10000">
                            <div class="row <?php $items_per_slide == 1 ? 'row-cols-1' : 'row-cols-md-2 row-cols-lg-3'; ?> g-3">
                                <?php foreach ($group as $post) : setup_postdata($post); ?>
                                    <div class="col">
                                        <?php __smarty_magazine_render_news_post($post, $status); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
    }
}

if (!function_exists('__smarty_magazine_render_news_post')) {
    /**
     * Render news post.
     * 
     * @since 1.0.0
     *
     * @param [type] $post
     * @param string $status
     * 
     * @return void
     */
    function __smarty_magazine_render_news_post($post, $status = null) {
        $post_id = is_object($post) ? $post->ID : $post; // Handle both WP_Post object and ID
        $button_class = $status === 'breaking' ? 'danger' : ($status === 'featured' ? 'warning' : 'primary');
        ?>
        <article id="post-<?php echo esc_attr($post_id); ?>" <?php post_class("card h-100 p-0 news-$status"); ?> itemscope itemtype="https://schema.org/NewsArticle">
            <figure class="sm-news-post-img">
                <?php if (has_post_thumbnail($post_id)) : ?>
                    <a href="<?php echo esc_url(get_permalink($post_id)); ?>" class="card-img-top">
                        <?php echo get_the_post_thumbnail($post_id, 'sm-featured-post-medium', array('class' => 'img-fluid', 'itemprop' => 'image')); ?>
                    </a>
                <?php else : ?>
                    <div class="card-img-top">
                        <?php __smarty_magazine_post_img('1'); ?>
                    </div>
                <?php endif; ?>
            </figure>

            <div class="text-center text-muted small">
                <?php __smarty_magazine_posted_on($post_id); ?>
            </div>

            <div class="card-body px-2">
                <h3 class="card-title fs-4" itemprop="headline">
                    <a href="<?php echo esc_url(get_permalink($post_id)); ?>" class="text-decoration-none">
                        <?php echo esc_html(get_the_title($post_id)); ?>
                    </a>
                </h3>
                <?php if ($status === null) : ?>
                    <div class="card-text mb-4" itemprop="description">
                        <?php
                        $excerpt = get_the_excerpt($post_id);
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
                <?php endif; ?>
            </div>

            <div class="card-footer bg-transparent border-0 p-0">
                <div class="d-grid gap-2">
                    <a href="<?php echo esc_url(get_permalink($post_id)); ?>" class="btn btn-<?php echo esc_attr($button_class); ?> rounded-0">
                        <?php _e('Read More', 'smarty_magazine'); ?>
                    </a>
                </div>
                <span class="search-icon position-absolute top-0 end-0 m-2">
                    <a href="<?php echo esc_url(get_permalink($post_id)); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark" class="btn btn-sm btn-dark">
                        <i class="bi bi-search"></i>
                    </a>
                </span>
            </div>

            <meta itemprop="mainEntityOfPage" content="<?php echo esc_url(get_permalink($post_id)); ?>">
            <?php if (has_post_thumbnail($post_id)) : ?>
                <meta itemprop="image" content="<?php echo esc_url(wp_get_attachment_url(get_post_thumbnail_id($post_id))); ?>">
            <?php endif; ?>
        </article>
        <?php
    }
}