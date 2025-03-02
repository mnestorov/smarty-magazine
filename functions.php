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
        add_image_size('sm-featured-post-extra-large', 1200, 800, true);
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

        // Add custom editor style.
        add_editor_style('assets/css/sm-custom-editor.css'); // This is the path to the editor-style.css file.
    }
	add_action('after_setup_theme', '__smarty_magazine_setup');
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
	function __smarty_magazine_admin_scripts($hook) {
		wp_enqueue_style('sm-admin-css', get_template_directory_uri() . '/assets/css/sm-admin.css', array(), null, 'all');
		
		if ('widgets.php' === $hook || 'customize.php' === $hook) {
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script('wp-color-picker');
			wp_enqueue_media();
			wp_enqueue_script('sm-admin-js', get_template_directory_uri() . '/assets/js/sm-admin.js', array('jquery'), null, true);
            wp_localize_script('sm-admin-js', 'sm_ajax_data', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('sm_ajax_nonce'),
            ));
        }
	}
	add_action('admin_enqueue_scripts', '__smarty_magazine_admin_scripts');
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

if (!function_exists('__smarty_magazine_front_scripts')) {
    /**
     * Enqueue scripts and styles.
	 * 
	 * @since 1.0.0
	 * 
	 * @return void
	 * 
	 * @link https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
     */
    function __smarty_magazine_front_scripts() {
		wp_enqueue_style('sm-style', get_stylesheet_uri(), array(), null, 'all');
		wp_enqueue_style('sm-front-fonts', '//fonts.googleapis.com/css?family=Roboto:400,300,500,700,900');
		wp_enqueue_style('bootstrap-icons', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css', array(), '1.11.3', 'all');
		wp_enqueue_style('bootstrap-5', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css', array(), '5.3.3', 'all');
		wp_enqueue_script('bootstrap-5', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js', array('jquery'), '5.3.3', true);
		wp_enqueue_script('swiper-js', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.js', array('jquery'), '11.0.5', true);
		wp_enqueue_style('swiper-js', 'https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.css', array(), '11.0.5', 'all');
		wp_enqueue_script('news-ticker', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-advanced-news-ticker/1.0.1/js/newsTicker.min.js', array('jquery'), '1.0.1', true);
		wp_enqueue_style('sm-front-css', get_template_directory_uri() . '/assets/css/sm-front.css', array(), null, 'all');
		wp_enqueue_script('sm-front-js', get_template_directory_uri() . '/assets/js/sm-front.js', array('jquery'), null, true);

        // Comment reply script for threaded comments
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
    add_action('wp_enqueue_scripts', '__smarty_magazine_front_scripts');
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
            // Category archive
            $category = get_queried_object();
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html($category->name) . '</li>';
        } elseif (is_tag()) {
            // Tag archive
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html(single_tag_title('', false)) . '</li>';
        } elseif (is_day()) {
            // Daily archive
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html__('Archive for', 'smarty_magazine') . ' ' . get_the_date() . '</li>';
        } elseif (is_month()) {
            // Monthly archive
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html__('Archive for', 'smarty_magazine') . ' ' . get_the_date('F, Y') . '</li>';
        } elseif (is_year()) {
            // Yearly archive
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html__('Archive for', 'smarty_magazine') . ' ' . get_the_date('Y') . '</li>';
        } elseif (is_author()) {
            // Author archive
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html__('Author Archive', 'smarty_magazine') . '</li>';
        } elseif (is_search()) {
            // Search results
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html__('Search Results', 'smarty_magazine') . '</li>';
        } elseif (is_post_type_archive('news')) {
            // News archive
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html__('News', 'smarty_magazine') . '</li>';
        } elseif (is_tax('news_category')) {
            // News category archive
            echo '<li class="breadcrumb-item"><a href="' . esc_url(get_post_type_archive_link('news')) . '">' . esc_html__('News', 'smarty_magazine') . '</a></li>';
            $term = get_queried_object();
            if ($term->parent != 0) {
                $parent_term = get_term($term->parent, 'news_category');
                echo '<li class="breadcrumb-item"><a href="' . esc_url(get_term_link($parent_term)) . '">' . esc_html($parent_term->name) . '</a></li>';
            }
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html($term->name) . '</li>';
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
                $primary_cat = !empty($categories) ? $categories[0] : null;
                if ($primary_cat) {
                    echo '<li class="breadcrumb-item"><a href="' . esc_url(get_category_link($primary_cat->term_id)) . '">' . esc_html($primary_cat->name) . '</a></li>';
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
        } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
            // Paged blog archive
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html__('Blog Archive', 'smarty_magazine') . '</li>';
        }

        echo '</ol>';
        echo '</nav>';
    }
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