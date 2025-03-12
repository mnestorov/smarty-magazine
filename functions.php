<?php
/**
 * Theme functions and definitions.
 * 
 * @since 1.0.0
 * 
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * 
 * @package SmartyMagazine
 */

if (!function_exists('__smarty_magazine_setup')) {
    /**
     * Sets up theme defaults and registers support for various WordPress features.
	 * 
	 * @since 1.0.0
	 * 
	 * @return void
	 * 
	 * @link https://developer.wordpress.org/reference/hooks/after_setup_theme/
     */
    function __smarty_magazine_setup() {
        // Make theme available for translation.
        load_theme_textdomain('smarty_magazine', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        // Custom Image Sizes
        add_image_size('sm-highlighted-post', 414, 279, true);
        add_image_size('sm-featured-image', 556, 380, true);
        add_image_size('sm-featured-post-extra-large', 1792, 1024, true);
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
            'top-bar-menu' => esc_html__('Top Bar Menu', 'smarty_magazine'),
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

        // Add WooCommerce support.
        add_theme_support('woocommerce');

        // Add custom editor style.
        add_editor_style('assets/css/sm-custom-editor.css'); // This is the path to the editor-style.css file.
    }
	add_action('after_setup_theme', '__smarty_magazine_setup');
}

if (!function_exists('__smarty_magazine_enable_lazy_loading_images')) {
    /**
     * Enable lazy loading for images in content.
     * 
     * @since 1.0.0
     *
     * @param string $content The post content.
     * 
     * @return string Modified post content.
     */
    function __smarty_magazine_enable_lazy_loading_images($content) {
        if (is_admin()) return $content;

        // Add loading="lazy" to all images in content
        $content = preg_replace('/<img(?![^>]+loading=)/', '<img loading="lazy"', $content);

        return $content;
    }
    add_filter('the_content', '__smarty_magazine_enable_lazy_loading_images', 99);
}

if (!function_exists('__smarty_magazine_content_width')) {
	/**
	 * Set the content width in pixels, based on the theme's design and stylesheet.
	 * 
	 * @since 1.0.0
	 *
	 * @global int $content_width
	 * 
	 * @return void
	 */
	function __smarty_magazine_content_width() {
		$GLOBALS['content_width'] = apply_filters('__smarty_magazine_content_width', 640);
	}
	add_action('after_setup_theme', '__smarty_magazine_content_width', 0);
}

if (!function_exists('__smarty_magazine_enqueue_admin_scripts')) {
	/**
	 * Enqueue media uploader scripts.
	 * 
	 * @since 1.0.0
	 *
	 * @param string $hook The current admin page hook.
	 * 
	 * @return void
	 * 
	 * @link https://developer.wordpress.org/reference/hooks/admin_enqueue_scripts/
	 */
	function __smarty_magazine_enqueue_admin_scripts($hook) {
        wp_enqueue_script('jquery');
		wp_enqueue_style('sm-admin-css', get_template_directory_uri() . '/assets/css/sm-admin.css', array(), null, 'all');
        wp_enqueue_script('sm-admin-js', get_template_directory_uri() . '/assets/js/sm-admin.js', array('jquery'), null, true);
		
		if ('widgets.php' === $hook || 'customize.php' === $hook) {
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script('wp-color-picker');
			wp_enqueue_media();
            wp_localize_script('sm-admin-js', 'sm_ajax_data', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('sm_ajax_nonce'),
            ));
        }
	}
	add_action('admin_enqueue_scripts', '__smarty_magazine_enqueue_admin_scripts');
}

if (!function_exists('__smarty_magazine_customizer_preview')) {
	/**
	 * Enqueue customizer scripts and styles.
	 * 
	 * @since 1.0.0
	 *
	 * @return void
	 * 
	 * @link https://developer.wordpress.org/reference/hooks/customize_preview_init/
	 */
	function __smarty_magazine_customizer_preview() {
		wp_enqueue_style('sm-customizer-css', get_template_directory_uri() . '/assets/css/sm-customizer.css');
		wp_enqueue_script('sm-customizer-js', get_template_directory_uri() . '/assets/js/sm-customizer.js', array('customize-preview'), null, true);
	}
	add_action('customize_preview_init', '__smarty_magazine_customizer_preview');
}

