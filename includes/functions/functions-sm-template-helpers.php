<?php
/**
 * Template helper functions.
 * 
 * @since 1.0.0
 * 
 * @package Smarty_Magazine
 */

if (!function_exists('__smarty_magazine_translate_news_status')) {
    /**
     * Translate news status.
     * 
     * @since 1.0.0
     * 
     * @param string $status
     * 
     * @return string
     */
    function __smarty_magazine_translate_news_status($status) {
        // Define translatable statuses
        $translatable_statuses = [
            'breaking'  => __('Breaking', 'smarty_magazine'),
            'featured'  => __('Featured', 'smarty_magazine'),
            'sponsored' => __('Sponsored', 'smarty_magazine'),
        ];

        // Return the translated status if available, otherwise return ucfirst version
        return $translatable_statuses[$status] ?? ucfirst($status);
    }
}

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
        <section class="news-section news-<?php echo esc_attr($status); ?> mb-5 shadow-lg">
            <div class="d-flex justify-content-between align-items-center px-2">
                <h2 class="section-title <?php echo esc_attr("text-$status"); ?>"><?php echo esc_html(__smarty_magazine_translate_news_status($status)); ?></h2>
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
        $button_class = $status === 'breaking' ? 'danger text-uppercase' : ($status === 'featured' ? 'warning text-uppercase' : 'primary-default text-uppercase');
        $border_class = $status === 'breaking' ? 'danger' : ($status === 'featured' ? 'warning' : 'primary');
        ?>
        <article id="post-<?php echo esc_attr($post_id); ?>" <?php post_class($status ? "card h-100 news-$status" : 'card h-100 border-0 shadow-lg'); ?> itemscope itemtype="https://schema.org/NewsArticle">
            <figure class="sm-news-post-img">
                <?php if (has_post_thumbnail($post_id)) : ?>
                    <a href="<?php echo esc_url(get_permalink($post_id)); ?>" class="card-img-top">
                        <?php echo get_the_post_thumbnail($post_id, 'sm-featured-post-large', array('class' => 'img-fluid rounded-top', 'itemprop' => 'image')); ?>
                    </a>
                <?php else : ?>
                    <div class="card-img-top">
                        <?php __smarty_magazine_post_img('1'); ?>
                    </div>
                <?php endif; ?>
            </figure>

            <div class="card-body px-0">
                <?php if ($status) : ?>
                    <h2 class="card-title" itemprop="headline">
                        <a href="<?php echo esc_url(get_permalink($post_id)); ?>" class="text-decoration-none">
                            <?php echo esc_html(get_the_title($post_id)); ?>
                        </a>
                    </h2>
                    <div class="card-text" itemprop="description">
                        <?php __smarty_magazine_posted_on($post_id); ?>
                        <?php //the_excerpt($post_id); ?>
                    </div>
                <?php else : ?>
                    <h3 class="card-title" itemprop="headline">
                        <a href="<?php echo esc_url(get_permalink($post_id)); ?>" class="text-decoration-none">
                            <?php echo esc_html(get_the_title($post_id)); ?>
                        </a>
                    </h3>
                    <div class="card-text" itemprop="description">
                        <?php __smarty_magazine_posted_on($post_id); ?>
                        <?php //the_excerpt($post_id); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="card-footer bg-transparent <?php echo $status ? 'border-' . esc_attr($border_class) : ''; ?> text-center">
                <a href="<?php echo esc_url(get_permalink($post_id)); ?>" class="btn btn-<?php echo $status ? esc_attr($button_class) : 'primary'; ?> mt-3">
                    <?php _e('Read More', 'smarty_magazine'); ?>
                </a>
            </div>

            <meta itemprop="mainEntityOfPage" content="<?php echo esc_url(get_permalink($post_id)); ?>">
            <?php if (has_post_thumbnail($post_id)) : ?>
                <meta itemprop="image" content="<?php echo esc_url(wp_get_attachment_url(get_post_thumbnail_id($post_id))); ?>">
            <?php endif; ?>
        </article>
        <?php
    }
}

if (!function_exists('__smarty_magazine_clean_archive_titles')) {
    /**
     * Clean archive titles.
     * 
     * @since 1.0.0
     * 
     * @param string $title
     * 
     * @return string
     */
    function __smarty_magazine_clean_archive_titles($title) {
        // Remove "Archive:" for custom post type "news"
        if (is_post_type_archive('news')) {
            return __('News', 'smarty_magazine'); // Change to your desired title
        }

        // Remove "Tag:" or other labels for custom taxonomies related to News
        if (is_tax()) {
            return single_term_title('', false);
        }

        // Remove "Category:" for category archives
        if (is_category()) {
            return single_cat_title('', false); // Returns only the category name
        }

        // Remove "Tag:" for normal post types
        if (is_tag()) {
            return single_tag_title('', false); // Returns only the tag name
        }

        // Remove default "Archive:" prefix from all archive pages
        $title = preg_replace('/^\s*'.__('Archive:', 'default').'\s*/', '', $title);
        
        return $title;
    }
    add_filter('get_the_archive_title', '__smarty_magazine_clean_archive_titles');
}
