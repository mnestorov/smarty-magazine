<?php
/**
 * Theme functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * @package SmartyMagazine
 */

if (!function_exists('__smarty_magazine_setup')) {
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     */
    function __smarty_magazine_setup() {
        // Make theme available for translation.
        load_theme_textdomain('smarty_magazine', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        // Custom Image Sizes
        add_image_size('sm-highlighted-post', 414, 279, true);
        add_image_size('sm-featured-image', 556, 380, true);
        add_image_size('sm-featured-post-large', 870, 600, true);
        add_image_size('sm-featured-post-medium', 410, 260, true);
        add_image_size('sm-featured-post-medium-small', 230, 150, true);
        add_image_size('sm-featured-post-small', 230, 184, true);

        // Let WordPress manage the document title.
        add_theme_support('title-tag');

        // Add Custom Logo Support.
        add_theme_support('custom-logo');

        // Enable support for Post Thumbnails on posts and pages.
        add_theme_support('post-thumbnails');

        // Register navigation menus.
        register_nav_menus(array(
            'primary'      => esc_html__('Primary Menu', 'smarty_magazine'),
            'top-bar-menu' => esc_html__('Top-bar Menu', 'smarty_magazine'),
        ));

        // Enable HTML5 support for various elements.
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        // Enable support for Post Formats.
        add_theme_support('post-formats', array(
            'aside',
            'image',
            'video',
            'quote',
            'link',
        ));

        // Set up the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('__smarty_magazine_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));

        // Support EasyMega menu.
        add_theme_support('megamenu-wp', array(
            'mobile_mod' => 992,
            'margin_top' => 1,
        ));

        // Add custom editor style.
        add_editor_style('assets/css/sm-custom-editor-style.css');
    }
	add_action('after_setup_theme', '__smarty_magazine_setup');
}

if (!function_exists('__smarty_magazine_content_width')) {
	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * @global int $content_width
	 */
	function __smarty_magazine_content_width() {
		$GLOBALS['content_width'] = apply_filters('__smarty_magazine_content_width', 640);
	}
	add_action('after_setup_theme', '__smarty_magazine_content_width', 0);
}

if (!function_exists('__smarty_magazine_scripts')) {
    /**
     * Enqueue scripts and styles.
     */
    function __smarty_magazine_scripts() {
        wp_enqueue_style('bootstrap-5', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', array(), '5.3.2');
        wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css', array(), '1.11.1');
        wp_enqueue_style('swiper', get_template_directory_uri() . '/assets/css/swiper.min.css', array(), '3.2.5');
        wp_enqueue_style('sm-roboto', '//fonts.googleapis.com/css?family=Roboto:400,300,500,700,900');
        wp_enqueue_style('sm-style', get_stylesheet_uri());
		
        wp_enqueue_script('swiper', get_template_directory_uri() . '/assets/js/swiper.jquery.min.js', array('jquery'), '3.2.5', true);
        wp_enqueue_script('newsticker', get_template_directory_uri() . '/assets/js/jquery.newsticker.min.js', array('jquery'), '', true);
        wp_enqueue_script('bootstrap-5', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.3.2', true);
        wp_enqueue_script('sm-custom', get_template_directory_uri() . '/assets/js/sm-custom.js', array('jquery'), '', true);

        // Comment reply script for threaded comments
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
    add_action('wp_enqueue_scripts', '__smarty_magazine_scripts');
}

if (!function_exists('__smarty_magazine_scripts')) {
	function __smarty_magazine_customize_preview_js() {
		wp_enqueue_script(
			'__smarty_magazine_customizer',
			get_template_directory_uri() . '/assets/js/sm-customizer.js',
			array('customize-preview'), // Ensure 'customize-preview' is a dependency
			'1.0',
			true
		);
	}
	add_action('customize_preview_init', '__smarty_magazine_customize_preview_js');
}

/**
 * Load additional theme functionality.
 */
require get_template_directory() . '/includes/functions/functions-sm-dashboard.php';
require get_template_directory() . '/includes/functions/functions-sm-plugins-recommended.php';
require get_template_directory() . '/includes/functions/functions-sm-custom-header.php';
require get_template_directory() . '/includes/functions/functions-sm-template-tags.php';
require get_template_directory() . '/includes/functions/functions-sm-customizer-styles.php';
require get_template_directory() . '/includes/functions/functions-sm-customizer.php';

/**
 * Load widget classes.
 */
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-tabs-content.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-posts-layout-1.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-posts-layout-2.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-posts-layout-3.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-posts-layout-4.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-social-icons.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-ads-728-90.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-ads-130-130.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-ads-870-150.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-ads-262-220.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-news-ticker.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-featured-post-slider.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-highlighted-news.php';

/**
 * Register widgets (move this to the bottom after all widget classes).
 */
require get_template_directory() . '/includes/functions/functions-sm-register-widgets.php';


if (!function_exists('easyblog_archive_excerpt_length')) {
	/**
	 * Filter the excerpt length.
	 *
	 * @param int $length Excerpt length.
	 * @return int Modified excerpt length.
	 */
	function easyblog_archive_excerpt_length($length) {
		return is_front_page() ? 50 : 40;
	}
	add_filter('excerpt_length', 'easyblog_archive_excerpt_length', 999);
}

if (!function_exists('easyblog_excerpt_more')) {
	/**
	 * Filter the excerpt "read more" string.
	 *
	 * @param string $more "Read more" excerpt string.
	 * @return string Modified "read more" excerpt string.
	 */
	function easyblog_excerpt_more($more) {
		return '...';
	}
	add_filter('excerpt_more', 'easyblog_excerpt_more');
}

if (!function_exists('__smarty_magazine_breadcrumb')) {
	/**
	 * Breadcrumb Navigation.
	 */
	function __smarty_magazine_breadcrumb() {
		global $post;
		echo '<ul id="sm_breadcrumbs">';

		if (!is_home()) {
			echo '<li><a href="' . esc_url(home_url()) . '">' . __('Home', 'smarty_magazine') . '</a></li><li class="separator"> / </li>';

			if (is_category() || is_single()) {
				echo '<li>';
				the_category(' </li><li class="separator"> / </li><li> ');

				if (is_single()) {
					echo '</li><li class="separator"> / </li><li>';
					the_title();
					echo '</li>';
				}
			} elseif (is_page()) {
				if ($post->post_parent) {
					$ancestors = get_post_ancestors($post->ID);
					foreach ($ancestors as $ancestor) {
						echo '<li><a href="' . esc_url(get_permalink($ancestor)) . '">' . esc_html(get_the_title($ancestor)) . '</a></li><li class="separator"> / </li>';
					}
					echo '<li>' . esc_html(get_the_title()) . '</li>';
				} else {
					echo '<li>' . esc_html(get_the_title()) . '</li>';
				}
			}
		} elseif (is_tag()) {
			single_tag_title();
		} elseif (is_day()) {
			echo "<li>" . __('Archive for', 'smarty_magazine') . ' ' . get_the_date() . '</li>';
		} elseif (is_month()) {
			echo "<li>" . __('Archive for', 'smarty_magazine') . ' ' . get_the_date('F, Y') . '</li>';
		} elseif (is_year()) {
			echo "<li>" . __('Archive for', 'smarty_magazine') . ' ' . get_the_date('Y') . '</li>';
		} elseif (is_author()) {
			echo "<li>" . __('Author Archive', 'smarty_magazine') . '</li>';
		} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
			echo "<li>" . __('Blog Archive', 'smarty_magazine') . '</li>';
		} elseif (is_search()) {
			echo "<li>" . __('Search Results', 'smarty_magazine') . '</li>';
		}

		echo '</ul>';
	}
}

if (!function_exists('__smarty_magazine_hex2rgba')) {
	/**
	 * Convert hex color string to rgba.
	 *
	 * @param string $color Hex color code.
	 * @param float  $opacity Opacity value.
	 * @return string RGB or RGBA color string.
	 */
	function __smarty_magazine_hex2rgba($color, $opacity = false) {
		$default = 'rgb(0,0,0)';

		if (empty($color)) {
			return $default;
		}

		if ($color[0] == '#') {
			$color = substr($color, 1);
		}

		if (strlen($color) == 6) {
			$hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
		} elseif (strlen($color) == 3) {
			$hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
		} else {
			return $default;
		}

		$rgb = array_map('hexdec', $hex);

		if ($opacity !== false) {
			$opacity = min(1, abs($opacity));
			return 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
		} else {
			return 'rgb(' . implode(",", $rgb) . ')';
		}
	}

	/**
	 * Get the first image from the post content.
	 *
	 * @param int $num Image position in the content.
	 */
	function __smarty_magazine_post_img($num) {
		global $more;
		$more = 1;
		$content = get_the_content();
		preg_match_all('/<img[^>]+>/i', $content, $images);

		if (!empty($images[0]) && isset($images[0][$num - 1])) {
			echo '<a href="' . esc_url(get_permalink()) . '">' . $images[0][$num - 1] . '</a>';
		} else {
			echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/no-image.png') . '" alt="No Image"/>';
		}

		$more = 0;
	}
}