<?php

/**
 * Theme Customizer.
 *
 * @package SmartyMagazine
 */

if (!function_exists('__smarty_magazine_customize_register')) {
	/**
	 * Add postMessage support for site title and description for the Theme Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	function __smarty_magazine_customize_register($wp_customize) {
		$wp_customize->get_setting('blogname')->transport         = 'postMessage';
		$wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
		$wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

		// Load custom controls
		require get_template_directory() . '/includes/classes/class-sm-customizer-controls.php';

		// Header Settings
		$wp_customize->add_panel('__smarty_magazine_header_options',	array(
			'priority' 			=> 60,
			'title' 			=> __('Header', 'smarty_magazine'),
			'description' 		=> __('Header Settings', 'smarty_magazine'),
			'capability' 		=> 'edit_theme_options'
		));

		// Sticky Menu Section
		$wp_customize->add_section('__smarty_magazine_sticky_menu_section', array(
			'priority'  => 100,
			'title'     => __('Sticky Menu', 'smarty_magazine'),
			'panel'     => '__smarty_magazine_header_options'
		));

		// Sticky Desktop Menu Setting
		$wp_customize->add_setting('__smarty_magazine_sticky_menu', array(
			'default' 			=> 0,
			'capability' 		=> 'edit_theme_options',
			'sanitize_callback' => '__smarty_magazine_checkbox_sanitize'
		));

		// Sticky Desktop Menu Control
		$wp_customize->add_control('__smarty_magazine_sticky_menu', array(
			'type' 				=> 'checkbox',
			'label' 			=> __('Enable Sticky Desktop Nenu', 'smarty_magazine'),
			'section' 			=> '__smarty_magazine_sticky_menu_section'
		));

		// Sticky Mobile Menu Setting
		$wp_customize->add_setting('__smarty_magazine_sticky_mobile_menu', array(
			'default'           => 0,
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => '__smarty_magazine_checkbox_sanitize',
		));

		// Sticky Mobile Menu Control
		$wp_customize->add_control('__smarty_magazine_sticky_mobile_menu', array(
			'type'    => 'checkbox',
			'label'   => __('Enable Sticky Mobile Menu', 'smarty_magazine'),
			'section' => '__smarty_magazine_sticky_menu_section',
		));

		
		
		

		$wp_customize->add_section('header_topbar', array(
			'title' 			=> __('Header Topbar', 'smarty_magazine'),
			'panel' 			=> '__smarty_magazine_header_options'
		));

		// Custom Shortcode Section
		$wp_customize->add_section('__smarty_magazine_custom_shortcode_section', array(
			'priority'    => 110,
			'title'       => __('Shortcode', 'smarty_magazine'),
			'description' => __('Insert a custom shortcode to display under the header area.', 'smarty_magazine'),
			'panel'       => '__smarty_magazine_header_options'
		));

		// Custom Shortcode Setting
		$wp_customize->add_setting('__smarty_magazine_custom_shortcode', array(
			'default'           => '',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field'
		));

		// Custom Shortcode Control
		$wp_customize->add_control('__smarty_magazine_custom_shortcode', array(
			'type'        => 'text',
			'label'       => __('Shortcode', 'smarty_magazine'),
			'description' => __('Enter the shortcode you want to display under the header (e.g., [my_shortcode]).', 'smarty_magazine'),
			'section'     => '__smarty_magazine_custom_shortcode_section',
		));

		$wp_customize->add_setting('__smarty_magazine_hide_date_setting', array(
			'default' 			=> '1',
			'capability' 		=> 'edit_theme_options',
			'sanitize_callback'	=> '__smarty_magazine_checkbox_sanitize'
		));

		$wp_customize->add_control('__smarty_magazine_hide_date', array(
			'type' 				=> 'checkbox',
			'label' 			=> __('Hide Header Date', 'smarty_magazine'),
			'section' 			=> 'header_topbar',
			'settings' 			=> '__smarty_magazine_hide_date_setting'
		));

		// Header Background Color Setting
		$wp_customize->add_setting('__smarty_magazine_header_bg_color', array(
			'default'              => '#ffffff',
			'capability'           => 'edit_theme_options',
			'sanitize_callback'    => '__smarty_magazine_color_sanitize',
			'sanitize_js_callback' => '__smarty_magazine_color_escaping_sanitize',
			'transport'            => 'postMessage',
		));

		// Header Background Color Control
		$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, '__smarty_magazine_header_bg_color_control', array(
			'label'    => __('Header Background Color', 'smarty_magazine'),
			'section'  => 'colors', // Attach directly to the Colors section
			'settings' => '__smarty_magazine_header_bg_color',
		)));

		// Header Text Logo Color Setting
		$wp_customize->add_setting('__smarty_magazine_header_text_color', array(
			'default'              => '#222222',
			'capability'           => 'edit_theme_options',
			'sanitize_callback'    => '__smarty_magazine_color_sanitize',
			'sanitize_js_callback' => '__smarty_magazine_color_escaping_sanitize',
			'transport'            => 'postMessage',
		));

		// Header Text Logo Color Control
		$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, '__smarty_magazine_header_text_color_control', array(
			'label'    => __('Header Text Logo Color', 'smarty_magazine'),
			'section'  => 'colors', // Attach directly to the Colors section
			'settings' => '__smarty_magazine_header_text_color',
		)));

		// Main Menu Color
		$wp_customize->add_section('__smarty_magazine_menu_color_section', array(
			'priority' 			=> 3,
			'title' 			=> __('Menu Color', 'smarty_magazine'),
			'panel'				=> 'colors'
		));

		$wp_customize->add_setting('__smarty_magazine_menu_color', array(
			'priority' 			     => 6,
			'default' 			     => '#ffffff',
			'capability' 			 => 'edit_theme_options',
			'sanitize_callback'		 => '__smarty_magazine_color_sanitize',
			'sanitize_js_callback'   => '__smarty_magazine_color_escaping_sanitize'
		));

		// Menu Text Color
		$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, '__smarty_magazine_menu_color_picker', array(
			'label' 		=> __('Menu Text Color', 'smarty_magazine'),
			'section' 		=> 'colors',
			'settings' 		=> '__smarty_magazine_menu_color'
		)));

		$wp_customize->add_setting('__smarty_magazine_menu_bg_color', array(
			'priority' 				 => 7,
			'default' 				 => '#cf4141',
			'capability' 			 => 'edit_theme_options',
			'sanitize_callback'		 => '__smarty_magazine_color_sanitize',
			'sanitize_js_callback'   => '__smarty_magazine_color_escaping_sanitize'
		));

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'__smarty_magazine_menu_bg_color_picker',
				array(
					'label' 			=> __('Menu Background', 'smarty_magazine'),
					'section' 			=> 'colors',
					'settings' 			=> '__smarty_magazine_menu_bg_color'
				)
			)
		);

		$wp_customize->add_setting(
			'__smarty_magazine_menu_color_hover',
			array(
				'priority' 			     => 8,
				'default' 			     => '#ffffff',
				'capability' 			 => 'edit_theme_options',
				'sanitize_callback'		 => '__smarty_magazine_color_sanitize',
				'sanitize_js_callback'   => '__smarty_magazine_color_escaping_sanitize'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'__smarty_magazine_menu_hover_color_picker',
				array(
					'label' 			=> __('Menu Text Hover Color', 'smarty_magazine'),
					'section' 			=> 'colors',
					'settings' 			=> '__smarty_magazine_menu_color_hover'
				)
			)
		);

		$wp_customize->add_setting(
			'__smarty_magazine_menu_hover_bg_color',
			array(
				'priority' 				 => 9,
				'default' 				 => '#be3434',
				'capability' 			 => 'edit_theme_options',
				'sanitize_callback'		 => '__smarty_magazine_color_sanitize',
				'sanitize_js_callback'   => '__smarty_magazine_color_escaping_sanitize'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'__smarty_magazine_menu_hover_bg_color_picker',
				array(
					'label' 			=> __('Menu Hover Background', 'smarty_magazine'),
					'section' 			=> 'colors',
					'settings' 			=> '__smarty_magazine_menu_hover_bg_color'
				)
			)
		);

		// News Ticker Background Color
		$wp_customize->add_setting('__smarty_magazine_news_ticker_bg', array(
			'default'           => '#222222',
			'sanitize_callback' => '__smarty_magazine_color_sanitize',
		));

		$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, '__smarty_magazine_news_ticker_bg', array(
			'label'    => __('News Ticker Background', 'smarty_magazine'),
			'section'  => 'colors',
			'settings' => '__smarty_magazine_news_ticker_bg',
		)));
	
		// News Ticker Border Color
		$wp_customize->add_setting('__smarty_magazine_news_ticker_border', array(
			'default'           => '#ffffff',
			'sanitize_callback' => '__smarty_magazine_color_sanitize',
		));

		$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, '__smarty_magazine_news_ticker_border', array(
			'label'    => __('News Ticker Border', 'smarty_magazine'),
			'section'  => 'colors',
			'settings' => '__smarty_magazine_news_ticker_border',
		)));
	
		// News Ticker Tag Background Color
		$wp_customize->add_setting('__smarty_magazine_news_ticker_tag_bg', array(
			'default'           => '#222222',
			'sanitize_callback' => '__smarty_magazine_color_sanitize',
		));

		$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, '__smarty_magazine_news_ticker_tag_bg', array(
			'label'    => __('News Ticker Tag Background', 'smarty_magazine'),
			'section'  => 'colors',
			'settings' => '__smarty_magazine_news_ticker_tag_bg',
		)));

		// News Ticker Tag Icon Color
		$wp_customize->add_setting('__smarty_magazine_news_ticker_icon', array(
			'default'           => '#be3434',
			'sanitize_callback' => '__smarty_magazine_color_sanitize',
		));

		$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, '__smarty_magazine_news_ticker_icon', array(
			'label'    => __('News Ticker Icon Color', 'smarty_magazine'),
			'section'  => 'colors', 
			'settings' => '__smarty_magazine_news_ticker_icon',
		)));
	
		// News Ticker Tag Text Color
		$wp_customize->add_setting('__smarty_magazine_news_ticker_tag_text', array(
			'default'           => '#ffffff',
			'sanitize_callback' => '__smarty_magazine_color_sanitize',
		));
		
		$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, '__smarty_magazine_news_ticker_tag_text', array(
			'label'    => __('News Ticker Tag Text Color', 'smarty_magazine'),
			'section'  => 'colors', 
			'settings' => '__smarty_magazine_news_ticker_tag_text',
		)));
	
		// News Ticker Tag Arrow Color
		$wp_customize->add_setting('__smarty_magazine_news_ticker_arrow', array(
			'default'           => '#222222',
			'sanitize_callback' => '__smarty_magazine_color_sanitize',
		));

		$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, '__smarty_magazine_news_ticker_arrow', array(
			'label'    => __('News Ticker Tag Arrow Color', 'smarty_magazine'),
			'section'  => 'colors',
			'settings' => '__smarty_magazine_news_ticker_arrow',
		)));
	
		// News Ticker Text Color
		$wp_customize->add_setting('__smarty_magazine_news_ticker_text', array(
			'default'           => '#ffffff',
			'sanitize_callback' => '__smarty_magazine_color_sanitize',
		));

		$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, '__smarty_magazine_news_ticker_text', array(
			'label'    => __('News Ticker Text Color', 'smarty_magazine'),
			'section'  => 'colors',  // Directly under Colors
			'settings' => '__smarty_magazine_news_ticker_text',
		)));

		// Footer backgounr color
		$wp_customize->add_setting(
			'__smarty_magazine_footer_bg_color',
			array(
				'priority' 				 => 9,
				'default' 				 => '#222222',
				'capability' 			 => 'edit_theme_options',
				'sanitize_callback'		 => '__smarty_magazine_color_sanitize',
				'sanitize_js_callback'   => '__smarty_magazine_color_escaping_sanitize'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'__smarty_magazine_footer_bg_color',
				array(
					'label' 			=> __('Footer Main Background', 'smarty_magazine'),
					'section' 			=> 'colors',
					'settings' 			=> '__smarty_magazine_footer_bg_color'
				)
			)
		);

		$wp_customize->add_setting(
			'__smarty_magazine_footer_text_color',
			array(
				'priority' 				 => 9,
				'default' 				 => '#ffffff',
				'capability' 			 => 'edit_theme_options',
				'sanitize_callback'		 => '__smarty_magazine_color_sanitize',
				'sanitize_js_callback'   => '__smarty_magazine_color_escaping_sanitize'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'__smarty_magazine_footer_text_color',
				array(
					'label' 			=> __('Footer Text Color', 'smarty_magazine'),
					'section' 			=> 'colors',
					'settings' 			=> '__smarty_magazine_footer_text_color'
				)
			)
		);
		$wp_customize->add_setting(
			'__smarty_magazine_footer_text_hover_color',
			array(
				'priority' 				 => 9,
				'default' 				 => '#cc2936',
				'capability' 			 => 'edit_theme_options',
				'sanitize_callback'		 => '__smarty_magazine_color_sanitize',
				'sanitize_js_callback'   => '__smarty_magazine_color_escaping_sanitize'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'__smarty_magazine_footer_text_hover_color',
				array(
					'label' 			=> __('Footer Text Hover Color', 'smarty_magazine'),
					'section' 			=> 'colors',
					'settings' 			=> '__smarty_magazine_footer_text_hover_color'
				)
			)
		);

		// Layout and Content
		$wp_customize->add_panel(
			'__smarty_magazine_layout_options',
			array(
				'capabitity' 		=> 'edit_theme_options',
				'description' 		=> __('Layout and Content Settings', 'smarty_magazine'),
				'priority' 			=> 201,
				'title' 			=> __('Layout and Content', 'smarty_magazine')
			)
		);

		// Website Default Layout
		$wp_customize->add_section(
			'__smarty_magazine_website_layout',
			array(
				'priority' 			=> 1,
				'title' 			=> __('Website Layout', 'smarty_magazine'),
				'panel'				=> '__smarty_magazine_layout_options'
			)
		);

		$wp_customize->add_setting(
			'__smarty_magazine_default_layout',
			array(
				'default' 			=> 'boxed_layout',
				'capability' 		=> 'edit_theme_options',
				'sanitize_callback' => '__smarty_magazine_site_layout_sanitize'
			)
		);

		$wp_customize->add_control('__smarty_magazine_default_layout', array(
			'type'			 	=> 'radio',
			'label' 			=> __('Choose layout: The change will make to whole site', 'smarty_magazine'),
			'choices' 			=> array(
				'boxed_layout'  => __('Boxed Layout', 'smarty_magazine'),
				'wide_layout'  	=> __('Wide Layout', 'smarty_magazine')
			),
			'section'			=> '__smarty_magazine_website_layout',
			'settings' 			=> '__smarty_magazine_default_layout'
		));

		// Website Default Layout
		$wp_customize->add_section(
			'__smarty_magazine_website_layout',
			array(
				'priority' 			=> 1,
				'title' 			=> __('Website Layout', 'smarty_magazine'),
				'panel'				=> '__smarty_magazine_layout_options'
			)
		);

		$wp_customize->add_setting(
			'__smarty_magazine_default_layout',
			array(
				'default' 			=> 'wide_layout',
				'capability' 		=> 'edit_theme_options',
				'sanitize_callback' => '__smarty_magazine_site_layout_sanitize'
			)
		);

		$wp_customize->add_control(
			'__smarty_magazine_default_layout',
			array(
				'type'			 	=> 'radio',
				'label' 			=> __('Choose layout: The change will make to whole site', 'smarty_magazine'),
				'choices' 			=> array(
					'boxed_layout'  => __('Boxed Layout', 'smarty_magazine'),
					'wide_layout'  	=> __('Wide Layout', 'smarty_magazine')
				),
				'section'			=> '__smarty_magazine_website_layout',
				'settings' 			=> '__smarty_magazine_default_layout'
			)
		);

		// Related Posts
		$wp_customize->add_section(
			'__smarty_magazine_related_posts_section',
			array(
				'priority' 			=> 4,
				'title' 			=> __('Related Posts', 'smarty_magazine'),
				'panel' 			=> '__smarty_magazine_layout_options',
			)
		);

		$wp_customize->add_setting(
			'__smarty_magazine_related_posts_setting',
			array(
				'default' 			=> 0,
				'capability' 		=> 'edit_theme_options',
				'sanitize_callback'	=> '__smarty_magazine_checkbox_sanitize'
			)
		);

		$wp_customize->add_control(
			'__smarty_magazine_related_posts',
			array(
				'type' 				=> 'checkbox',
				'label' 			=> __('Check to activate the related posts', 'smarty_magazine'),
				'section' 			=> '__smarty_magazine_related_posts_section',
				'settings' 			=> '__smarty_magazine_related_posts_setting'
			)
		);

		// Default Font Size
		$wp_customize->add_section(
			'__smarty_magazine_font_size_section',
			array(
				'priority' 			=> 5,
				'title' 			=> __('Default Font Size', 'smarty_magazine'),
				'panel'				=> '__smarty_magazine_layout_options'
			)
		);

		$wp_customize->add_setting(
			'__smarty_magazine_font_size',
			array(
				'default' 			=> '15',
				'capability' 		=> 'edit_theme_options',
				'sanitize_callback' => '__smarty_magazine_sanitize_integer'
			)
		);

		$wp_customize->add_control(
			'__smarty_magazine_font_size',
			array(
				'type'			 	=> 'number',
				'label' 			=> __('Set Default Font Size', 'smarty_magazine'),
				'section'			=> '__smarty_magazine_font_size_section',
				'settings' 			=> '__smarty_magazine_font_size'
			)
		);
		

		// Primary Color
		$wp_customize->add_setting(
			'__smarty_magazine_primary_color',
			array(
				'default' 			     => '#cc2936',
				'capability' 			 => 'edit_theme_options',
				'sanitize_callback'		 => '__smarty_magazine_color_sanitize',
				'sanitize_js_callback'   => '__smarty_magazine_color_escaping_sanitize'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'__smarty_magazine_primary_color',
				array(
					'label' 		=> __('Global Primary Color', 'smarty_magazine'),
					'section' 		=> 'colors',
					'settings' 		=> '__smarty_magazine_primary_color'
				)
			)
		);

		// Secondary Color
		$wp_customize->add_setting(
			'__smarty_magazine_secondary_color',
			array(
				'default' 			     => '#222222',
				'capability' 			 => 'edit_theme_options',
				'sanitize_callback'		 => '__smarty_magazine_color_sanitize',
				'sanitize_js_callback'   => '__smarty_magazine_color_escaping_sanitize'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'__smarty_magazine_secondary_color',
				array(
					'label' 		=> __('Global Secondary Color', 'smarty_magazine'),
					'section' 		=> 'colors',
					'settings' 		=> '__smarty_magazine_secondary_color'
				)
			)
		);

		// Footer Settings
		$wp_customize->add_section(
			'__smarty_magazine_footer_options',
			array(
				'title'       => esc_html__('Footer', 'smarty_magazine'),
				'priority'    => 210,
				'description' => esc_html__('Customize the footer settings including background color, text color, and copyright text.', 'smarty_magazine'),
			)
		);

		// Footer Copyright Text Setting
		$wp_customize->add_setting(
			'__smarty_magazine_footer_copyright',
			array(
				'default'           => esc_html__('© 2025 Smarty Magazine. All rights reserved.', 'smarty_magazine'),
				'sanitize_callback' => 'sanitize_text_field',
				'capability'        => 'edit_theme_options',
			)
		);

		// Footer Copyright Control
		$wp_customize->add_control(
			'__smarty_magazine_footer_copyright',
			array(
				'label'       => esc_html__('Footer Copyright Text', 'smarty_magazine'),
				'description' => esc_html__('Enter the text you want to display in the footer. HTML is allowed.', 'smarty_magazine'),
				'section'     => '__smarty_magazine_footer_options',
				'type'        => 'text',
			)
		);
	}
}

if (!function_exists('__smarty_magazine_checkbox_sanitize')) {
	/**
	 * Sanitize checkbox inputs.
	 *
	 * @param mixed $input The input value.
	 * @return int|string Returns 1 if checked, otherwise an empty string.
	 */
	function __smarty_magazine_checkbox_sanitize($input) {
		return ($input == 1) ? 1 : '';
	}
}