if (!function_exists('__smarty_magazine_preload_front_styles')) {
    /**
     * Preload front-end styles for faster loading.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    function __smarty_magazine_preload_front_styles() {
        // Define an array of styles to preload
        $preload_styles = array(
            'bootstrap'       => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css',
            'bootstrap-icons' => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css',
            'swiper'          => 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.css',
        );

        // Loop through and print the preload links
        foreach ($preload_styles as $key => $url) {
            echo '<link rel="preload" as="style" href="' . esc_url($url) . '" onload="this.rel=\'stylesheet\';">' . "\n";
        }
    }
    add_action('wp_head', '__smarty_magazine_preload_front_styles', 5);
}

if (!function_exists('__smarty_magazine_enqueue_front_scripts')) {
    /**
     * Enqueue scripts and styles.
	 * 
	 * @since 1.0.0
	 * 
	 * @return void
	 * 
	 * @link https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
     */
    function __smarty_magazine_enqueue_front_scripts() {
        wp_enqueue_style('sm-style', get_stylesheet_uri(), array(), null, 'all');
    
        // Preload Fonts (using wp_enqueue_style for better management)
        wp_enqueue_style('google-fonts-preconnect', 'https://fonts.googleapis.com', array(), null, 'all');
        wp_enqueue_style('google-fonts-preconnect-crossorigin', 'https://fonts.gstatic.com', array(), null, 'all');
        wp_enqueue_style(
            'google-fonts-roboto',
            'https://fonts.googleapis.com/css?family=Roboto:400,300,500,700,900&display=swap',
            array(),
            null,
            'print' // Loads as print, switches to all onload
        );
        wp_add_inline_script(
            'google-fonts-roboto',
            'document.getElementById("google-fonts-roboto-css").media="all";'
        );
    
        // Load Bootstrap CSS asynchronously
        wp_enqueue_style('bootstrap-icons', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css', array(), '1.11.3', 'all');
        wp_enqueue_style('bootstrap-5', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css', array(), '5.3.3', 'all');
        wp_enqueue_script('bootstrap-5', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js', array('jquery'), '5.3.3', true);

        // Load jQuery
        wp_enqueue_script('jquery');

        // Load Swiper (Only defer JS, keep CSS async)
        wp_enqueue_style('swiper-css', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.css', array(), '11.0.5', 'all');
        wp_enqueue_script('swiper-js', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.js', array('jquery'), '11.0.5', true);
        
        // Load News Ticker
        wp_enqueue_script('news-ticker', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-advanced-news-ticker/1.0.1/js/newsTicker.min.js', array('jquery'), '1.0.1', true);
        
        // Load Local Styles
        wp_enqueue_style('sm-front-css', get_template_directory_uri() . '/assets/css/sm-front.css', array(), null, 'all');
        wp_enqueue_script('sm-front-js', get_template_directory_uri() . '/assets/js/sm-front.js', array('jquery'), null, true);
    
        // Comment reply script for threaded comments
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }

        // Add nonce to sm-front-js (or any script needing AJAX)
        wp_localize_script(
            'sm-front-js',      // Attach to an enqueued script
            'smartyMagazine',   // Object name in JS
            array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('smarty_magazine_nonce'), // Nonce for front-end AJAX
            )
        );
    }
    add_action('wp_enqueue_scripts', '__smarty_magazine_enqueue_front_scripts');    
}

if (!function_exists('__smarty_magazine_defer_scripts')) {
    /**
     * Defer non-critical scripts.
     * 
     * @since 1.0.0
     * 
     * @param string $tag    The script tag.
     * 
     * @return string Modified script tag.
     * 
     * @link https://developer.wordpress.org/reference/hooks/script_loader_tag/
     */
    function __smarty_magazine_defer_scripts($tag, $handle, $src) {
        // List of scripts to defer
        $scripts_to_defer = array(
            'bootstrap-5',
            'swiper-js',
            'news-ticker',
            'sm-front-js'
        );

        if (in_array($handle, $scripts_to_defer)) {
            return '<script src="' . esc_url($src) . '" defer></script>';
        }

        return $tag;
    }
    add_filter('script_loader_tag', '__smarty_magazine_defer_scripts', 10, 3);
}

if (!function_exists('__smarty_magazine_handle_ajax')) {
    /**
     * Handle AJAX requests.
     * 
     * @since 1.0.0
     * 
     * @return void
     * 
     * @link https://developer.wordpress.org/reference/hooks/wp_ajax_action/
     */
    function __smarty_magazine_handle_ajax() {
        check_ajax_referer('smarty_magazine_nonce', 'nonce');
        // Your AJAX logic here
        wp_send_json_success('Success!');
    }
    add_action('wp_ajax_some_action', '__smarty_magazine_handle_ajax');
    add_action('wp_ajax_nopriv_some_action', '__smarty_magazine_handle_ajax');
}

/**
 * Load additional theme functionality.
 * 
 * @since 1.0.0
 * 
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 */
require get_template_directory() . '/includes/functions/functions-sm-dashboard.php';
require get_template_directory() . '/includes/functions/functions-sm-plugins-recommended.php';
require get_template_directory() . '/includes/functions/functions-sm-custom-header.php';
require get_template_directory() . '/includes/functions/functions-sm-template-helpers.php';
require get_template_directory() . '/includes/functions/functions-sm-template-tags.php';
require get_template_directory() . '/includes/functions/functions-sm-customizer-styles.php';
require get_template_directory() . '/includes/functions/functions-sm-customizer.php';
require get_template_directory() . '/includes/functions/functions-sm-meta-schema.php';

/**
 * Load widget classes.
 * 
 * @since 1.0.0
 * 
 * @link https://developer.wordpress.org/themes/functionality/widgets/
 */
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-tabs-content.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-posts-layout-1.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-posts-layout-2.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-posts-layout-3.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-posts-layout-4.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-social-icons.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-shortcodes.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-ads-728-90.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-ads-130-130.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-ads-870-150.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-ads-300-250.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-news-ticker.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-featured-post-slider.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-highlighted-news.php';
require get_template_directory() . '/includes/classes/widgets/class-sm-widget-news-categories.php';

/**
 * Load additional theme functionality.
 * 
 * @since 1.0.0
 * 
 * 
 * @link https://developer.wordpress.org/themes/functionality/custom-post-types/
 * @link https://developer.wordpress.org/themes/functionality/widgets/
 */
require get_template_directory() . '/includes/functions/functions-sm-register-cpt.php';
require get_template_directory() . '/includes/functions/functions-sm-register-widgets.php';

if (!function_exists('__smarty_magazine_tinymce_init')) {
    /**
     * Customize TinyMCE settings.
     * 
     * @since 1.0.0
     * 
     * @param array $init TinyMCE settings.
     * 
     * @return array Modified TinyMCE settings.
     */
    function __smarty_magazine_tinymce_init($init) {
        // Force TinyMCE to keep paragraph tags
        $init['wpautop'] = true;
        $init['forced_root_block'] = 'p';
        $init['force_p_newlines'] = true;
        $init['remove_redundant_brs'] = false; // Prevent TinyMCE from removing <br> tags

        return $init;
    }
    add_filter('tiny_mce_before_init', '__smarty_magazine_tinymce_init');
}

if (!function_exists('__smarty_archive_excerpt_length')) {
	/**
	 * Filter the excerpt length.
	 * 
	 * @since 1.0.0
	 * 
	 * @param int $length Excerpt length.
	 * 
	 * @return int Modified excerpt length.
	 */
	function __smarty_archive_excerpt_length($length) {
		return is_front_page() ? 50 : 40;
	}
	add_filter('excerpt_length', '__smarty_archive_excerpt_length', 999);
}

if (!function_exists('__smarty_excerpt_more')) {
	/**
	 * Filter the excerpt "read more" string.
	 * 
	 * @since 1.0.0
	 *
	 * @param string $more "Read more" excerpt string.
	 * 
	 * @return string Modified "read more" excerpt string.
	 */
	function __smarty_excerpt_more($more) {
		return '...';
	}
	add_filter('excerpt_more', '__smarty_excerpt_more');
}

if (!function_exists('__smarty_magazine_breadcrumb')) {
    /**
     * Breadcrumb Navigation with Bootstrap 5 styling, supporting all post types including 'news'.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    function __smarty_magazine_breadcrumb() {
        global $post;

        // Don't show breadcrumbs on home or front page
        if (is_home() && is_front_page()) {
            return;
        }

        echo '<nav aria-label="' . esc_attr__('Breadcrumb', 'smarty_magazine') . '">';
        echo '<ol class="breadcrumb">';

        // Home link
        echo '<li class="breadcrumb-item"><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'smarty_magazine') . '</a></li>';

        if (is_category()) {
            // Get the current category
            $category = get_queried_object();
        
            // Get parent categories if they exist
            $parents = get_ancestors($category->term_id, 'category');
            $parents = array_reverse($parents); // Reverse to display hierarchy in correct order
        
            foreach ($parents as $parent_id) {
                $parent = get_term($parent_id, 'category');
                echo '<li class="breadcrumb-item"><a href="' . esc_url(get_category_link($parent->term_id)) . '">' . esc_html($parent->name) . '</a></li>';
            }
        
            // Display current category
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html($category->name) . '</li>';
        } elseif (is_tag()) {
            // Detect if it's a tag for "news"
            $queried_tag = get_queried_object();
            if ($queried_tag->taxonomy === 'news_tag') {
                echo '<li class="breadcrumb-item"><a href="' . esc_url(get_post_type_archive_link('news')) . '">' . esc_html__('News', 'smarty_magazine') . '</a></li>';
            }
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(single_tag_title('', false)) . '</li>';
        } elseif (is_tax('news_category')) {
            // News category archive
            echo '<li class="breadcrumb-item"><a href="' . esc_url(get_post_type_archive_link('news')) . '">' . esc_html__('News', 'smarty_magazine') . '</a></li>';
            $term = get_queried_object();
            if ($term->parent != 0) {
                $parent_term = get_term($term->parent, 'news_category');
                echo '<li class="breadcrumb-item"><a href="' . esc_url(get_term_link($parent_term)) . '">' . esc_html($parent_term->name) . '</a></li>';
            }
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html($term->name) . '</li>';
        } elseif (is_tax('news_tag')) {
            // News tag archive
            echo '<li class="breadcrumb-item"><a href="' . esc_url(get_post_type_archive_link('news')) . '">' . esc_html__('News', 'smarty_magazine') . '</a></li>';
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(single_tag_title('', false)) . '</li>';
        } elseif (is_post_type_archive('news')) {
            // News archive
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html__('News', 'smarty_magazine') . '</li>';
        } elseif (is_singular('news')) {
            // Single news post
            echo '<li class="breadcrumb-item"><a href="' . esc_url(get_post_type_archive_link('news')) . '">' . esc_html__('News', 'smarty_magazine') . '</a></li>';
            $categories = get_the_terms($post->ID, 'news_category');
            if ($categories && !is_wp_error($categories)) {
                $primary_cat = $categories[0];
                echo '<li class="breadcrumb-item"><a href="' . esc_url(get_term_link($primary_cat)) . '">' . esc_html($primary_cat->name) . '</a></li>';
            }
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title()) . '</li>';
        } elseif (is_single() && 'post' === get_post_type()) {
            // Single standard post
            if (has_category()) {
                $categories = get_the_category();
                if (!empty($categories)) {
                    // Find the primary category (Yoast SEO method)
                    $primary_cat = get_post_meta($post->ID, '_yoast_wpseo_primary_category', true);
                    
                    // If Yoast is not set, use the first category
                    $category = $primary_cat ? get_term($primary_cat, 'category') : $categories[0];
            
                    // Get parent categories if they exist
                    $parents = get_ancestors($category->term_id, 'category');
                    $parents = array_reverse($parents);
                    
                    foreach ($parents as $parent_id) {
                        $parent = get_term($parent_id, 'category');
                        echo '<li class="breadcrumb-item"><a href="' . esc_url(get_category_link($parent->term_id)) . '">' . esc_html($parent->name) . '</a></li>';
                    }
            
                    // Print the current category
                    echo '<li class="breadcrumb-item"><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></li>';
                }
            }
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title()) . '</li>';
        } elseif (is_page()) {
            // Pages with hierarchy
            if ($post->post_parent) {
                $ancestors = array_reverse(get_post_ancestors($post->ID));
                foreach ($ancestors as $ancestor) {
                    echo '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink($ancestor)) . '">' . esc_html(get_the_title($ancestor)) . '</a></li>';
                }
            }
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(get_the_title()) . '</li>';
        } elseif (is_author()) {
            // Author archive
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html__('Author Archive', 'smarty_magazine') . '</li>';
        } elseif (is_search()) {
            // Search results
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html__('Search Results', 'smarty_magazine') . '</li>';
        } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
            // Paged blog archive
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html__('Blog Archive', 'smarty_magazine') . '</li>';
        }

        echo '</ol>';
        echo '</nav>';
    }
}

if (!function_exists('__smarty_magazine_include_news_in_author_archive')) {
    /**
     * Include 'news' post type in author archive.
     * 
     * @since 1.0.0
     * 
     * @param WP_Query $query The main query.
     * 
     * @return void
     */
    function __smarty_magazine_include_news_in_author_archive($query) {
        if (!is_admin() && $query->is_author() && $query->is_main_query()) {
            $query->set('post_type', array('post', 'news')); // Include both posts and news
        }
    }
    add_action('pre_get_posts', '__smarty_magazine_include_news_in_author_archive');
}

if (!function_exists('__smarty_magazine_news_post_navigation')) {
	/**
	 * Display post navigation for 'news' post type.
	 * 
	 * @since 1.0.0
	 * 
	 * @global WP_Post $post
	 * 
	 * @return void
	 */
    function __smarty_magazine_news_post_navigation() {
        global $post;
        $terms = get_the_terms($post->ID, 'news_category');
        if (!$terms || is_wp_error($terms)) {
            return; // No categories assigned, no navigation
        }
        $term_ids = wp_list_pluck($terms, 'term_id');

        $args = array(
            'post_type' => 'news',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => 'news_category',
                    'field' => 'term_id',
                    'terms' => $term_ids,
                ),
            ),
            'orderby' => 'date',
            'order' => 'DESC',
        );
        $query = new WP_Query($args);

        if ($query->have_posts()) {
            $post_ids = wp_list_pluck($query->posts, 'ID');
            $current_index = array_search($post->ID, $post_ids);
            
            $prev_id = ($current_index > 0) ? $post_ids[$current_index - 1] : null;
            $next_id = ($current_index < count($post_ids) - 1) ? $post_ids[$current_index + 1] : null;

            if ($prev_id || $next_id) {
                echo '<nav class="navigation post-navigation" role="navigation">';
                echo '<div class="nav-links">';
                
                if ($prev_id) {
                    echo '<div class="nav-previous">';
                    echo '<a href="' . esc_url(get_permalink($prev_id)) . '"></span> <span class="nav-title">' . esc_html(get_the_title($prev_id)) . '</span></a>';
                    echo '</div>';
                }
                if ($next_id) {
                    echo '<div class="nav-next">';
                    echo '<a href="' . esc_url(get_permalink($next_id)) . '"><span class="nav-title">' . esc_html(get_the_title($next_id)) . '</span></a>';
                    echo '</div>';
                }
                
                echo '</div>';
                echo '</nav>';
            }
        }
        wp_reset_postdata();
    }
}

if (!function_exists('__smarty_magazine_hex2rgba')) {
	/**
	 * Convert hex color string to rgba.
	 * 
	 * @since 1.0.0
	 *
	 * @param string $color Hex color code.
	 * @param float  $opacity Opacity value.
	 * 
	 * @return string RGB or RGBA color string.
	 */
	function __smarty_magazine_hex2rgba($color, $opacity = false) {
		$default = 'rgb( 0, 0, 0)';

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
}

if (!function_exists('__smarty_magazine_post_img')) {
	/**
	 * Get the first image from the post content.
	 * 
	 * @since 1.0.0
	 *
	 * @param int $num Image position in the content.
	 * 
	 * @return void
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

if (!function_exists('__smarty_magazine_save_custom_slug')) {
    /**
     * Save custom slug with slashes for a page
     *
     * @since 1.0.0
     * 
     * @param int $post_id The post ID
     * 
     * @return void
     */
    function __smarty_magazine_save_custom_slug($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (get_post_type($post_id) !== 'page') return;
        if (!current_user_can('edit_page', $post_id)) return;

        if (isset($_POST['smarty_magazine_custom_slug']) && wp_verify_nonce($_POST['smarty_magazine_custom_slug_nonce'], 'smarty_magazine_custom_slug_nonce')) {
            $custom_slug = sanitize_text_field($_POST['smarty_magazine_custom_slug']); // Keep slashes
            update_post_meta($post_id, '_sm_custom_slug', $custom_slug);
        }
    }
    add_action('save_post', '__smarty_magazine_save_custom_slug');
}

if (!function_exists('__smarty_magazine_add_custom_slug_metabox')) {
    /**
     * Add metabox for custom slug input
     *
     * @since 1.0.0
     * 
     * @return void
     */
    function __smarty_magazine_add_custom_slug_metabox() {
        add_meta_box(
            'smarty_magazine_custom_slug_metabox',
            __('Custom Slug', 'smarty_magazine'),
            '__smarty_magazine_render_custom_slug_metabox',
            'page',
            'side',
            'high'
        );
    }
    add_action('add_meta_boxes', '__smarty_magazine_add_custom_slug_metabox');
}

if (!function_exists('__smarty_magazine_render_custom_slug_metabox')) {
    /**
     * Render the custom slug metabox
     *
     * @since 1.0.0
     * 
     * @param WP_Post $post The post object
     * 
     * @return void
     */
    function __smarty_magazine_render_custom_slug_metabox($post) {
        $custom_slug = get_post_meta($post->ID, '_sm_custom_slug', true);
        wp_nonce_field('smarty_magazine_custom_slug_nonce', 'smarty_magazine_custom_slug_nonce');
        ?>
        <label for="smarty_magazine_custom_slug"><?php _e('Enter custom slug (e.g., text/another-text):', 'smarty_magazine'); ?></label>
        <input type="text" name="smarty_magazine_custom_slug" id="smarty_magazine_custom_slug" value="<?php echo esc_attr($custom_slug); ?>" class="widefat" />
        <p><?php _e('This will override the default slug with slashes.', 'smarty_magazine'); ?></p>
        <?php
    }
}

if (!function_exists('__smarty_magazine_custom_page_permalink')) {
    /**
     * Filter the page permalink to use custom slug with slashes
     *
     * @since 1.0.0
     * 
     * @param string $link The default permalink
     * @param int $post_id The post ID
     * 
     * @return string The modified permalink
     */
    function __smarty_magazine_custom_page_permalink($link, $post_id) {
        $post = get_post($post_id);
        if ($post->post_type === 'page') {
            $custom_slug = get_post_meta($post_id, '_sm_custom_slug', true);
            if ($custom_slug) {
                $link = home_url("/$custom_slug");
            }
        }
        return $link;
    }
    add_filter('page_link', '__smarty_magazine_custom_page_permalink', 10, 2);
}

if (!function_exists('__smarty_magazine_custom_page_link')) {
    /**
     * Filter the page link to reflect custom slug in admin
     *
     * @since 1.0.0
     * 
     * @param string $link The default link
     * @param WP_Post $post The post object
     * 
     * @return string The modified link
     */
    function __smarty_magazine_custom_page_link($link, $post) {
        if ($post->post_type === 'page') {
            $custom_slug = get_post_meta($post->ID, '_sm_custom_slug', true);
            if ($custom_slug) {
                $link = home_url("/$custom_slug");
            }
        }
        return $link;
    }
    add_filter('post_type_link', '__smarty_magazine_custom_page_link', 10, 2);
}

if (!function_exists('__smarty_magazine_add_full_width_tabs_metabox')) {
    /**
     * Add metabox for tab titles and content
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    function __smarty_magazine_add_full_width_tabs_metabox() {
        add_meta_box(
            'smarty_magazine_full_width_tabs_metabox',             // Metabox ID
            __('Full Width Tab Content', 'smarty_magazine'), // Title
            '__smarty_magazine_full_width_tabs_metabox_callback',  // Callback function
            'page',                                          // Post type
            'normal',                                        // Context (below editor)
            'high',                                          // Priority
            null                                             // Callback args
        );
    }
    add_action('add_meta_boxes', '__smarty_magazine_add_full_width_tabs_metabox');
}

if (!function_exists('__smarty_magazine_full_width_tabs_metabox_callback')) {
    /**
     * Metabox callback to render fields
     * 
     * @since 1.0.0
     * 
     * @param WP_Post $post The post object
     * 
     * @return void
     */
    function __smarty_magazine_full_width_tabs_metabox_callback($post) {
        // Nonce field for security
        wp_nonce_field('smarty_magazine_full_width_tabs_save_meta', 'smarty_magazine_full_width_tabs_nonce');

        // Get existing meta values
        $tabs = [
            'smarty_magazine_tab1' => [
                'title'   => get_post_meta($post->ID, '_smarty_magazine_tab1_title', true), 
                'content' => get_post_meta($post->ID, '_smarty_magazine_tab1_content', true),
                'enabled' => get_post_meta($post->ID, '_smarty_magazine_tab1_enabled', true) === '1'
            ],

            'smarty_magazine_tab2' => [
                'title'   => get_post_meta($post->ID, '_smarty_magazine_tab2_title', true), 
                'content' => get_post_meta($post->ID, '_smarty_magazine_tab2_content', true),
                'enabled' => get_post_meta($post->ID, '_smarty_magazine_tab2_enabled', true) === '1'
            ],

            'smarty_magazine_tab3' => [
                'title'   => get_post_meta($post->ID, '_smarty_magazine_tab3_title', true), 
                'content' => get_post_meta($post->ID, '_smarty_magazine_tab3_content', true),
                'enabled' => get_post_meta($post->ID, '_smarty_magazine_tab3_enabled', true) === '1'
            ]
        ];


        // Only show metabox for the specific template
        $template = get_post_meta($post->ID, '_wp_page_template', true);
        if ($template !== 'template-full-width-nav.php') {
            echo '<p>' . esc_html__('This metabox is only available for the "Full Width Nav" page template.', 'smarty_magazine') . '</p>';
            return;
        }
        ?>

        <div class="sm-magazine-pills-metabox">
            <?php foreach ($tabs as $tab => $data) : ?>
                <div class="sm-magazine-tab-section">
                    <div class="sm-magazine-tab-header">
                        <h4><?php echo esc_html(ucwords(str_replace('smarty_magazine_tab', 'Tab ', $tab))); ?></h4>
                        <p class="mb-0">
                            <label class="smarty-toggle-switch" aria-label="<?php echo esc_attr__('Enable/Disable ' . ucwords(str_replace('smarty_magazine_tab', 'Tab ', $tab))); ?>">
                                <input type="checkbox" name="<?php echo esc_attr($tab . '_enabled'); ?>" value="1" <?php checked($data['enabled'], true); ?> aria-checked="<?php echo $data['enabled'] ? 'true' : 'false'; ?>">
                                <span class="smarty-slider round"></span>
                            </label>
                        </p>
                    </div>
                    <p>
                        <label for="<?php echo esc_attr($tab . '_title'); ?>"><?php esc_html_e('Title:', 'smarty_magazine'); ?></label>
                        <input type="text" id="<?php echo esc_attr($tab . '_title'); ?>" name="<?php echo esc_attr($tab . '_title'); ?>" value="<?php echo esc_attr($data['title']); ?>">
                    </p>
                    <p>
                        <label for="<?php echo esc_attr($tab . '_content'); ?>"><?php esc_html_e('Content:', 'smarty_magazine'); ?></label>
                        <?php
                        wp_editor(
                            $data['content'],
                            $tab . '_content',
                            array(
                                'textarea_name' => $tab . '_content',
                                'textarea_rows' => 5,
                                'media_buttons' => true,
                            )
                        );
                        ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}

if (!function_exists('__smarty_magazine_save_full_width_tabs_meta')) {
    /**
     * Save metabox data
     * 
     * @since 1.0.0
     * 
     * @param int $post_id The post ID
     * 
     * @return void
     */
    function __smarty_magazine_save_full_width_tabs_meta($post_id) {
        // Check if our nonce is set and verify it
        if (!isset($_POST['smarty_magazine_full_width_tabs_nonce']) || !wp_verify_nonce($_POST['smarty_magazine_full_width_tabs_nonce'], 'smarty_magazine_full_width_tabs_save_meta')) {
            return;
        }

        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check user permissions
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }

        // Only save for the specific template
        $template = get_post_meta($post_id, '_wp_page_template', true);
        if ($template !== 'template-full-width-nav.php') {
            return;
        }

        // Save meta fields
        $fields = [
            'smarty_magazine_tab1_title',
            'smarty_magazine_tab1_content',
            'smarty_magazine_tab1_enabled',
            'smarty_magazine_tab2_title',
            'smarty_magazine_tab2_content',
            'smarty_magazine_tab2_enabled',
            'smarty_magazine_tab3_title',
            'smarty_magazine_tab3_content',
            'smarty_magazine_tab3_enabled',
        ];

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = $field === 'smarty_magazine_tab1_title' || $field === 'smarty_magazine_tab2_title' || $field === 'smarty_magazine_tab3_title' 
                    ? sanitize_text_field($_POST[$field]) 
                    : ($field === 'smarty_magazine_tab1_enabled' || $field === 'smarty_magazine_tab2_enabled' || $field === 'smarty_magazine_tab3_enabled' 
                        ? (isset($_POST[$field]) && $_POST[$field] === '1' ? '1' : '0') // Explicitly check for '1' and default to '0'
                        : wp_kses_post($_POST[$field]));
                update_post_meta($post_id, "_$field", $value); // Ensure meta key starts with underscore
                error_log("✅ Saved $field: " . print_r($value, true));
            } else {
                // Default to disabled (0) for _enabled fields if not set (unchecked checkbox)
                if (strpos($field, '_enabled') !== false) {
                    update_post_meta($post_id, "_$field", '0'); // Default to disabled (0) for unchecked checkboxes
                    error_log("⚠️ Defaulted $field to disabled (0).");
                } else {
                    delete_post_meta($post_id, "_$field"); // Ensure meta key starts with underscore
                    error_log("⚠️ Deleted $field (not set).");
                }
            }
        }
    }
    add_action('save_post', '__smarty_magazine_save_full_width_tabs_meta');
}

if (!function_exists('__smarty_magazine_add_two_column_tabs_metabox')) {
    /**
     * Add metabox for two-column tabs titles and content
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    function __smarty_magazine_add_two_column_tabs_metabox() {
        add_meta_box(
            'smarty_magazine_two_column_tabs_metabox',            // Metabox ID
            __('Two Column Tabs Content', 'smarty_magazine'),     // Title
            '__smarty_magazine_two_column_tabs_metabox_callback', // Callback function
            'page',                                               // Post type
            'normal',                                             // Context (below editor)
            'high',                                               // Priority
            null                                                  // Callback args
        );
    }
    add_action('add_meta_boxes', '__smarty_magazine_add_two_column_tabs_metabox');
}

if (!function_exists('__smarty_magazine_two_column_tabs_metabox_callback')) {
    /**
     * Metabox callback to render fields for two-column tabs
     * 
     * @since 1.0.0
     * 
     * @param WP_Post $post The post object
     * 
     * @return void
     */
    function __smarty_magazine_two_column_tabs_metabox_callback($post) {
        // Nonce field for security
        wp_nonce_field('smarty_magazine_two_column_tabs_save_meta', 'smarty_magazine_two_column_tabs_nonce');

        // Get existing meta values
        $tabs = [
            'smarty_magazine_nav_tab1' => [
                'title'    => get_post_meta($post->ID, '_smarty_magazine_nav_tab1_title', true),
                'content'  => get_post_meta($post->ID, '_smarty_magazine_nav_tab1_content', true),
                'enabled'  => get_post_meta($post->ID, '_smarty_magazine_nav_tab1_enabled', true) === '1'
            ],
            'smarty_magazine_nav_tab2' => [
                'title'    => get_post_meta($post->ID, '_smarty_magazine_nav_tab2_title', true),
                'content'  => get_post_meta($post->ID, '_smarty_magazine_nav_tab2_content', true),
                'enabled'  => get_post_meta($post->ID, '_smarty_magazine_nav_tab2_enabled', true) === '1'
            ],
        ];

        // Only show metabox for the specific template (assuming it's 'two-column.php' or similar)
        $template = get_post_meta($post->ID, '_wp_page_template', true);
        if ($template !== 'template-full-width-nav.php') { // Adjust template name as needed
            echo '<p>' . esc_html__('This metabox is only available for the "Full Width Nav" page template.', 'smarty_magazine') . '</p>';
            return;
        }
        ?>

        <div class="sm-magazine-pills-metabox">
            <?php foreach ($tabs as $tab => $data) : ?>
                <div class="sm-magazine-tab-section">
                    <div class="sm-magazine-tab-header">
                        <h4><?php echo esc_html(ucwords(str_replace('smarty_magazine_nav_tab', 'Tab ', $tab))); ?></h4>
                        <p class="mb-0">
                            <label class="smarty-toggle-switch" aria-label="<?php echo esc_attr__('Enable/Disable ' . ucwords(str_replace('smarty_magazine_tab', 'Tab ', $tab))); ?>">
                                <input type="checkbox" name="<?php echo esc_attr($tab . '_enabled'); ?>" value="1" <?php checked($data['enabled'], true); ?> aria-checked="<?php echo $data['enabled'] ? 'true' : 'false'; ?>">
                                <span class="smarty-slider round"></span>
                            </label>
                        </p>
                    </div>
                    <p>
                        <label for="<?php echo esc_attr($tab . '_title'); ?>"><?php esc_html_e('Title:', 'smarty_magazine'); ?></label>
                        <input type="text" id="<?php echo esc_attr($tab . '_title'); ?>" name="<?php echo esc_attr($tab . '_title'); ?>" value="<?php echo esc_attr($data['title']); ?>">
                    </p>
                    <p>
                        <label for="<?php echo esc_attr($tab . '_content'); ?>"><?php esc_html_e('Content:', 'smarty_magazine'); ?></label>
                        <?php
                        wp_editor(
                            $data['content'],
                            $tab . '_content',
                            array(
                                'textarea_name' => $tab . '_content',
                                'textarea_rows' => 5,
                                'media_buttons' => true,
                            )
                        );
                        ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        // Debug: Log if the toggle switch CSS is enqueued
        if (wp_style_is('smarty-toggle-switch', 'enqueued')) {
            error_log("Smarty toggle switch CSS enqueued successfully for two-column tabs.");
        } else {
            error_log("Smarty toggle switch CSS not enqueued for two-column tabs.");
        }
    }
}

if (!function_exists('__smarty_magazine_save_two_column_tabs_meta')) {
    /**
     * Save metabox data for two-column tabs
     * 
     * @since 1.0.0
     * 
     * @param int $post_id The post ID
     * 
     * @return void
     */
    function __smarty_magazine_save_two_column_tabs_meta($post_id) {
        // Verify nonce
        if (!isset($_POST['smarty_magazine_two_column_tabs_nonce']) || 
            !wp_verify_nonce($_POST['smarty_magazine_two_column_tabs_nonce'], 'smarty_magazine_two_column_tabs_save_meta')) {
            return;
        }

        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check user permissions
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }

        // Only save for the specific template
        $template = get_post_meta($post_id, '_wp_page_template', true);
        if ($template !== 'template-full-width-nav.php') { // Adjust template name as needed
            return;
        }

        // Save meta fields
        $fields = [
            'smarty_magazine_nav_tab1_title',
            'smarty_magazine_nav_tab1_content',
            'smarty_magazine_nav_tab1_enabled',
            'smarty_magazine_nav_tab2_title',
            'smarty_magazine_nav_tab2_content',
            'smarty_magazine_nav_tab2_enabled',
        ];

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = in_array($field, ['smarty_magazine_nav_tab1_title', 'smarty_magazine_nav_tab2_title'])
                    ? sanitize_text_field($_POST[$field]) 
                    : (in_array($field, ['smarty_magazine_nav_tab1_enabled', 'smarty_magazine_nav_tab2_enabled'])
                        ? (isset($_POST[$field]) && $_POST[$field] === '1' ? '1' : '0') // Explicitly check for '1' and default to '0'
                        : wp_kses_post($_POST[$field]));
                update_post_meta($post_id, "_$field", $value); // Ensure meta key starts with underscore
                error_log("Saved $field: " . print_r($value, true));
            } else {
                // Default to disabled (0) for _enabled fields if not set (unchecked checkbox)
                if (strpos($field, '_enabled') !== false) {
                    update_post_meta($post_id, "_$field", '0'); // Default to disabled (0) for unchecked checkboxes
                    error_log("Defaulted $field to disabled (0).");
                } else {
                    delete_post_meta($post_id, "_$field"); // Ensure meta key starts with underscore
                    error_log("Deleted $field (not set).");
                }
            }
        }
    }
    add_action('save_post', '__smarty_magazine_save_two_column_tabs_meta');
}

