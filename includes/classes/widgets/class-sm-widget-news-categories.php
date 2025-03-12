<?php
/**
 * News Categories Widget.
 *
 * @since 1.0.0
 *
 * @package SmartyMagazine
 */

class __Smarty_magazine_News_Categories extends WP_Widget {

    /**
     * Constructor: Initialize the widget.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    public function __construct() {
        parent::__construct(
            '__Smarty_magazine_News_Categories',
            __('SM News Categories', 'smarty_magazine'),
            array(
                'description' => __('A widget to display news categories', 'smarty_magazine')
            )
        );
    }

    /**
     * Outputs the content of the widget.
     * 
     * This method is used to display the widget content on the front-end.
     * 
     * @since 1.0.0
     *
     * @param array $args
     * @param array $instance
     * 
     * @return void
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        // Get selected categories
        $selected_categories = !empty($instance['categories']) ? $instance['categories'] : array();
        $show_post_count = !empty($instance['show_post_count']) ? $instance['show_post_count'] : false;

        // Display news categories
        $categories = get_terms(array(
            'taxonomy' => 'news_category',
            'hide_empty' => false,
            'include' => $selected_categories,
        ));

        if (!empty($categories) && !is_wp_error($categories)) {
            echo '<ul class="list-group p-0">';
            foreach ($categories as $category) {
                echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                echo '<a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a>';
                if ($show_post_count) {
                    echo '<span class="badge bg-featured rounded-pill">' . esc_html($category->count) . '</span>';
                }
                echo '</li>';
            }
            echo '</ul>';
        }

        echo $args['after_widget'];
    }

    /**
     * Outputs the options form on admin.
     * 
     * This method is used to display the widget form on the admin.
     * 
     * @since 1.0.0
     *
     * @param array $instance The widget options
     * 
     * @return void
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('News Categories', 'smarty_magazine');
        $selected_categories = !empty($instance['categories']) ? $instance['categories'] : array();
        $show_post_count = !empty($instance['show_post_count']) ? (bool) $instance['show_post_count'] : false;
        $categories = get_terms(array(
            'taxonomy' => 'news_category',
            'hide_empty' => false,
        ));
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'smarty_magazine'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('categories')); ?>"><?php esc_attr_e('Select Categories:', 'smarty_magazine'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('categories')); ?>" name="<?php echo esc_attr($this->get_field_name('categories')); ?>[]" multiple>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?php echo esc_attr($category->term_id); ?>" <?php echo in_array($category->term_id, $selected_categories) ? 'selected' : ''; ?>>
                        <?php echo esc_html($category->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_post_count); ?> id="<?php echo esc_attr($this->get_field_id('show_post_count')); ?>" name="<?php echo esc_attr($this->get_field_name('show_post_count')); ?>" />
            <label for="<?php echo esc_attr($this->get_field_id('show_post_count')); ?>"><?php esc_attr_e('Show post counts', 'smarty_magazine'); ?></label>
        </p>
        <?php
    }

    /**
     * Processing widget options on save.
     * 
     * This method is used to update the widget options.
     * 
     * @since 1.0.0
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     *
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['categories'] = (!empty($new_instance['categories'])) ? array_map('sanitize_text_field', $new_instance['categories']) : array();
        $instance['show_post_count'] = isset($new_instance['show_post_count']) ? (bool) $new_instance['show_post_count'] : false;

        return $instance;
    }
}