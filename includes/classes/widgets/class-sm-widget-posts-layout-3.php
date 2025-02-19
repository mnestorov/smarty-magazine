<?php
/**
 * News List Layout 3 Widget.
 *
 * This widget displays posts in a dual-column layout. Each column can have its own title, 
 * category, and configuration options, allowing for flexible presentation of recently 
 * published posts.
 * 
 * @since 1.0.0
 * 
 * @package Smarty_Magazine
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class __Smarty_Magazine_Post_Layout_3 extends __Smarty_Magazine_Tabs_Content {
    /**
     * @var int Layout identifier for differentiating this layout style.
     */
    public $layout = 3;

    /**
     * Constructor: Initializes the widget with a unique ID, name, and description.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    public function __construct() {
        parent::__construct(
            '__Smarty_Magazine_Post_Layout_3',
            __('SM News Layout 3', 'smarty_magazine'),
            array(
                'description' => __('Posts display layout 3 for recently published post', 'smarty_magazine')
            )
        );
    }

    /**
     * Defines configuration fields for the widget settings.
     *
     * This method returns an array of configurable options displayed in the widget admin panel.
     * It allows setting titles, categories, number of posts, and ordering for two different columns.
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
                'name'  => 'title2',
                'label' => esc_html__('Title 2', 'smarty_magazine'),
            ),
            array(
                'type'  => 'list_cat',
                'name'  => 'category2',
                'label' => esc_html__('Categories 2', 'smarty_magazine'),
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

    /**
     * Renders the widget content on the front-end.
     *
     * This method outputs two columns, each displaying posts based on the provided settings 
     * (title, category, number of posts, order, and order by). It uses the parent widget 
     * rendering logic for consistency.
     * 
     * @since 1.0.0
     *
     * @param array $args Widget arguments.
     * @param array $instance The widget settings instance.
     * 
     * @return void
     */
    public function widget($args, $instance) {
        // Prepare the instance with default values.
        $instance = $this->setup_instance($instance);

        // Extract settings for the first column.
        $title        = isset($instance['title']) ? $instance['title'] : '';
        $category     = isset($instance['category']) ? $instance['category'] : '';
        $no_of_posts  = isset($instance['no_of_posts']) ? $instance['no_of_posts'] : '';

        // Extract settings for the second column.
        $title2       = isset($instance['title2']) ? $instance['title2'] : '';
        $category2    = isset($instance['category2']) ? $instance['category2'] : '';

        // Configuration array for the first column.
        $instance_1 = array(
            '__setup_data'  => false,
            'title'         => $title,
            'category'      => $category,
            'no_of_posts'   => $no_of_posts,
            'order'         => $instance['order'],
            'orderby'       => $instance['orderby'],
        );

        // Configuration array for the second column.
        $instance_2 = array(
            '__setup_data'    => false,
            'title'           => $title2,
            'category'        => $category2,
            'posts_per_page'  => $no_of_posts,
            'order'           => $instance['order'],
            'orderby'         => $instance['orderby'],
        );

        // Output the widget HTML structure.
        ?>
        <div class="sm-news-list-3">
            <!-- First Column -->
            <div class="sm-news-layout-half">
                <?php parent::widget(array(), $instance_1); ?>
            </div><!-- .sm-news-layout-half -->

            <!-- Second Column -->
            <div class="sm-news-layout-half sm-half-last">
                <?php parent::widget(array(), $instance_2); ?>
            </div><!-- .sm-news-layout-half.sm-half-last -->

            <div class="clearfix"></div>
        </div><!-- .sm-news-list-3 -->
        <?php
    }
}
