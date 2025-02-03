<?php

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 * @package SmartyMagazine
 */

if (!function_exists('__smarty_magazine_widgets_init')) {
    function __smarty_magazine_widgets_init() {
        $sidebars = [
            [
                'name'        => __('Sidebar', 'smarty_magazine'),
                'id'          => 'sm-right-sidebar',
                'description' => __('Add widgets to show widgets at the right panel of the page', 'smarty_magazine')
            ],
            [
                'name'        => __('Top Bar Search', 'smarty_magazine'),
                'id'          => 'sm-top-bar-search',
                'description' => __('Top Bar Search Position', 'smarty_magazine')
            ],
            [
                'name'        => __('Top Bar Social', 'smarty_magazine'),
                'id'          => 'sm-top-bar-social',
                'description' => __('Top Bar Search social icons', 'smarty_magazine')
            ],
            [
                'name'        => __('Header Ads 728x90', 'smarty_magazine'),
                'id'          => 'sm-header-ads728x90',
                'description' => __('Shows Advertisement at the header position beside the logo', 'smarty_magazine')
            ],
            [
                'name'        => __('Hompage: News Ticker', 'smarty_magazine'),
                'id'          => 'sm-news-ticker',
                'description' => __('Shows News Ticker', 'smarty_magazine')
            ],
            [
                'name'        => __('Homepage: Feautured Slider', 'smarty_magazine'),
                'id'          => 'sm-featured-news-slider',
                'description' => __('Add widgets to show at Frontpage featured News slider', 'smarty_magazine')
            ],
            [
                'name'        => __('Homepage: Highlighted News', 'smarty_magazine'),
                'id'          => 'sm-highlighted-news',
                'description' => __('Add widgets to show at Frontpage Highlighted News beside the featured News slider', 'smarty_magazine')
            ],
            [
                'name'        => __('Homepage: News Section', 'smarty_magazine'),
                'id'          => 'sm-front-top-section-news',
                'description' => __('Add widgets to show a list of news from a category at Front page Section', 'smarty_magazine')
            ],
            [
                'name'        => __('Footer Position 1', 'smarty_magazine'),
                'id'          => 'sm-footer1',
                'description' => __('Add widgets to show widgets at Footer Position 1', 'smarty_magazine')
            ],
            [
                'name'        => __('Footer Position 2', 'smarty_magazine'),
                'id'          => 'sm-footer2',
                'description' => __('Add widgets to show widgets at Footer Position 2', 'smarty_magazine')
            ],
            [
                'name'        => __('Footer Position 3', 'smarty_magazine'),
                'id'          => 'sm-footer3',
                'description' => __('Add widgets to show widgets at Footer Position 3', 'smarty_magazine')
            ],
            [
                'name'        => __('Footer Position 4', 'smarty_magazine'),
                'id'          => 'sm-footer4',
                'description' => __('Add widgets to show widgets at Footer Position 4', 'smarty_magazine')
            ],
        ];

        foreach ($sidebars as $sidebar) {
            register_sidebar([
                'name'          => $sidebar['name'],
                'id'            => $sidebar['id'],
                'description'   => $sidebar['description'],
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ]);
        }
    }
    add_action('widgets_init', '__smarty_magazine_widgets_init');
}

if (!function_exists('__smarty_magazine_media_script')) {
    /**
     * Enqueue Admin Scripts
     */
    function __smarty_magazine_media_script($hook) {
        if ($hook !== 'widgets.php') {
            return;
        }

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_style('sm-widgets-css', get_template_directory_uri() . '/assets/css/sm-widgets.css');
        
        wp_enqueue_media();

        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script('sm-widgets-js', get_template_directory_uri() . '/assets/js/sm-widgets.js', ['jquery'], '', true);
    }
    add_action('admin_enqueue_scripts', '__smarty_magazine_media_script');
}

if (!function_exists('__smarty_magazine_register_widgets')) {
    /**
     * Register and initialize widgets
     */
    function __smarty_magazine_register_widgets() {
        $widgets = [
            '__Smarty_Magazine_Featured_Post_Slider',
            '__Smarty_Magazine_Social_Icons',
            '__Smarty_Magazine_Ads_728_90',
            '__Smarty_Magazine_Ads_130_130',
            '__Smarty_Magazine_Ads_870_150',
            '__Smarty_Magazine_Ads_262_220',
            '__Smarty_Magazine_News_Ticker',
            '__Smarty_Magazine_Highlighted_News',
            '__Smarty_Magazine_Post_Layout_1',
            '__Smarty_Magazine_Post_Layout_2',
            '__Smarty_Magazine_Post_Layout_3',
            '__Smarty_Magazine_Post_Layout_4',
        ];
    
        foreach ($widgets as $widget) {
            if (class_exists($widget)) {
                register_widget($widget);
            } else {
                error_log("Widget class $widget not found.");
            }
        }
    }
    add_action('widgets_init', '__smarty_magazine_register_widgets');    
}