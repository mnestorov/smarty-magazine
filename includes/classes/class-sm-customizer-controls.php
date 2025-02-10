<?php
/**
 * Custom Control Class for WordPress Customizer Group Headings
 *
 * This class extends WP_Customize_Control to create custom group heading sections 
 * in the WordPress Customizer. It supports different heading types such as 
 * standard headings, messages, and horizontal rules (hr).
 *
 * @package SmartyMagazine
 */

class __Smarty_Magazine_Group_Settings_Heading_Control extends WP_Customize_Control {
    /**
     * Default setting for the control.
     *
     * @var string
     */
    public $settings = 'blogname';

    /**
     * Description displayed below the heading.
     *
     * @var string
     */
    public $description = '';

    /**
     * Title of the group heading.
     *
     * @var string
     */
    public $title = '';

    /**
     * Group identifier, can be used for additional grouping logic.
     *
     * @var string
     */
    public $group = '';

    /**
     * Type of heading to render. 
     * Possible values: 'group_heading_top', 'group_heading', 'group_heading_message', 'hr'.
     *
     * @var string
     */
    public $type = '';

    /**
     * Render the content for the customizer control.
     *
     * Based on the 'type' property, this method renders different heading styles 
     * or a horizontal rule. It outputs sanitized HTML to ensure security.
     *
     * @return void
     */
    public function render_content() {
        switch ($this->type) {
            case 'group_heading_top':
                echo '<h4 class="customizer-group-heading group-heading-top">' . esc_html($this->title) . '</h4>';
                if (!empty($this->description)) {
                    echo '<p class="customizer-group-subheading">' . esc_html($this->description) . '</p>';
                }
                break;

            case 'group_heading':
                echo '<h4 class="customizer-group-heading">' . esc_html($this->title) . '</h4>';
                if (!empty($this->description)) {
                    echo '<p class="customizer-group-subheading">' . esc_html($this->description) . '</p>';
                }
                break;

            case 'group_heading_message':
                echo '<h4 class="customizer-group-heading-message">' . esc_html($this->title) . '</h4>';
                if (!empty($this->description)) {
                    echo '<p class="customizer-group-heading-message">' . esc_html($this->description) . '</p>';
                }
                break;

            case 'hr':
                echo '<hr />';
                break;

            default:
                // Optionally handle unexpected types here.
                break;
        }
    }
}
