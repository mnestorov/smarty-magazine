<?php
/**
 * Shortcodes Widget Class.
 *
 * Displays a shortcode data in website top bar.
 * 
 * @since 1.0.0
 *
 * @package SmartyMagazine
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class __Smarty_Magazine_Shortcodes extends WP_Widget {
    /**
     * Constructor: Initializes the widget with its name and description.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    public function __construct() {
        parent::__construct(
            '__Smarty_Magazine_Shortcodes',
            __('SM Shortcode', 'smarty_magazine'),
            array(
                'description' => __('Displays a shortcode output in the website top bar.', 'smarty_magazine')
            )
        );
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
        $args = wp_parse_args($args, array(
            'before_widget' => '<div class="widget %s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));

        // Get spacing settings
        $top_spacing = !empty($instance['top_spacing']) && $instance['top_spacing'] === 'yes' ? 'mt-5' : '';
        $bottom_spacing = !empty($instance['bottom_spacing']) && $instance['bottom_spacing'] === 'yes' ? 'mb-5' : '';

        // Extract existing classes from before_widget
        preg_match('/class=["\'](.*?)["\']/i', $args['before_widget'], $matches);
        $existing_classes = isset($matches[1]) ? explode(' ', $matches[1]) : array('widget');
        
        // Add widget ID base and spacing classes
        $widget_classes = array_merge($existing_classes, array($this->id_base));
        if ($top_spacing) {
            $widget_classes[] = $top_spacing;
        }
        if ($bottom_spacing) {
            $widget_classes[] = $bottom_spacing;
        }

        // Remove duplicates and empty values
        $widget_classes = array_filter(array_unique($widget_classes));

        // Replace the class attribute in before_widget with the updated classes
        $before_widget = preg_replace(
            '/class=["\'].*?["\']/i',
            'class="' . esc_attr(implode(' ', $widget_classes)) . '"',
            $args['before_widget']
        );
        if (!preg_match('/class=["\'].*?["\']/i', $before_widget)) {
            // If no class attribute exists, add one
            $before_widget = str_replace('>', ' class="' . esc_attr(implode(' ', $widget_classes)) . '">', $args['before_widget']);
        }

        // Output the widget
        echo $before_widget;

        if (!empty($instance['shortcode'])) {
            $shortcode = trim($instance['shortcode']);
            echo do_shortcode($shortcode);
        }

        echo $args['after_widget'];
    }

    /**
     * Outputs the widget settings form in the admin dashboard.
     * 
     * This method is used to display the widget settings form in the admin dashboard.
     * 
     * @since 1.0.0
     *
     * @param array $instance The widget settings.
     * 
     * @return void
     */
    public function form($instance) {
        $defaults = array(
            'shortcode'      => '',
            'top_spacing'    => 'no',
            'bottom_spacing' => 'no'
        );
        $instance = wp_parse_args((array) $instance, $defaults);

        $shortcode = esc_textarea($instance['shortcode']);
        $top_spacing = $instance['top_spacing'];
        $bottom_spacing = $instance['bottom_spacing'];
        ?>
        <div class="sm-admin-input-wrap">
            <label for="<?php echo esc_attr($this->get_field_id('shortcode')); ?>">
                <?php esc_html_e('Enter Shortcode:', 'smarty_magazine'); ?>
            </label>
            <textarea class="widefat" rows="3" 
                      id="<?php echo esc_attr($this->get_field_id('shortcode')); ?>" 
                      name="<?php echo esc_attr($this->get_field_name('shortcode')); ?>"><?php echo esc_textarea($shortcode); ?></textarea>
            <small><?php esc_html_e('Example: [my_shortcode]', 'smarty_magazine'); ?></small>
        </div>

        <div class="sm-admin-input-wrap">
            <label for="<?php echo esc_attr($this->get_field_id('top_spacing')); ?>">
                <?php esc_html_e('Add Top Spacing', 'smarty_magazine'); ?>
            </label>
            <input 
                type="checkbox" 
                id="<?php echo esc_attr($this->get_field_id('top_spacing')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('top_spacing')); ?>" 
                value="yes" 
                <?php checked($top_spacing, 'yes'); ?>>
            <small><?php _e('Check to add top margin', 'smarty_magazine'); ?></small>
        </div>

        <div class="sm-admin-input-wrap">
            <label for="<?php echo esc_attr($this->get_field_id('bottom_spacing')); ?>">
                <?php esc_html_e('Add Bottom Spacing', 'smarty_magazine'); ?>
            </label>
            <input 
                type="checkbox" 
                id="<?php echo esc_attr($this->get_field_id('bottom_spacing')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('bottom_spacing')); ?>" 
                value="yes" 
                <?php checked($bottom_spacing, 'yes'); ?>>
            <small><?php _e('Check to add bottom margin', 'smarty_magazine'); ?></small>
        </div>
        <?php
    }

    /**
     * Handles widget settings updates.
     * 
     * This method is used to handle the widget settings updates.
     * 
     * @since 1.0.0
     *
     * @param array $new_instance New widget settings.
     * @param array $old_instance Previous widget settings.
     * 
     * @return array Updated settings.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['shortcode'] = !empty($new_instance['shortcode']) ? sanitize_text_field($new_instance['shortcode']) : '';
        $instance['top_spacing'] = isset($new_instance['top_spacing']) && $new_instance['top_spacing'] === 'yes' ? 'yes' : 'no';
        $instance['bottom_spacing'] = isset($new_instance['bottom_spacing']) && $new_instance['bottom_spacing'] === 'yes' ? 'yes' : 'no';
        return $instance;
    }
}