if (!function_exists('__smarty_magazine_add_toc_to_post')) {
    /**
     * Adds a dynamic Table of Contents (ToC) to posts.
     *
     * @since 1.0.0
     * 
     * @param string $content The post content
     * 
     * @return string The modified content with ToC
     */
    function __smarty_magazine_add_toc_to_post($content) {
        if (is_singular(array('post', 'news'))) {
            global $post;

            // Extract headings (H2-H6) with positions
            preg_match_all('/<h([2-6])>(.*?)<\/h\1>/', $content, $matches, PREG_OFFSET_CAPTURE);
            $headings = $matches[0];

            if (!empty($headings)) {
                // Start Bootstrap 5 TOC container
                $toc_html = '<div class="container toc-container mb-5 px-0">';

                // TOC Header
                $toc_html .= '
                <div class="accordion shadow-lg rounded" id="tocAccordion">
                    <div class="accordion-item">
                        <div class="accordion-header" id="tocHeading">
                            <button class="accordion-button fw-semibold shadow-sm collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#tocContent" aria-expanded="false" aria-controls="tocContent">
                                <span><i class="bi bi-dot"></i></span>' . esc_html__('Table of Contents', 'smarty_magazine') . '
                            </button>
                        </div>
                        <div id="tocContent" class="accordion-collapse collapse" aria-labelledby="tocHeading" data-bs-parent="#tocAccordion">
                            <div class="accordion-body">
                                <ul>';
                                    // Add each heading as a link
                                    foreach ($headings as $index => $heading) {
                                        $heading_text = strip_tags($heading[0]);

                                        // Generate an anchor ID (keeping original heading in content)
                                        $anchor_id = 'sm-magazine-toc-heading-' . $index;

                                        // **Remove emojis only from the ToC version**
                                        $heading_text_toc = preg_replace('/[\x{1F300}-\x{1FAD6}\x{1F600}-\x{1F64F}\x{2600}-\x{26FF}]/u', '', $heading_text);

                                        // Add ToC entry without emojis
                                        $toc_html .= '<li><a href="#' . esc_attr($anchor_id) . '" class="sm-magazine-toc-link d-block py-1">' . esc_html($heading_text_toc) . '</a></li>';

                                        // Replace heading in content with an anchor (original text kept intact)
                                        $content = str_replace($heading[0], '<h' . $matches[1][$index][0] . ' id="' . esc_attr($anchor_id) . '">' . $heading_text . '</h' . $matches[1][$index][0] . '>', $content);
                                    }

                                    $toc_html .= '
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div> <!-- End ToC accordion item -->
                </div> <!-- End ToC container -->';

                // Prepend ToC to content
                $content = $toc_html . $content;
            }
        }

        return $content;
    }
    add_filter('the_content', '__smarty_magazine_add_toc_to_post');
}

