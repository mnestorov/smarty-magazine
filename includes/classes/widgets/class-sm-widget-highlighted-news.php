<?php

/**
 * Highlighted News Grid Widget Class.
 * 
 * Displays highlighted grid news posts from 4 different categories.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class __Smarty_Magazine_Highlighted_News extends WP_Widget {

    /**
     * Constructor: Initialize the widget.
     */
    public function __construct() {
        parent::__construct(
            '__Smarty_Magazine_Highlighted_News',
            __('SM Highlighted News', 'smarty_magazine'),
            array(
                'description' => __('Highlighted grid news posts from 4 different categories', 'smarty_magazine')
            )
        );
    }

    /**
     * Output the widget content on the front-end.
     *
     * @param array $args Display arguments including 'before_title', 'after_title', etc.
     * @param array $instance The widget settings.
     */
    public function widget($args, $instance) {
        global $post;

        // Retrieve widget options
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
                $this->display_highlighted_news($category_id, $rgba);
            }
        }

        echo '<div class="clearfix"></div></div>';
    }

    /**
     * Display highlighted news for a given category.
     *
     * @param int $category_id The category ID.
     * @param string $rgba The background color in RGBA format.
     */
    private function display_highlighted_news($category_id, $rgba) {
        $query = new WP_Query(array(
            'post_type'      => 'post',
            'category__in'   => $category_id,
            'posts_per_page' => 1,
        ));

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $this->render_post($category_id, $rgba);
            }
            wp_reset_postdata();
        } else {
            echo '<p>' . __('Sorry, no posts found in the selected category.', 'smarty_magazine') . '</p>';
        }
    }

    /**
     * Render the post HTML structure.
     *
     * @param int $category_id The category ID.
     * @param string $rgba The background color in RGBA format.
     */
    private function render_post($category_id, $rgba) {
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
                    <a href="<?php echo esc_url(get_category_link($category_id)); ?>" title="<?php echo esc_attr(get_cat_name($category_id)); ?>">
                        <?php echo esc_html(get_cat_name($category_id)); ?>
                    </a>
                </span>

                <h2 class="transition5">
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
     * @param array $instance Current settings.
     */
    public function form($instance) {
        $defaults = array(
            'title'      => '',
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

            <?php for ($i = 1; $i <= 4; $i++) : ?>
                <div class="sm-admin-input-wrap">
                    <label for="<?php echo $this->get_field_id('category' . $i); ?>">
                        <strong><?php printf(__('Category %d', 'smarty_magazine'), $i); ?></strong>
                    </label>
                    <select id="<?php echo $this->get_field_id('category' . $i); ?>" 
                        name="<?php echo $this->get_field_name('category' . $i); ?>">
                        <option value=""><?php _e('Select Category', 'smarty_magazine'); ?></option>
                        <?php 
                        $categories = get_terms(array('taxonomy' => 'category', 'hide_empty' => false));
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
     * @param array $new_instance New settings for this instance as input by the user.
     * @param array $old_instance Old settings for this instance.
     * @return array Updated settings.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);

        for ($i = 1; $i <= 4; $i++) {
            $instance['category' . $i] = intval($new_instance['category' . $i]);
        }

        return $instance;
    }
}
