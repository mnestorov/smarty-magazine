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
        echo $args['before_widget'];

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
        $shortcode = !empty($instance['shortcode']) ? esc_textarea($instance['shortcode']) : '';
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
        return $instance;
    }
}
