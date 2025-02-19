<?php
/**
 * News List Layout 4 Widget.
 *
 * This widget displays posts in a sidebar-friendly layout, ideal for highlighting 
 * recently published content in a compact format.
 * 
 * @since 1.0.0
 * 
 * @package Smarty_Magazine
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class __Smarty_Magazine_Post_Layout_4 extends __Smarty_Magazine_Tabs_Content {
    /**
     * @var int Layout identifier for distinguishing this layout style.
     */
    public $layout = 4;

    /**
     * @var string CSS class applied to the layout container for styling.
     */
    public $layout_class = 'sm-sidebar-news';

    /**
     * Constructor: Initializes the widget with its ID, name, and description.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    public function __construct() {
        parent::__construct(
            '__Smarty_Magazine_Post_Layout_4',
            __('SM News Layout 4', 'smarty_magazine'),
            array(
                'description' => __('Posts display layout 4 for recently published posts', 'smarty_magazine')
            )
        );
    }

    /**
     * Defines configuration fields for the widget settings.
     *
     * This method returns an array of fields that appear in the widget admin panel,
     * allowing customization of the title, categories, number of posts, and order settings.
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
                'type'  => 'list_cat',
                'name'  => 'category',
                'label' => esc_html__('Categories', 'smarty_magazine'),
            ),
            array(
                'type'  => 'text',
                'name'  => 'no_of_posts',
                'label' => esc_html__('Number of Posts', 'smarty_magazine'),
            ),
            array(
                'type'  => 'orderby',
                'name'  => 'orderby',
                'label' => esc_html__('Order By', 'smarty_magazine'),
            ),
            array(
                'type'  => 'order',
                'name'  => 'order',
                'label' => esc_html__('Order', 'smarty_magazine'),
            ),
        );

        return $fields;
    }
}
