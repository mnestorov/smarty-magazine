<?php

/**
 * News List Layout 1 Widget.
 *
 * This widget displays posts in layout style 1, focusing on recently published posts.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class __Smarty_Magazine_Post_Layout_1 extends __Smarty_Magazine_Tabs_Content {
    /**
     * @var int Layout identifier.
     */
    public $layout = 1;

    /**
     * Constructor: Initializes the widget with name and description.
     */
    public function __construct() {
        parent::__construct(
            '__Smarty_Magazine_Post_Layout_1',
            __('SM News Layout 1', 'smarty_magazine'),
            array(
                'description' => __('Posts display layout 1 for recently published post', 'smarty_magazine')
            )
        );
    }

    /**
     * Defines the configuration fields for the widget.
     *
     * This method returns an array of configuration options that will be displayed
     * in the widget admin settings.
     *
     * @return array Configuration fields for widget settings.
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
                'label' => esc_html__('No. of Posts', 'smarty_magazine'),
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
        );

        return $fields;
    }
}