if (!function_exists('__smarty_magazine_add_faq_meta_box')) {
    /**
	 * Add a meta box for FAQs in the post edit screen.
     * 
     * @since 1.0.0
     * 
     * @return void
	 */
	function __smarty_magazine_add_faq_meta_box() {
		add_meta_box(
			'__smarty_magazine_add_faq_meta_box', 
			__('FAQs', 'smarty_magazine'),
			'__smarty_magazine_faq_meta_box_callback', 
			array('post', 'news'), 
			'normal', 
			'high'
		);
	}
	add_action('add_meta_boxes', '__smarty_magazine_add_faq_meta_box');
}

if (!function_exists('__smarty_magazine_faq_meta_box_callback')) {
	/**
	 * Callback function to display and manage FAQs in the post edit screen.
     * 
     * @since 1.0.0
     * 
     * @param WP_Post $post The post object
     * 
     * @return void
	 */
	function __smarty_magazine_faq_meta_box_callback($post) {
		// Nonce field for security
		wp_nonce_field('smarty_magazine_save_faq_meta_box', 'smarty_magazine_faq_meta_box_nonce');

		// Retrieve existing FAQs and custom FAQ title
		$faqs = get_post_meta($post->ID, '_smarty_magazine_faqs', true);
		$faq_section_title = get_post_meta($post->ID, '_smarty_magazine_faq_section_title', true);

		// Custom FAQ Section Title ?>
		<p><strong><?php _e('Section Title', 'smarty_magazine'); ?></strong></p>
		<p><input type="text" name="_smarty_magazine_faq_section_title" id="sm-magazine-faq-section-title" value="<?php echo esc_attr($faq_section_title); ?>" style="width: 100%;"></p>
        <?php
		
        // Display existing FAQs
		if (!empty($faqs)) {
			foreach ($faqs as $index => $faq) {
				?>
				<div class="sm-magazine-custom-faq <?php echo ($index % 2 == 0) ? 'even' : 'odd'; ?>" data-index="<?php echo $index; ?>">
					<div class="sm-magazine-faq-header">
						<h4><?php printf(__('FAQ %d -', 'smarty_magazine'), ($index + 1)); ?> <span class="sm-magazine-faq-title"><?php echo esc_html($faq['question']); ?></span></h4>
						<button type="button" class="button button-secondary sm-magazine-toggle-faq"><?php _e('Toggle', 'smarty_magazine'); ?></button>
					</div>
					<div class="sm-magazine-faq-content" style="display: none;">
						<p>
							<label><?php _e('Question', 'smarty_magazine'); ?></label><br>
							<input type="text" name="_smarty_magazine_faqs[<?php echo $index; ?>][question]" value="<?php echo esc_attr($faq['question']); ?>" style="width: 100%;">
						</p>
						<p>
							<label><?php _e('Answer', 'smarty_magazine'); ?></label><br>
							<?php 
                            $editor_id = '_smarty_magazine_faqs_' . $index . '_answer';
                            $editor_settings = array(
                                'textarea_name' => "_smarty_magazine_faqs[{$index}][answer]",
                                'media_buttons' => true, // Enable media upload button
                                'textarea_rows' => 5,    // Set height
                                'tinymce'       => true, // Enable TinyMCE editor
                                'quicktags'     => true  // Enable Quicktags (HTML mode)
                            );
                            wp_editor( $faq['answer'], $editor_id, $editor_settings );
                            ?>
						</p>
						<input type="hidden" name="_smarty_magazine_faqs[<?php echo $index; ?>][delete]" value="0" class="sm-magazine-delete-input">
						<button type="button" class="button button-secondary sm-magazine-remove-faq-button"><?php _e('Remove FAQ', 'smarty_magazine'); ?></button>
					</div>
				</div>
				<?php
			}
		}
		?>
		<div id="sm-magazine-faqs-container"></div>
		<button type="button" id="sm-magazine-add-faq-button" class="button button-primary"><?php _e('Add FAQ', 'smarty_magazine'); ?></button><?php
	}
}

