<?php

/**
 * Social Icons Widget Class.
 *
 * Displays a set of social media icons linked to the user's profiles.
 *
 * @package SmartyMagazine
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Class __Smarty_Magazine_Social_Icons
 *
 * Custom Widget for displaying social media icons.
 */
class __Smarty_Magazine_Social_Icons extends WP_Widget {

    /**
     * Constructor: Initializes the widget with its name and description.
     */
    public function __construct() {
        parent::__construct(
            '__Smarty_Magazine_Social_Icons',
            __('SM Social Icons', 'smarty_magazine'),
            array(
                'description' => __('Displays social media icons linked to profiles.', 'smarty_magazine')
            )
        );
    }

    /**
     * Outputs the widget content on the front-end.
     *
     * @param array $args Display arguments including 'before_title', 'after_title', etc.
     * @param array $instance The widget settings.
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';

        // List of social platforms and their respective Font Awesome classes.
        $social_platforms = array(
            'facebook'   => 'bi-facebook',
            'twitter'    => 'bi-twitter-x',
            'g-plus'     => 'bi-google',
            'instagram'  => 'bi-instagram',
            'github'     => 'bi-github',
            'flickr'     => 'bi-flickr',
            'pinterest'  => 'bi-pinterest',
            'wordpress'  => 'bi-wordpress',
            'youtube'    => 'bi-youtube',
        );

        echo '<div class="sm-social-icons">';

        // Display the widget title if it exists.
        if (!empty($title)) {
            echo '<h2 class="widget-title">' . esc_html($title) . '</h2>';
        }

        echo '<ul>';

        // Loop through each social platform and display icons if URLs are set.
        foreach ($social_platforms as $platform => $icon_class) {
            if (!empty($instance[$platform])) {
                echo '<li><a href="' . esc_url($instance[$platform]) . '" target="_blank" rel="noopener noreferrer">';
                echo '<i class="bi ' . esc_attr($icon_class) . ' transition35"></i>';
                echo '</a></li>';
            }
        }

        echo '<div class="clearfix"></div>';
        echo '</ul></div>';
    }

    /**
     * Outputs the widget settings form in the admin dashboard.
     *
     * @param array $instance Current widget settings.
     */
    public function form($instance) {
        $defaults = array(
            'title'     => '',
            'facebook'  => '',
            'twitter'   => '',
            'g-plus'    => '',
            'instagram' => '',
            'github'    => '',
            'flickr'    => '',
            'pinterest' => '',
            'wordpress' => '',
            'youtube'   => '',
        );

        $instance = wp_parse_args((array)$instance, $defaults);

        // Display Title input field.
        $this->generate_input_field('title', __('Title', 'smarty_magazine'), $instance['title']);

        // Loop through each social platform to generate input fields.
        foreach ($defaults as $key => $value) {
            if ($key !== 'title') {
                $this->generate_input_field($key, ucfirst($key), $instance[$key]);
            }
        }
    }

    /**
     * Helper method to generate input fields for the widget form.
     *
     * @param string $field_id The field ID.
     * @param string $label The field label.
     * @param string $value The current value of the field.
     */
    private function generate_input_field($field_id, $label, $value) {
        ?>
        <div class="sm-admin-input-wrap">
            <label for="<?php echo esc_attr($this->get_field_id($field_id)); ?>"><?php echo esc_html($label); ?></label>
            <input 
                type="text" 
                id="<?php echo esc_attr($this->get_field_id($field_id)); ?>" 
                name="<?php echo esc_attr($this->get_field_name($field_id)); ?>" 
                value="<?php echo esc_attr($value); ?>" 
                placeholder="<?php echo sprintf(__('https://%s.com/', 'smarty_magazine'), $field_id); ?>">
        </div>
        <?php
    }

    /**
     * Sanitizes and saves widget settings.
     *
     * @param array $new_instance New settings input by the user.
     * @param array $old_instance Previous settings.
     * @return array Updated settings.
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;

        // Sanitize title.
        $instance['title'] = sanitize_text_field($new_instance['title']);

        // List of social media keys.
        $social_keys = array(
            'facebook', 'twitter', 'g-plus', 'instagram', 'github', 'flickr',
            'pinterest', 'wordpress', 'youtube',
        );

        // Sanitize each social media URL.
        foreach ($social_keys as $key) {
            $instance[$key] = !empty($new_instance[$key]) ? esc_url_raw($new_instance[$key]) : '';
        }

        return $instance;
    }
}
