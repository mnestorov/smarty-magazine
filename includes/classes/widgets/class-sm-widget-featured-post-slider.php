<?php

/**
 * Featured Post Slider Widget Class.
 *
 * Displays a featured news image slider with titles and published dates.
 * 
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class __Smarty_Magazine_Featured_Post_Slider extends WP_Widget {
    /**
     * Constructor: Initializes the widget.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    public function __construct() {
        parent::__construct(
            '__Smarty_Magazine_Featured_Post_Slider',
            __('SM Featured Slider', 'smarty_magazine'),
            array(
                'description' => __('Featured News Image Slider with title and published date.', 'smarty_magazine')
            )
        );

        add_action('wp_ajax_sm_get_categories', [$this, 'get_categories']);
        add_action('wp_ajax_nopriv_sm_get_categories', [$this, 'get_categories']);
    }

    /**
     * Fetch categories dynamically based on post type (AJAX handler)
     * 
     * @since 1.0.0
     * 
     * @return void
     * 
     * @throws Exception If the request is invalid.
     */
    public function get_categories() {
        if (!isset($_POST['post_type'], $_POST['selected_categories']) || !check_ajax_referer('sm_ajax_nonce', 'security', false)) {
            wp_send_json_error(['message' => 'Invalid request']);
            return;
        }

        $post_type = sanitize_text_field($_POST['post_type']);
        $taxonomy = ($post_type === 'news') ? 'news_category' : 'category';
        $selected_categories = array_map('intval', (array) $_POST['selected_categories']);

        $categories = get_terms(array(
            'taxonomy'   => $taxonomy,
            'hide_empty' => false
        ));

        if (is_wp_error($categories)) {
            wp_send_json_error(['message' => 'Error fetching categories']);
            return;
        }

        $category_options = '<option value="">' . __('Select Category', 'smarty_magazine') . '</option>';
        foreach ($categories as $category) {
            $selected = in_array($category->term_id, $selected_categories) ? 'selected="selected"' : '';
            $category_options .= sprintf(
                '<option value="%s" %s>%s</option>',
                esc_attr($category->term_id),
                $selected,
                esc_html($category->name)
            );
        }

        wp_send_json_success(['categories' => $category_options]);
    }

    /**
     * Outputs the widget content on the front-end.
     * 
     * This method is used to display the widget content on the front-end.
     * 
     * @since 1.0.0
     *
     * @param array $args Display arguments including 'before_title', 'after_title', etc.
     * @param array $instance The widget settings.
     * 
     * @return void
     */
    public function widget($args, $instance) {
        $show_posts_from = isset($instance['show_posts_from']) ? $instance['show_posts_from'] : 'recent';
        $category        = isset($instance['category']) ? (array) $instance['category'] : [];
        $sort_order      = isset($instance['sort_order']) ? $instance['sort_order'] : 'DESC';
        $no_of_posts     = isset($instance['no_of_posts']) ? $instance['no_of_posts'] : 5;

        //error_log('Executing Featured Slider Widget');
        //error_log('Post Type: ' . $show_posts_from);
        //error_log('Category: ' . $category);
        //error_log('Number of Posts: ' . $no_of_posts);

        $category = array_filter($category);

        // Query posts based on settings
        $query_args = array(
            'post_type'           => ($show_posts_from === 'news') ? 'news' : 'post',
            'posts_per_page'      => $no_of_posts,
            'ignore_sticky_posts' => true,
            'orderby'             => 'date',
            'order'               => $sort_order,
        );

        if (!empty($category)) {
            if ($show_posts_from === 'news') {
                // Use custom taxonomy for news posts
                $query_args['tax_query'] = array(
                    array(
                        'taxonomy' => 'news_category',
                        'field'    => 'term_id',
                        'terms'    => $category, // Supports multiple categories
                        'operator' => 'IN', // Ensure it fetches posts matching at least one selected category
                    ),
                );
            } else {
                // Use regular WordPress categories
                $query_args['tax_query'] = array(
                    array(
                        'taxonomy' => 'category',
                        'field'    => 'term_id',
                        'terms'    => $category,
                        'operator' => 'IN',
                    ),
                );
            }
        }

        //error_log('Query Args: ' . print_r($query_args, true));

        $featured_posts = new WP_Query($query_args);

        //error_log('SQL Query: ' . $featured_posts->request);
        //error_log('Found Posts: ' . $featured_posts->found_posts);

        if ($featured_posts->have_posts()) : ?>
            <div class="sm-featured-post-slider-wrap">
                <div class="sm-featured-post-slider">
                    <div class="swiper-wrapper">
                        <?php while ($featured_posts->have_posts()) : $featured_posts->the_post(); ?>
                            <?php //error_log('Rendering Post: ' . get_the_title()); // Debugging post titles ?>
                            <?php if (has_post_thumbnail()) : ?>
                                <?php $this->render_post_slide(); ?>
                            <?php else : ?>
                                <div class="swiper-slide">
                                    <h3><?php echo get_the_title(); ?></h3>
                                    <p><?php _e('No Thumbnail.', 'smarty_magazine'); ?></p>
                                </div>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    </div><!-- .swiper-wrapper -->
                    <!-- Navigation arrows -->
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div><!-- .sm-featured-post-slider, .sm-featured-post-slider-wrap -->
        <?php else : ?>
            <p><?php _e('Sorry, no posts found in the selected category.', 'smarty_magazine'); ?></p>
        <?php endif; ?>
        <?php wp_reset_postdata();
    }

    /**
     * Renders the individual post slide.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    private function render_post_slide() { ?>
        <div class="swiper-slide">
            <div class="sm-featured-posts-wrap">
                <figure class="sm-featured-post-img">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php 
                                the_post_thumbnail('sm-featured-post-large', array(
                                    'title' => esc_attr(get_the_title()),
                                    'alt'   => esc_attr(get_the_title()),
                                ));
                            ?>
                        <?php else : ?>
                            <p><?php _e('No Thumbnail.', 'smarty_magazine'); ?></p>
                        <?php endif; ?>
                    </a>
                </figure>
                <h2>
                    <span class="sm-featured-post-date">
                        <span class="sm-featured-post-month">
                            <?php echo esc_html(get_the_date('M')); ?><br>
                            <?php echo esc_html(get_the_date('Y')); ?>
                        </span>
                        <span class="sm-featured-post-day">
                            <?php echo esc_html(get_the_date('d')); ?>
                        </span>
                    </span>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                </h2>
            </div>
        </div>
        <?php
    }

    /**
     * Outputs the widget settings form in the admin dashboard.
     * 
     * This method is used to display the widget settings form in the admin dashboard.
     * 
     * @since 1.0.0
     *
     * @param array $instance Current settings.
     * 
     * @return void
     */
    public function form($instance) {
        $defaults = array(
            'title'           => '',
            'show_posts_from' => 'post',
            'category'        => [],
            'no_of_posts'     => 5,
            'sort_order'      => 'DESC'
        );

        $instance = wp_parse_args((array) $instance, $defaults);
        $selected_post_type = $instance['show_posts_from'];
        $selected_categories = isset($instance['category']) ? (array) $instance['category'] : [];

        // Determine the correct taxonomy
        $taxonomy = ($selected_post_type === 'news') ? 'news_category' : 'category';
        $categories = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => false));
        ?>

        <div class="sm-featured-post-slider">
            <div class="sm-admin-input-wrap">
                <label for="<?php echo $this->get_field_id('title'); ?>">
                    <?php _e('Title', 'smarty_magazine'); ?>
                </label>
                <input type="text" 
                       id="<?php echo $this->get_field_id('title'); ?>" 
                       name="<?php echo $this->get_field_name('title'); ?>" 
                       value="<?php echo esc_attr($instance['title']); ?>" 
                       placeholder="<?php _e('Title for Featured Posts', 'smarty_magazine'); ?>">
            </div>

            <div class="sm-admin-input-wrap">
                <label><?php _e('Choose Type', 'smarty_magazine'); ?></label><br>
                <label>
                    <input type="radio" 
                        class="sm-show-posts-type"
                        name="<?php echo $this->get_field_name('show_posts_from'); ?>" 
                        value="post"
                        data-field-name="<?php echo $this->get_field_name('show_posts_from'); ?>"
                        <?php checked($selected_post_type, 'post'); ?>>
                    <?php _e('Posts', 'smarty_magazine'); ?>
                </label>
                <label>
                    <input type="radio" 
                        class="sm-show-posts-type"
                        name="<?php echo $this->get_field_name('show_posts_from'); ?>" 
                        value="news"
                        data-field-name="<?php echo $this->get_field_name('show_posts_from'); ?>"
                        <?php checked($selected_post_type, 'news'); ?>>
                    <?php _e('News', 'smarty_magazine'); ?>
                </label>
            </div>

            <div class="sm-admin-input-wrap sm-category-dropdown">
                <label for="<?php echo $this->get_field_id('category'); ?>">
                    <?php _e('Select Categories (Hold CTRL to Select Multiple)', 'smarty_magazine'); ?>
                </label>
                <select id="<?php echo $this->get_field_id('category'); ?>" 
                        name="<?php echo $this->get_field_name('category'); ?>[]" 
                        class="sm-category-select" multiple="multiple">
                    <?php
                    foreach ($categories as $category) {
                        printf(
                            '<option value="%s" %s>%s</option>',
                            esc_attr($category->term_id),
                            in_array($category->term_id, $selected_categories) ? 'selected="selected"' : '',
                            esc_html($category->name)
                        );
                    }
                    ?>
                </select>
            </div>

            <div class="sm-admin-input-wrap">
                <label for="<?php echo $this->get_field_id('sort_order'); ?>">
                    <?php _e('Sort Order', 'smarty_magazine'); ?>
                </label>
                <select id="<?php echo $this->get_field_id('sort_order'); ?>" 
                        name="<?php echo $this->get_field_name('sort_order'); ?>">
                    <option value="DESC" <?php selected($instance['sort_order'], 'DESC'); ?>><?php _e('Descending (Newest First)', 'smarty_magazine'); ?></option>
                    <option value="ASC" <?php selected($instance['sort_order'], 'ASC'); ?>><?php _e('Ascending (Oldest First)', 'smarty_magazine'); ?></option>
                </select>
            </div>

            <div class="sm-admin-input-wrap">
                <label for="<?php echo $this->get_field_id('no_of_posts'); ?>">
                    <?php _e('Number of Posts', 'smarty_magazine'); ?>
                </label>
                <input type="number" 
                       id="<?php echo $this->get_field_id('no_of_posts'); ?>" 
                       name="<?php echo $this->get_field_name('no_of_posts'); ?>" 
                       value="<?php echo esc_attr($instance['no_of_posts']); ?>" 
                       min="1" step="1">
            </div>
        </div>
        <?php
    }

    /**
     * Sanitizes and saves widget settings.
     * 
     * This method is used to sanitize and save the widget settings.
     * 
     * @since 1.0.0
     *
     * @param array $new_instance New settings for this instance as input by the user.
     * @param array $old_instance Old settings for this instance.
     * 
     * @return array Updated settings.
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;

        $instance['title']           = sanitize_text_field($new_instance['title']);
        $instance['show_posts_from'] = in_array($new_instance['show_posts_from'], array('recent', 'category', 'news')) 
            ? $new_instance['show_posts_from'] 
            : 'recent';
            $instance['category'] = (!empty($new_instance['category']) && is_array($new_instance['category']))
            ? array_map('intval', $new_instance['category'])
            : [];
        $instance['sort_order']      = in_array($new_instance['sort_order'], array('ASC', 'DESC')) ? $new_instance['sort_order'] : 'DESC';
        $instance['no_of_posts']     = absint($new_instance['no_of_posts']);

        return $instance;
    }
}