if (!function_exists('__smarty_magazine_save_faq_meta_box')) {
	/**
	 * Save FAQs and custom title when the post is saved.
     * 
     * @since 1.0.0
     * 
     * @param int $post_id The post ID
     * 
     * @return void
	 */
	function __smarty_magazine_save_faq_meta_box($post_id) {
		if (!isset($_POST['smarty_magazine_faq_meta_box_nonce']) 
            || !wp_verify_nonce($_POST['smarty_magazine_faq_meta_box_nonce'], 'smarty_magazine_save_faq_meta_box')) return;
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
		if (!current_user_can('edit_post', $post_id)) return;

		$new_faqs = array();

		if (isset($_POST['_smarty_magazine_faqs'])) {
			foreach ($_POST['_smarty_magazine_faqs'] as $faq) {
				if (isset($faq['delete']) && $faq['delete'] == '0') {
					$new_faqs[] = $faq;
				}
			}
			update_post_meta($post_id, '_smarty_magazine_faqs', $new_faqs);
		}

		if (isset($_POST['_smarty_magazine_faq_section_title'])) {
			update_post_meta($post_id, '_smarty_magazine_faq_section_title', sanitize_text_field($_POST['_smarty_magazine_faq_section_title']));
		}
	}
	add_action('save_post', '__smarty_magazine_save_faq_meta_box');
}

