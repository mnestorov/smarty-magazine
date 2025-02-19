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
                'description' => __('Posts display layout 2 for recently published post', 'smarty_magazine')
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
