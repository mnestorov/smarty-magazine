<?php
/**
 * Register widget area.
 * 
 * @since 1.0.0
 *
 * @package SmartyMagazine
 */

if (!function_exists('__smarty_magazine_widgets_init')) {
    /**
     * Register widget areas.
     * 
     * @since 1.0.0
     * 
     * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
     * 
     * @return void
     */
    function __smarty_magazine_widgets_init() {
        $sidebars = [
            [
                'name'          => __('Sidebar', 'smarty_magazine'),
                'id'            => 'sm-right-sidebar',
                'description'   => __('Add widgets to show widgets at the right panel of the page', 'smarty_magazine'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s sm-sidebar-widget">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ],
            [
                'name'          => __('Home Sidebar', 'smarty_magazine'),
                'id'            => 'sm-home-sidebar',
                'description'   => __('Add widgets to show widgets at the right panel of the Home page', 'smarty_magazine'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s sm-sidebar-widget">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ],
            [
                'name'          => __('News Sidebar', 'smarty_magazine'),
                'id'            => 'sm-news-sidebar',
                'description'   => __('Add widgets to show widgets at the right panel of the News post type', 'smarty_magazine'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s sm-sidebar-widget">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ],
            [
                'name'          => __('Archive Topbar', 'smarty_magazine'),
                'id'            => 'sm-archive-sidebar',
                'description'   => __('Add widgets to show widgets at the top of the Archive pages', 'smarty_magazine'),
                'before_widget' => '',
                'after_widget'  => '',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ],
            [
                'name'          => __('Top Bar: Search', 'smarty_magazine'),
                'id'            => 'sm-top-bar-search',
                'description'   => __('Top Bar search icon', 'smarty_magazine'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ],
            [
                'name'          => __('Top Bar: Social', 'smarty_magazine'),
                'id'            => 'sm-top-bar-social',
                'description'   => __('Top Bar social icons', 'smarty_magazine'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ],
            [
                'name'          => __('Top Bar: Shortcode', 'smarty_magazine'),
                'id'            => 'sm-top-bar-shortcode',
                'description'   => __('Top Bar shortcode position', 'smarty_magazine'),
                'before_widget' => '<span id="%1$s" class="widget %2$s me-3">',
                'after_widget'  => '</span>',
                'before_title'  => '',
                'after_title'   => '',
            ],
            [
                'name'          => __('Header: Ads 728x90', 'smarty_magazine'),
                'id'            => 'sm-header-ads728x90',
                'description'   => __('Shows advertisement at the header position beside the logo', 'smarty_magazine'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ],
            [
                'name'          => __('Homepage: News Ticker', 'smarty_magazine'),
                'id'            => 'sm-news-ticker',
                'description'   => __('Shows news ticker', 'smarty_magazine'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ],
            [
                'name'          => __('Homepage: Feautured Slider', 'smarty_magazine'),
                'id'            => 'sm-featured-news-slider',
                'description'   => __('Add widgets to show at Frontpage featured News slider', 'smarty_magazine'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ],
            [
                'name'          => __('Homepage: Highlighted News', 'smarty_magazine'),
                'id'            => 'sm-highlighted-news',
                'description'   => __('Add widgets to show at front page highlighted news beside the featured news slider', 'smarty_magazine'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ],
            [
                'name'          => __('Homepage: News Section', 'smarty_magazine'),
                'id'            => 'sm-front-top-section-news',
                'description'   => __('Add widgets to show a list of news from a category at front page section', 'smarty_magazine'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ],
            [
                'name'          => __('Footer: Position 1', 'smarty_magazine'),
                'id'            => 'sm-footer1',
                'description'   => __('Add widgets to show widgets at Footer Position 1', 'smarty_magazine'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ],
            [
                'name'          => __('Footer: Position 2', 'smarty_magazine'),
                'id'            => 'sm-footer2',
                'description'   => __('Add widgets to show widgets at Footer Position 2', 'smarty_magazine'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ],
            [
                'name'          => __('Footer: Position 3', 'smarty_magazine'),
                'id'            => 'sm-footer3',
                'description'   => __('Add widgets to show widgets at Footer Position 3', 'smarty_magazine'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ],
            [
                'name'          => __('Footer: Position 4', 'smarty_magazine'),
                'id'            => 'sm-footer4',
                'description'   => __('Add widgets to show widgets at Footer Position 4', 'smarty_magazine'),
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ],
        ];

        foreach ($sidebars as $sidebar) {
            register_sidebar([
                'name'          => $sidebar['name'],
                'id'            => $sidebar['id'],
                'description'   => $sidebar['description'],
                'before_widget' => $sidebar['before_widget'],
                'after_widget'  => $sidebar['after_widget'],
                'before_title'  => $sidebar['before_title'],
                'after_title'   => $sidebar['after_title'],
            ]);
        }
    }
    add_action('widgets_init', '__smarty_magazine_widgets_init');
}

if (!function_exists('__smarty_magazine_register_widgets')) {
    /**
     * Register widgets.
     * 
     * @since 1.0.0
     * 
     * @return void
     * 
     * @link https://developer.wordpress.org/reference/functions/register_widget/
     */
    function __smarty_magazine_register_widgets() {
        $widgets = [
            '__Smarty_Magazine_Featured_Post_Slider',
            '__Smarty_Magazine_Social_Icons',
            '__Smarty_Magazine_Shortcodes',
            '__Smarty_Magazine_Ads_728_90',
            '__Smarty_Magazine_Ads_130_130',
            '__Smarty_Magazine_Ads_870_150',
            '__Smarty_Magazine_Ads_300_250',
            '__Smarty_Magazine_News_Ticker',
            '__Smarty_Magazine_Highlighted_News',
            '__Smarty_Magazine_News_Categories',
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