if (!function_exists('__smarty_magazine_display_faqs')) {
    /**
     * Display FAQs on single posts and 'news' CPTs.
     * 
     * @since 1.0.0
     * 
     * @param string $content The post content
     * 
     * @return string The modified post content
     */
    function __smarty_magazine_display_faqs($content) {
        if (is_singular(array('post', 'news'))) { // Apply to posts and 'news' CPT
            global $post;

            // Retrieve saved FAQs and custom title
            $faqs = get_post_meta($post->ID, '_smarty_magazine_faqs', true);
            $faq_section_title = get_post_meta($post->ID, '_smarty_magazine_faq_section_title', true);

            if (!empty($faqs)) {
                // Start Bootstrap 5 FAQ container
                $faq_html = '<div class="container faq-container px-0">';

                // Styled FAQ Header
                $faq_html .= '
               <div class="faq-header mb-4">
                    <h3>' . (!empty($faq_section_title) ? esc_html($faq_section_title) : __('Frequently Asked Questions', 'smarty_magazine')) . '</h3>
                    <p>' . __('Find answers to the most common questions below.', 'smarty_magazine') . '</p>
                </div>';

                // Accordion container with Bootstrap 5
                $faq_html .= '<div class="accordion shadow-lg rounded" id="faqAccordion">';

                // Schema.org FAQPage structure
                $faq_schema = array(
                    "@context"   => "https://schema.org",
                    "@type"      => "FAQPage",
                    "mainEntity" => array()
                );

                foreach ($faqs as $index => $faq) {
                    if (!empty($faq['question']) && !empty($faq['answer'])) {
                        // Generate unique IDs for Bootstrap accordion
                        $question_id = 'faqHeading' . $index;
                        $answer_id   = 'faqCollapse' . $index;

                        // Bootstrap 5 Accordion Item with Custom Colors
                        $faq_html .= '
                        <div class="accordion-item">
                            <h4 class="accordion-header" id="' . esc_attr($question_id) . '">
                                <button class="accordion-button fw-semibold shadow-sm collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#' . esc_attr($answer_id) . '" aria-expanded="false" aria-controls="' . esc_attr($answer_id) . '">
                                    <span><i class="bi bi-dot"></i></span>' . esc_html($faq['question']) . '
                                </button>
                            </h4>
                            <div id="' . esc_attr($answer_id) . '" class="accordion-collapse collapse" aria-labelledby="' . esc_attr($question_id) . '" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <p class="mb-0">' . wp_kses_post($faq['answer']) . '</p>
                                </div>
                            </div>
                        </div>';

                        // Add FAQ to Schema.org structure
                        $faq_schema['mainEntity'][] = array(
                            "@type" => "Question",
                            "name"  => esc_html($faq['question']),
                            "acceptedAnswer" => array(
                                "@type" => "Answer",
                                "text"  => wp_kses_post($faq['answer'])
                            )
                        );
                    }
                }

                $faq_html .= '</div>'; // Close accordion
                $faq_html .= '</div>'; // Close container

                // Use a global flag to prevent duplicate schema injections
                global $smarty_faq_schema_added;
                if (!isset($smarty_faq_schema_added)) {
                    $smarty_faq_schema_added = true;
                    add_action('wp_footer', function() use ($faq_schema) {
                        echo '<script type="application/ld+json">' . json_encode($faq_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
                    }, 1);
                }

                // Append FAQs to content
                $content .= $faq_html;
            }
        }

        return $content;
    }
    add_filter('the_content', '__smarty_magazine_display_faqs');
}

if (!function_exists('__smarty_magazine_add_twitter_embed_metabox')) {
    /**
     * Add metabox for Twitter embed code
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    function __smarty_magazine_add_twitter_embed_metabox() {
        add_meta_box(
            '__smarty_magazine_twitter_embed',
            __('Twitter Embed', 'smarty_magazine'),
            '__smarty_magazine_render_twitter_embed_metabox',
            array('post', 'news'), 
            'normal',
            'high'
        );
    }
    add_action('add_meta_boxes', '__smarty_magazine_add_twitter_embed_metabox');
}

if (!function_exists('__smarty_magazine_render_twitter_embed_metabox')) {
    /**
     * Render the Twitter embed metabox
     * 
     * @since 1.0.0
     * 
     * @param WP_Post $post The post object
     * 
     * @return void
     */
    function __smarty_magazine_render_twitter_embed_metabox($post) {
        // Get the saved Twitter embed code
        $twitter_embed = get_post_meta($post->ID, 'smarty_magazine_twitter_embed', true); ?>
        <textarea name="smarty_magazine_twitter_embed" rows="5" style="width:100%;" placeholder="Paste Twitter embed code here..."><?php echo esc_textarea($twitter_embed); ?></textarea>
        <?php if (!empty($twitter_embed)) : ?>
            <p><strong>Shortcode:</strong> <input type="text" value='[sm_magazine_twitter_embed]' readonly style="width:100%; background:#f3f3f3; border:none; padding:5px;"></p>
            <div style="margin-top: 10px; padding: 10px; border: 1px solid #ddd; background: #f9f9f9;">
                <strong>Preview:</strong>
                <div><?php echo $twitter_embed; ?></div>
            </div>
        <?php endif; ?>
        <?php
    }
}

if (!function_exists('__smarty_magazine_save_twitter_embed_metabox')) {
    /**
     * Save the Twitter embed code when the post is saved
     * 
     * @since 1.0.0
     * 
     * @param int $post_id The post ID
     * 
     * @return void
     */
    function __smarty_magazine_save_twitter_embed_metabox($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!isset($_POST['smarty_magazine_twitter_embed'])) return;
        if (!current_user_can('edit_post', $post_id)) return;

        // **Allow script tags**
        $allowed_tags = array(
            'blockquote' => array('class' => array()),
            'p' => array('lang' => array(), 'dir' => array()),
            'a' => array('href' => array(), 'rel' => array(), 'target' => array()),
            'script' => array('src' => array(), 'async' => array(), 'charset' => array())
        );

        $twitter_embed = wp_kses($_POST['smarty_magazine_twitter_embed'], $allowed_tags);
        update_post_meta($post_id, 'smarty_magazine_twitter_embed', $twitter_embed);

    }
    add_action('save_post', '__smarty_magazine_save_twitter_embed_metabox');
}

