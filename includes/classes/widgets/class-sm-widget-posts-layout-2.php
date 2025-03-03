<?php

/**
 * News List Layout 2 Widget.
 *
 * This widget displays posts in layout style 2, providing options for randomization,
 * ordering, and category filtering for recently published posts.
 * 
 * @since 1.0.0
 * 
 * @package Smarty_Magazine
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class __Smarty_Magazine_Post_Layout_2 extends __Smarty_Magazine_Tabs_Content {
    /**
     * @var int Layout identifier.
     */
    public $layout = 2;

    /**
     * Constructor: Initializes the widget with a unique ID, name, and description.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    public function __construct() {
        parent::__construct(
            '__Smarty_Magazine_Post_Layout_2',
            __('SM News Layout 2', 'smarty_magazine'),
            array(
                'description' => __('Posts display layout 2 for recently published post or news.', 'smarty_magazine')
            )
        );
    }

    /**
     * Defines configuration fields for the widget settings.
     *
     * This method returns an array of configurable options displayed in the widget admin panel.
     * It allows setting the title, category, number of posts, randomization, and ordering options.
     * 
     * @since 1.0.0
     *
     * @return array Configuration fields for widget customization.
     */
    public function get_configs() {
        $fields = array(
            array(
                'type'  => 'text',
                'name'  => 'title',
                'label' => esc_html__('Title', 'smarty_magazine'),
            ),

            array(
                'type'  => 'radio',
                'name'  => 'post_type',
                'label' => esc_html__('Choose Type', 'smarty_magazine'),
                'options' => array(
                    'post' => esc_html__('Posts', 'smarty_magazine'),
                    'news' => esc_html__('News', 'smarty_magazine'),
                ),
                'default' => 'post'
            ),

            array(
                'type'  => 'list_cat',
                'name'  => 'category',
                'label' => esc_html__('Categories', 'smarty_magazine'),
            ),

            array(
                'type'  => 'checkbox',
                'name'  => 'random_posts',
                'label' => esc_html__('Random Posts', 'smarty_magazine'),
            ),

            array(
                'type'  => 'text',
                'name'  => 'no_of_posts',
                'label' => esc_html__('Number of Posts', 'smarty_magazine'),
            ),

            array(
                'type'  => 'orderby',
                'name'  => 'orderby',
                'label' => esc_html__('Order by', 'smarty_magazine'),
            ),

            array(
                'type'  => 'order',
                'name'  => 'order',
                'label' => esc_html__('Order', 'smarty_magazine'),
            ),

            array(
                'type'  => 'checkbox',
                'name'  => 'top_spacing',
                'label' => esc_html__('Add Top Spacing', 'smarty_magazine'),
                'default' => 'no'
            ),

            array(
                'type'  => 'checkbox',
                'name'  => 'bottom_spacing',
                'label' => esc_html__('Add Bottom Spacing', 'smarty_magazine'),
                'default' => 'no'
            ),
        );

        return $fields;
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
        $fields = $this->get_configs();
        $defaults = array(
            'post_type'      => 'post',
            'random_posts'   => 'no',
            'top_spacing'    => 'no',
            'bottom_spacing' => 'no'
        );
        $instance = wp_parse_args((array) $instance, $defaults);

        foreach ($fields as $field) {
            $value = !empty($instance[$field['name']]) ? $instance[$field['name']] : '';
            $this->generate_input_field($field, $value, $instance);
        }
    }

    /**
     * Helper method to generate input fields for the widget form.
     * 
     * This method generates input fields for the widget form.
     * 
     * @since 1.0.0
     *
     * @param array $field The field configuration.
     * @param string $value The current value of the field.
     * 
     * @return void
     */
    private function generate_input_field($field, $value, $instance) {
        ?>
        <div class="sm-magazine-admin-input-wrap">
            <label for="<?php echo esc_attr($this->get_field_id($field['name'])); ?>"><?php echo esc_html($field['label']); ?></label>
            <?php if ($field['type'] === 'text') : ?>
                <input 
                    type="text" 
                    id="<?php echo esc_attr($this->get_field_id($field['name'])); ?>" 
                    name="<?php echo esc_attr($this->get_field_name($field['name'])); ?>" 
                    value="<?php echo esc_attr($value); ?>">
            <?php elseif ($field['type'] === 'radio' && $field['name'] === 'post_type') : ?>
                <div class="sm-post-type-options">
                    <?php foreach ($field['options'] as $option_value => $option_label) : ?>
                        <label>
                            <input 
                                type="radio" 
                                class="sm-show-posts-type"
                                name="<?php echo esc_attr($this->get_field_name($field['name'])); ?>" 
                                value="<?php echo esc_attr($option_value); ?>"
                                <?php checked($value, $option_value); ?>>
                            <?php echo esc_html($option_label); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php elseif ($field['type'] === 'number') : ?>
                <input 
                    type="number" 
                    id="<?php echo esc_attr($this->get_field_id($field['name'])); ?>" 
                    name="<?php echo esc_attr($this->get_field_name($field['name'])); ?>" 
                    value="<?php echo esc_attr($value); ?>" 
                    min="1" step="1">
            <?php elseif ($field['type'] === 'list_cat') : ?>
                <div class="sm-category-dropdown">
                    <select 
                        id="<?php echo esc_attr($this->get_field_id($field['name'])); ?>" 
                        name="<?php echo esc_attr($this->get_field_name($field['name'])); ?>">
                        <option value=""><?php _e('Select a Category', 'smarty_magazine'); ?></option>
                        <?php
                        $taxonomy = !empty($instance['post_type']) && $instance['post_type'] === 'news' ? 'news_category' : 'category';
                        $categories = get_terms(array(
                            'taxonomy' => $taxonomy,
                            'hide_empty' => false
                        ));
                        foreach ($categories as $term) {
                            printf(
                                '<option value="%s" %s>%s</option>',
                                esc_attr($term->term_id),
                                selected($value, $term->term_id, false),
                                esc_html($term->name)
                            );
                        }
                        ?>
                    </select>
                </div>
            <?php elseif ($field['type'] === 'orderby') : ?>
                <select 
                    id="<?php echo esc_attr($this->get_field_id($field['name'])); ?>" 
                    name="<?php echo esc_attr($this->get_field_name($field['name'])); ?>">
                    <option value="date" <?php selected($value, 'date'); ?>><?php _e('Date', 'smarty_magazine'); ?></option>
                    <option value="title" <?php selected($value, 'title'); ?>><?php _e('Title', 'smarty_magazine'); ?></option>
                    <option value="rand" <?php selected($value, 'rand'); ?>><?php _e('Random', 'smarty_magazine'); ?></option>
                </select>
            <?php elseif ($field['type'] === 'order') : ?>
                <select 
                    id="<?php echo esc_attr($this->get_field_id($field['name'])); ?>" 
                    name="<?php echo esc_attr($this->get_field_name($field['name'])); ?>">
                    <option value="ASC" <?php selected($value, 'ASC'); ?>><?php _e('Ascending', 'smarty_magazine'); ?></option>
                    <option value="DESC" <?php selected($value, 'DESC'); ?>><?php _e('Descending', 'smarty_magazine'); ?></option>
                </select>
            <?php elseif ($field['type'] === 'checkbox') : ?>
                <input 
                    type="checkbox" 
                    id="<?php echo esc_attr($this->get_field_id($field['name'])); ?>" 
                    name="<?php echo esc_attr($this->get_field_name($field['name'])); ?>" 
                    value="yes" <?php checked($value, 'yes'); ?>>
                <small><?php _e('Check to add top and/or bottom margin', 'smarty_magazine'); ?></small>
            <?php endif; ?>
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

        $fields = $this->get_configs();

        foreach ($fields as $field) {
            if ($field['type'] === 'checkbox') {
                $instance[$field['name']] = isset($new_instance[$field['name']]) && $new_instance[$field['name']] === 'yes' ? 'yes' : 'no';
            } else {
                $instance[$field['name']] = sanitize_text_field($new_instance[$field['name']]);
            }
        }

        return $instance;
    }
}