if (!function_exists('__smarty_magazine_color_sanitize')) {
	/**
	 * Sanitize color input by ensuring it's a valid hex code.
	 *
	 * @param string $color The color input.
	 * @return string The sanitized color with hash or original input.
	 */
	function __smarty_magazine_color_sanitize($color) {
		if ($unhashed = sanitize_hex_color_no_hash($color)) {
			return '#' . $unhashed;
		}
		return $color;
	}
}

if (!function_exists('__smarty_magazine_color_escaping_sanitize')) {
	/**
	 * Escape color input for safe output in JavaScript.
	 *
	 * @param string $input The color input.
	 * @return string The escaped color string.
	 */
	function __smarty_magazine_color_escaping_sanitize($input) {
		return esc_attr($input);
	}
}

if (!function_exists('__smarty_magazine_site_layout_sanitize')) {
	/**
	 * Sanitize site layout input.
	 *
	 * @param string $input The layout input.
	 * @return string The validated layout option or an empty string if invalid.
	 */
	function __smarty_magazine_site_layout_sanitize($input) {
		$valid_keys = array(
			'boxed_layout' => __('Boxed Layout', 'smarty_magazine'),
			'wide_layout'  => __('Wide Layout', 'smarty_magazine'),
		);

		return array_key_exists($input, $valid_keys) ? $input : '';
	}
}

