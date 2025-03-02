<?php

/**
 * Highlighted News Widget
 *
 * Displays highlighted news posts from 4 different categories.
 * 
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class __Smarty_Magazine_Highlighted_News extends WP_Widget {
    /**
     * Constructor: Initialize the widget.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    public function __construct() {
        parent::__construct(
            '__Smarty_Magazine_Highlighted_News',
            __('SM Highlighted News', 'smarty_magazine'),
            array(
                'description' => __('Highlighted grid news posts from 4 different categories.', 'smarty_magazine')
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
     * Output the widget content on the front-end.
     * 
     * @since 1.0.0
     * 
     * @param array $args     Widget arguments.
     * @param array $instance Widget instance settings.
     * 
     * @return void
     */
    public function widget($args, $instance) {
        global $post;

        // Retrieve widget options
        $post_type = !empty($instance['post_type']) ? $instance['post_type'] : 'post';
        $categories = array(
            isset($instance['category1']) ? $instance['category1'] : '',
            isset($instance['category2']) ? $instance['category2'] : '',
            isset($instance['category3']) ? $instance['category3'] : '',
            isset($instance['category4']) ? $instance['category4'] : '',
        );

        $title_bg_color = get_theme_mod('__smarty_magazine_primary_color', '#cc2936');
        $rgba = __smarty_magazine_hex2rgba($title_bg_color, 0.75);

        echo '<div class="sm-highlighted-news">';

        foreach ($categories as $index => $category_id) {
            if (!empty($category_id)) {
                $this->display_highlighted_news($category_id, $rgba, $post_type);
            }
        }

        echo '<div class="clearfix"></div></div>';
    }

    /**
     * Display highlighted news for a given category.
     * 
     * @since 1.0.0
     * 
     * @param int    $category_id Category ID.
     * @param string $rgba        RGBA color value.
     * @param string $post_type   Post type (post or news).
     * 
     * @return void
     */
    private function display_highlighted_news($category_id, $rgba, $post_type) {
        $taxonomy = ($post_type === 'news') ? 'news_category' : 'category';

        $query = new WP_Query(array(
            'post_type'      => $post_type,
            'tax_query'      => array(
                array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $category_id,
                ),
            ),
            'posts_per_page' => 1,
        ));

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $this->render_post($category_id, $rgba, $taxonomy);
            }
            wp_reset_postdata();
        } else {
            echo '<p>' . __('Sorry, no posts found in the selected category.', 'smarty_magazine') . '</p>';
        }
    }

    /**
     * Render the post HTML structure.
     * 
     * @since 1.0.0
     * 
     * @param int    $category_id Category ID.
     * @param string $rgba        RGBA color value.
     * @param string $taxonomy    Taxonomy name.
     * 
     * @return void
     */
    private function render_post($category_id, $rgba, $taxonomy) {
        global $post;
        ?>
        <div class="sm-highlighted-news-holder">
            <figure class="sm-highlighted-news-img">
                <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php echo esc_url(get_permalink()); ?>" title="<?php the_title_attribute(); ?>">
                        <?php echo get_the_post_thumbnail($post->ID, 'sm-featured-post-medium', array(
                            'title' => esc_attr(get_the_title($post->ID)),
                            'alt'   => esc_attr(get_the_title($post->ID)),
                        )); ?>
                    </a>
                <?php endif; ?>
            </figure>

            <div class="sm-highlighted-news-desc">
                <span class="sm-highlighted-news-cat" style="background: <?php echo esc_attr($rgba); ?>">
                    <a href="<?php echo esc_url(get_term_link($category_id, $taxonomy)); ?>" title="<?php echo esc_attr(get_term($category_id)->name); ?>">
                        <?php echo esc_html(get_term($category_id)->name); ?>
                    </a>
                </span>

                <h2>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                        <?php the_title(); ?>
                    </a>
                </h2>
            </div>
        </div>
        <?php
    }

    /**
     * Render the widget form in the admin dashboard.
     * 
     * @since 1.0.0
     * 
     * @param array $instance Widget instance settings.
     * 
     * @return void
     */
    public function form($instance) {
        $defaults = array(
            'title'      => '',
            'post_type'  => 'post',
            'category1'  => '',
            'category2'  => '',
            'category3'  => '',
            'category4'  => '',
        );

        $instance = wp_parse_args((array) $instance, $defaults);
        ?>
        <div class="sm-highlighted-news-grid">
            <div class="sm-admin-input-wrap">
                <label for="<?php echo $this->get_field_id('title'); ?>"><strong><?php _e('Title', 'smarty_magazine'); ?></strong></label>
                <input type="text" id="<?php echo $this->get_field_id('title'); ?>" 
                    name="<?php echo $this->get_field_name('title'); ?>" 
                    value="<?php echo esc_attr($instance['title']); ?>" 
                    placeholder="<?php _e('Title for Highlighted News', 'smarty_magazine'); ?>">
            </div>

            <div class="sm-admin-input-wrap">
                <label><strong><?php _e('Choose Type', 'smarty_magazine'); ?></strong></label><br>
                <label>
                    <input type="radio" class="sm-show-posts-type" 
                        name="<?php echo $this->get_field_name('post_type'); ?>" 
                        value="post" <?php checked($instance['post_type'], 'post'); ?>>
                    <?php _e('Posts', 'smarty_magazine'); ?>
                </label>
                <label>
                    <input type="radio" class="sm-show-posts-type" 
                        name="<?php echo $this->get_field_name('post_type'); ?>" 
                        value="news" <?php checked($instance['post_type'], 'news'); ?>>
                    <?php _e('News', 'smarty_magazine'); ?>
                </label>
            </div>

            <?php for ($i = 1; $i <= 4; $i++) : ?>
                <div class="sm-admin-input-wrap sm-category-dropdown">
                    <label for="<?php echo $this->get_field_id('category' . $i); ?>">
                        <strong><?php printf(__('Category %d', 'smarty_magazine'), $i); ?></strong>
                    </label>
                    <select id="<?php echo $this->get_field_id('category' . $i); ?>" 
                        name="<?php echo $this->get_field_name('category' . $i); ?>" 
                        class="sm-category-dropdown">
                        <option value=""><?php _e('Select Category', 'smarty_magazine'); ?></option>
                        <?php 
                        $taxonomy = ($instance['post_type'] === 'news') ? 'news_category' : 'category';
                        $categories = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => false));
                        foreach ($categories as $category) {
                            printf(
                                '<option value="%s" %s>%s</option>',
                                esc_attr($category->term_id),
                                selected($instance['category' . $i], $category->term_id, false),
                                esc_html($category->name)
                            );
                        }
                        ?>
                    </select>
                </div>
            <?php endfor; ?>
        </div>
        <?php
    }

    /**
     * Sanitize and save widget settings.
     * 
     * @since 1.0.0
     * 
     * @param array $new_instance New widget settings.
     * @param array $old_instance Old widget settings.
     * 
     * @return array Updated widget settings.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['post_type'] = in_array($new_instance['post_type'], ['post', 'news']) ? $new_instance['post_type'] : 'post';

        for ($i = 1; $i <= 4; $i++) {
            $instance['category' . $i] = intval($new_instance['category' . $i]);
        }

        return $instance;
    }
}