if (!function_exists('__smarty_magazine_twitter_embed_shortcode')) {
    /**
     * Shortcode to display the Twitter embed code
     * 
     * @since 1.0.0
     * 
     * @param array $atts The shortcode attributes
     * 
     * @return string The Twitter embed code
     */
    function __smarty_magazine_twitter_embed_shortcode($atts) {
        global $post;
        if (!$post) return '';

        // Retrieve saved Twitter embed code
        $twitter_embed = get_post_meta($post->ID, 'smarty_magazine_twitter_embed', true);

        if (!empty($twitter_embed)) {
            return '<div class="sm-magazine-twitter-embed">' . $twitter_embed . '</div>';
        }

        return ''; // Return empty if no embed exists
    }
    add_shortcode('sm_magazine_twitter_embed', '__smarty_magazine_twitter_embed_shortcode');
}

if (!function_exists('__smarty_magazine_dictionary_search')) {
    /**
     * Add a custom dictionary post type
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    function __smarty_magazine_dictionary_search() {
        check_ajax_referer('smarty_magazine_nonce', 'nonce');

        $search_query = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
        $args = array(
            'post_type'      => 'dictionary',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC',
            's'              => $search_query,
        );

        $items = get_posts($args);
        $grouped_items = array();
        foreach ($items as $item) {
            $first_letter = mb_strtoupper(mb_substr($item->post_title, 0, 1, 'UTF-8'), 'UTF-8');
            $grouped_items[$first_letter][] = $item;
        }

        ob_start();
        foreach ($grouped_items as $letter => $items) : ?>
            <section id="letter-<?php echo esc_attr($letter); ?>" class="dictionary-section mb-5">
                <h2 class="mb-3"><?php echo esc_html($letter); ?></h2>
                <div class="accordion" id="accordion-<?php echo esc_attr($letter); ?>">
                    <?php foreach ($items as $index => $item) : ?>
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="heading-<?php echo esc_attr($item->ID); ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo esc_attr($item->ID); ?>" aria-expanded="false" aria-controls="collapse-<?php echo esc_attr($item->ID); ?>">
                                    <?php echo esc_html($item->post_title); ?>
                                </button>
                            </h3>
                            <div id="collapse-<?php echo esc_attr($item->ID); ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?php echo esc_attr($item->ID); ?>" data-bs-parent="#accordion-<?php echo esc_attr($letter); ?>">
                                <div class="accordion-body">
                                    <?php
                                    echo wpautop(get_the_content(null, false, $item->ID));
                                    $article_id = get_post_meta($item->ID, '_dictionary_related_article', true);
                                    if ($article_id) {
                                        $article_link = get_permalink($article_id);
                                        echo '<p><a href="' . esc_url($article_link) . '" class="btn btn-link read-more">' . __('Read More', 'smarty_magazine') . '</a></p>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach;
        $output = ob_get_clean();

        wp_send_json_success($output);
    }
    add_action('wp_ajax_dictionary_search', '__smarty_magazine_dictionary_search');
    add_action('wp_ajax_nopriv_dictionary_search', '__smarty_magazine_dictionary_search');
}

if (!function_exists('__smarty_magazine_add_watermark_to_images')) {
    /**
     * Add a watermark to uploaded images
     * 
     * @since 1.0.0
     * 
     * @param array $metadata The attachment metadata
     * @param int $attachment_id The attachment ID
     * 
     * @return array The modified attachment metadata
     */
    function __smarty_magazine_add_watermark_to_images($metadata, $attachment_id) {
        if (!wp_attachment_is_image($attachment_id)) {
            return $metadata;
        }
    
        // Define watermark settings
        $watermark_text = "CRYPTOPOINT.BG";
        $font = get_template_directory() . '/assets/fonts/Bicubik.OTF';
        $font_size = 30;
        $opacity = 70; // 0-100
        $min_width = 1000; // Minimum width to apply watermark (adjust as needed)
    
        if (!extension_loaded('imagick') || !class_exists('Imagick')) {
            error_log('Imagick is not installed. Watermarking skipped.');
            return $metadata;
        }
    
        if (!file_exists($font)) {
            error_log('Font file not found at: ' . $font);
            return $metadata;
        }
    
        $upload_dir = wp_upload_dir();
        $image_path = $upload_dir['basedir'] . '/' . $metadata['file'];
    
        // Process full-size image if it meets the size threshold
        $image = new Imagick($image_path);
        if ($image->getImageWidth() >= $min_width) {
            $draw = new ImagickDraw();
            $draw->setFont($font);
            $draw->setFontSize($font_size);
            $draw->setFillColor('white');
            $draw->setFillOpacity($opacity / 100);
    
            $metrics = $image->queryFontMetrics($draw, $watermark_text);
            $x = $image->getImageWidth() - $metrics['textWidth'] - 10;
            $y = $image->getImageHeight() - $metrics['textHeight'] + $metrics['ascender'] - 10;
    
            $image->annotateImage($draw, $x, $y, 0, $watermark_text);
            $image->writeImage($image_path);
        }
        $image->destroy();
    
        // Apply to generated sizes, but only if they exceed the threshold
        if (isset($metadata['sizes']) && is_array($metadata['sizes'])) {
            foreach ($metadata['sizes'] as $size => $size_info) {
                $size_path = $upload_dir['basedir'] . '/' . dirname($metadata['file']) . '/' . $size_info['file'];
                $size_image = new Imagick($size_path);
    
                // Check size before applying watermark
                if ($size_image->getImageWidth() >= $min_width) {
                    $size_draw = new ImagickDraw();
                    $size_draw->setFont($font);
                    $size_draw->setFontSize($font_size);
                    $size_draw->setFillColor('white');
                    $size_draw->setFillOpacity($opacity / 100);
    
                    $size_metrics = $size_image->queryFontMetrics($size_draw, $watermark_text);
                    $size_x = $size_image->getImageWidth() - $size_metrics['textWidth'] - 10;
                    $size_y = $size_image->getImageHeight() - $size_metrics['textHeight'] + $size_metrics['ascender'] - 10;
    
                    $size_image->annotateImage($size_draw, $size_x, $size_y, 0, $watermark_text);
                    $size_image->writeImage($size_path);
                }
                $size_image->destroy();
            }
        }
    
        return $metadata;
    }
    add_filter('wp_generate_attachment_metadata', '__smarty_magazine_add_watermark_to_images', 10, 2);
}