if (!function_exists('__smarty_magazine_page_layout_sanitize')) {
	/**
	 * Sanitize page layout input.
	 *
	 * @param string $input The layout input.
	 * @return string The validated layout option or an empty string if invalid.
	 */
	function __smarty_magazine_page_layout_sanitize($input) {
		$valid_keys = array(
			'right_sidebar' => __('Right Sidebar', 'smarty_magazine'),
			'left_sidebar'  => __('Left Sidebar', 'smarty_magazine'),
			'full_width'    => __('Full Width', 'smarty_magazine'),
		);

		return array_key_exists($input, $valid_keys) ? $input : '';
	}
}

if (!function_exists('__smarty_magazine_sanitize_integer')) {
	/**
	 * Sanitize integer input.
	 *
	 * @param mixed $input The input value.
	 * @return int The sanitized integer.
	 */
	function __smarty_magazine_sanitize_integer($input) {
		return absint($input);
	}
	add_action('customize_register', '__smarty_magazine_customize_register');
}

if (!function_exists('__smarty_magazine_customize_preview_js')) {
	/**
	 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
	 */
	function __smarty_magazine_customize_preview_js() {
		wp_enqueue_script(
			'__smarty_magazine_customizer',
			get_template_directory_uri() . '/assets/js/sm-customizer.js',
			array('customize-preview'),
			'20130508',
			true
		);
	}
	add_action('customize_preview_init', '__smarty_magazine_customize_preview_js');
}

if (!function_exists('__smarty_magazine_customize_js_settings')) {
	/**
	 * Enqueue styles for Theme Customizer controls.
	 */
	function __smarty_magazine_customize_js_settings() {
		wp_register_style(
			'sm-customizer-controls',
			get_template_directory_uri() . '/assets/css/sm-customizer.css'
		);
		wp_enqueue_style('sm-customizer-controls');
	}
	add_action('customize_controls_enqueue_scripts', '__smarty_magazine_customize_js_settings');
}
