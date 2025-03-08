<?php

/**
 * Advertisement Widget (130x130).
 *
 * This widget displays a 130x130 advertisement banner in the sidebar.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class __Smarty_Magazine_Ads_130_130 extends WP_Widget {

    /**
     * Constructor: Initializes the widget with name and description.
     */
    public function __construct() {
        parent::__construct(
            '__smarty_magazine_ads_130_130',
            __('SM Ads 130x130', 'smarty_magazine'),
            array(
                'description' => __('Advertisement with size of 130x130 for sidebar position', 'smarty_magazine')
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
        $title = !empty($instance['title']) ? esc_html($instance['title']) : __('130x130 Ads', 'smarty_magazine');
        $ads_image_path = !empty($instance['ads_image']) ? esc_url($instance['ads_image']) : '';
        $ads_link = !empty($instance['ads_link']) ? esc_url($instance['ads_link']) : esc_url(home_url('/'));
        $ads_link_type = ($instance['ads_link_type'] === 'nofollow') ? 'nofollow' : 'dofollow';

        echo '<div class="sm-ads-130x130">';
        if ($ads_image_path) {
            echo sprintf(
                '<a href="%s" title="%s" rel="%s" target="_blank">
                    <img src="%s" alt="%s" />
                 </a>',
                $ads_link,
                $title,
                esc_attr($ads_link_type),
                $ads_image_path,
                $title
            );
        }
        echo '</div>';
    }

    /**
     * Outputs the widget settings form in the admin dashboard.
     *
     * @param array $instance Current settings.
     */
    public function form($instance) {
        $defaults = array(
            'title'        => '',
            'ads_link'     => '',
            'ads_image'    => '',
            'ads_link_type'=> ''
        );
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>

        <div class="sm-ads-130x130">
            <!-- Title Field -->
            <div class="sm-admin-input-wrap">
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'smarty_magazine'); ?></label>
                <input type="text"
                       id="<?php echo $this->get_field_id('title'); ?>"
                       name="<?php echo $this->get_field_name('title'); ?>"
                       value="<?php echo esc_attr($instance['title']); ?>"
                       placeholder="<?php _e('Advertise Title', 'smarty_magazine'); ?>">
            </div>

            <!-- Ads Link Field -->
            <div class="sm-admin-input-wrap">
                <label for="<?php echo $this->get_field_id('ads_link'); ?>"><?php _e('Ads Link', 'smarty_magazine'); ?></label>
                <input type="url"
                       id="<?php echo $this->get_field_id('ads_link'); ?>"
                       name="<?php echo $this->get_field_name('ads_link'); ?>"
                       value="<?php echo esc_url($instance['ads_link']); ?>"
                       placeholder="<?php _e('URL', 'smarty_magazine'); ?>">
            </div>

            <!-- Link Type Selector -->
            <div class="sm-admin-input-wrap">
                <label for="<?php echo $this->get_field_id('ads_link_type'); ?>"><?php _e('Link Type', 'smarty_magazine'); ?></label>
                <select id="<?php echo $this->get_field_id('ads_link_type'); ?>"
                        name="<?php echo $this->get_field_name('ads_link_type'); ?>">
                    <option value="dofollow" <?php selected($instance['ads_link_type'], 'dofollow'); ?>><?php _e('Do Follow', 'smarty_magazine'); ?></option>
                    <option value="nofollow" <?php selected($instance['ads_link_type'], 'nofollow'); ?>><?php _e('No Follow', 'smarty_magazine'); ?></option>
                </select>
            </div>

            <!-- Image Upload Section -->
            <div class="sm-admin-input-wrap">
                <label for="<?php echo $this->get_field_id('ads_image'); ?>"><?php _e('Ads Image', 'smarty_magazine'); ?></label>
                <?php if (!empty($instance['ads_image'])): ?>
                    <img src="<?php echo esc_url($instance['ads_image']); ?>" style="width:130px;height:130px;" />
                <?php else: ?>
                    <img src="" style="width:130px;height:130px;display:none;" />
                <?php endif; ?>
                <input type="hidden"
                       class="sm-custom-media-image"
                       id="<?php echo $this->get_field_id('ads_image'); ?>"
                       name="<?php echo $this->get_field_name('ads_image'); ?>"
                       value="<?php echo esc_attr($instance['ads_image']); ?>" />
                <input type="button"
                       class="sm-img-upload sm-custom-media-button"
                       id="custom_media_button"
                       name="<?php echo $this->get_field_name('ads_image'); ?>"
                       value="<?php _e('Select Image', 'smarty_magazine'); ?>" />
            </div>
        </div>

        <?php
    }

    /**
     * Sanitizes and saves widget settings.
     *
     * @param array $new_instance New settings as input by the user.
     * @param array $old_instance Previous settings.
     * @return array Updated settings.
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;

        $instance['title']         = sanitize_text_field($new_instance['title']);
        $instance['ads_link']      = esc_url_raw($new_instance['ads_link']);
        $instance['ads_link_type'] = sanitize_text_field($new_instance['ads_link_type']);
        $instance['ads_image']     = esc_url_raw($new_instance['ads_image']);

        return $instance;
    }
}
