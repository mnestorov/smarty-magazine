<?php
/**
 * Register custom post types for this theme.
 * 
 * @since 1.0.0
 * 
 * @package Smarty_Magazine
 */

/**
 * ------------------------------------------------------------------------
 * ------------------------------------------------------------------------
 * -------------------- News - Post Type Functionality --------------------
 * ------------------------------------------------------------------------
 * ------------------------------------------------------------------------
 */

if (!function_exists('__smarty_magazine_register_widgets')) {
    /**
     * Register news post type for this theme.
     * 
     * @since 1.0.0
     * 
     * @return void
     * 
     * @link https://developer.wordpress.org/reference/functions/register_post_type/
     */
    function __smarty_magazine_register_news_post_type() {
        $labels = array(
            'name'                 => _x('News', 'Post Type General Name', 'smarty_magazine'),
            'singular_name'        => _x('News Item', 'Post Type Singular Name', 'smarty_magazine'),
            'menu_name'            => __('News', 'smarty_magazine'),
            'name_admin_bar'       => __('News', 'smarty_magazine'),
            'archives'             => __('News Archives', 'smarty_magazine'),
            'add_new'              => __('Add New', 'smarty_magazine'),
            'add_new_item'         => __('Add New News Item', 'smarty_magazine' ),
            'new_item'             => __('New News Item', 'smarty_magazine'),
            'edit_item'            => __('Edit News Item', 'smarty_magazine'),
            'view_item'            => __('View News Item', 'smarty_magazine'),
            'all_items'            => __('All News Items', 'smarty_magazine'),
            'search_items'         => __('Search News', 'smarty_magazine'),
            'not_found'            => __('No News Items found', 'smarty_magazine'),
            'not_found_in_trash'   => __('No News Items found in Trash', 'smarty_magazine'),
        );

        $args = array(
            'label'               => __('News', 'smarty_magazine'),
            'labels'              => $labels,
            'supports'            => array('title', 'editor', 'thumbnail', 'revisions', 'author', 'excerpt'),
            'hierarchical'        => true,
            'show_in_nav_menus'   => true,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-welcome-write-blog',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => true,
            'rewrite'             => array('slug' => 'news'),
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_rest'        => true,
            'query_var'           => true,
        );

        register_post_type('news', $args);
    }
    add_action('init', '__smarty_magazine_register_news_post_type');
}

if (!function_exists('__smarty_magazine_register_news_taxonomies')) {
    /**
     * Register custom taxonomies for the news post type.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    function __smarty_magazine_register_news_taxonomies() {
        // Register custom category taxonomy for news
        $labels = array(
            'name'              => _x('News Categories', 'taxonomy general name', 'smarty_magazine'),
            'singular_name'     => _x('News Category', 'taxonomy singular name', 'smarty_magazine'),
            'search_items'      => __('Search News Categories', 'smarty_magazine'),
            'all_items'         => __('All News Categories', 'smarty_magazine'),
            'parent_item'       => __('Parent News Category', 'smarty_magazine'),
            'parent_item_colon' => __('Parent News Category:', 'smarty_magazine'),
            'edit_item'         => __('Edit News Category', 'smarty_magazine'),
            'update_item'       => __('Update News Category', 'smarty_magazine'),
            'add_new_item'      => __('Add New News Category', 'smarty_magazine'),
            'new_item_name'     => __('New News Category Name', 'smarty_magazine'),
            'menu_name'         => __('News Categories', 'smarty_magazine'),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'news-category'),
        );

        register_taxonomy('news_category', array('news'), $args);

        // Register custom tag taxonomy for news
        $labels = array(
            'name'                       => _x('News Tags', 'taxonomy general name', 'smarty_magazine'),
            'singular_name'              => _x('News Tag', 'taxonomy singular name', 'smarty_magazine'),
            'search_items'               => __('Search News Tags', 'smarty_magazine'),
            'popular_items'              => __('Popular News Tags', 'smarty_magazine'),
            'all_items'                  => __('All News Tags', 'smarty_magazine'),
            'edit_item'                  => __('Edit News Tag', 'smarty_magazine'),
            'update_item'                => __('Update News Tag', 'smarty_magazine'),
            'add_new_item'               => __('Add New News Tag', 'smarty_magazine'),
            'new_item_name'              => __('New News Tag Name', 'smarty_magazine'),
            'separate_items_with_commas' => __('Separate news tags with commas', 'smarty_magazine'),
            'add_or_remove_items'        => __('Add or remove news tags', 'smarty_magazine'),
            'choose_from_most_used'      => __('Choose from the most used news tags', 'smarty_magazine'),
            'not_found'                  => __('No news tags found.', 'smarty_magazine'),
            'menu_name'                  => __('News Tags', 'smarty_magazine'),
        );

        $args = array(
            'hierarchical'          => false,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => array('slug' => 'news-tag'),
        );

        register_taxonomy('news_tag', array('news'), $args);
    }
    add_action('init', '__smarty_magazine_register_news_taxonomies');
}

if (!function_exists('__smarty_magazine_add_news_meta_boxes')) {
    /**
     * Add meta boxes for the news post type.
     * 
     * @since 1.0.0
     * 
     * @return void
     * 
     * @link https://developer.wordpress.org/reference/functions/add_meta_box/
     */
    function __smarty_magazine_add_news_meta_boxes() {
        add_meta_box(
            'news_disclaimer_meta_box',
            __('Disclaimer', 'smarty_magazine'),
            '__smarty_magazine_news_disclaimer_callback',
            'news',
            'normal',
            'high'
        );

        add_meta_box(
            'news_details_meta_box',
            __('News Details', 'smarty_magazine'),
            '__smarty_magazine_news_details_callback',
            'news',
            'side',
            'default'
        );
    }
    add_action('add_meta_boxes', '__smarty_magazine_add_news_meta_boxes');
}

if (!function_exists('__smarty_magazine_news_disclaimer_callback')) {
    /**
     * Display the disclaimer meta box.
     * 
     * @since 1.0.0
     * 
     * @param WP_Post $post The post object.
     * 
     * @return void
     */
    function __smarty_magazine_news_disclaimer_callback($post) {
        wp_nonce_field(basename(__FILE__), 'news_disclaimer_nonce');
        $disclaimer = get_post_meta($post->ID, '_news_disclaimer', true);
        ?>
        <textarea name="news_disclaimer" id="news_disclaimer" class="widefat" rows="5"><?php echo esc_textarea($disclaimer); ?></textarea>
        <p><?php _e('Write Disclaimer Text', 'smarty_magazine'); ?></p>
        <?php
    }
}

if (!function_exists('__smarty_magazine_news_details_callback')) {
    /**
     * Display the news details meta box.
     * 
     * @since 1.0.0
     * 
     * @param WP_Post $post The post object.
     * 
     * @return void
     */
    function __smarty_magazine_news_details_callback($post) {
        wp_nonce_field(basename(__FILE__), 'news_details_nonce');

        $author_id = get_post_field('post_author', $post->ID);
        $author_name = get_the_author_meta('display_name', $author_id);
        $image_source = get_post_meta($post->ID, '_news_image_source', true);
        $news_status = get_post_meta($post->ID, '_news_status', true);

        $statuses = array(
            'breaking'  => __('Breaking', 'smarty_magazine'),
            'featured'  => __('Featured', 'smarty_magazine'),
            'sponsored' => __('Sponsored', 'smarty_magazine')
        );
        
        $details = get_post_meta($post->ID, '_news_details', true);
        ?>
        <p>
            <label for="news_image_source"><b><?php _e('Image Source', 'smarty_magazine'); ?></b></label>
            <input type="text" name="news_image_source" id="news_image_source" class="widefat" value="<?php echo esc_attr($image_source); ?>">
        </p>
        <p>
            <label for="news_status"><b><?php _e('News Status', 'smarty_magazine'); ?></b></label>
            <select name="news_status" id="news_status" class="widefat">
                <option value=""><?php _e('Select a status', 'smarty_magazine'); ?></option>
                <?php foreach ($statuses as $key => $value) : ?>
                    <option value="<?php echo esc_attr($key); ?>" <?php selected($news_status, $key); ?>><?php echo esc_html($value); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <label for="news_details"><b><?php _e('Details', 'smarty_magazine'); ?></b></label>
        <textarea name="news_details" id="news_details" class="widefat" rows="5"><?php echo esc_attr($details); ?></textarea>
        <?php
    }
}

if (!function_exists('__smarty_magazine_save_news_meta')) {
    /**
     * Save news meta data.
     * 
     * @since 1.0.0
     * 
     * @param int $post_id The post ID.
     * 
     * @return void
     */
    function __smarty_magazine_save_news_meta($post_id) {
        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
    
        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    
        // Save disclaimer
        if (isset($_POST['news_disclaimer_nonce']) && wp_verify_nonce($_POST['news_disclaimer_nonce'], basename(__FILE__))) {
            if (isset($_POST['news_disclaimer'])) {
                update_post_meta($post_id, '_news_disclaimer', sanitize_textarea_field($_POST['news_disclaimer']));
            }
        }
    
        // Save news details
        if (isset($_POST['news_details_nonce']) && wp_verify_nonce($_POST['news_details_nonce'], basename(__FILE__))) {
            if (isset($_POST['news_image_source'])) {
                update_post_meta($post_id, '_news_image_source', sanitize_text_field($_POST['news_image_source']));
            }
    
            if (isset($_POST['news_status'])) {
                update_post_meta($post_id, '_news_status', sanitize_text_field($_POST['news_status']));
            }
    
            if (isset($_POST['news_details'])) {
                update_post_meta($post_id, '_news_details', sanitize_textarea_field($_POST['news_details']));
            }
        }
    }
    add_action('save_post', '__smarty_magazine_save_news_meta');
}

if (!function_exists('__smarty_magazine_get_news_disclaimer')) {
    /**
     * Get news meta data.
     * 
     * @since 1.0.0
     * 
     * @param int $post_id The post ID.
     * 
     * @return mixed
     */
    function __smarty_magazine_get_news_disclaimer($post_id) {
        return get_post_meta($post_id, '_news_disclaimer', true);
    }
}

if (!function_exists('__smarty_magazine_get_news_image_source')) {
    /**
     * Get news image source meta data.
     * 
     * @since 1.0.0
     * 
     * @param int $post_id The post ID.
     * 
     * @return mixed
     */
    function __smarty_magazine_get_news_image_source($post_id) {
        return get_post_meta($post_id, '_news_image_source', true, array('class' => 'img-fluid', 'itemprop' => 'image'));
    }
}

if (!function_exists('__smarty_magazine_get_news_status')) {
    /**
     * Get news details meta data.
     * 
     * @since 1.0.0
     * 
     * @param int $post_id The post ID.
     * 
     * @return mixed
     */
    function __smarty_magazine_get_news_status($post_id) {
        return get_post_meta($post_id, '_news_status', true) ?: array();
    }
}

if (!function_exists('__smarty_magazine_get_news_details')) {
    /**
     * Get news details meta data.
     * 
     * @since 1.0.0
     * 
     * @param int $post_id The post ID.
     * 
     * @return mixed
     */
    function __smarty_magazine_get_news_details($post_id) {
        return get_post_meta($post_id, '_news_details', true);
    }
}

if (!function_exists('__smarty_magazine_get_news_related')) {
    /**
     * Get related news meta data.
     * 
     * @since 1.0.0
     * 
     * @param int $post_id The post ID.
     * 
     * @return mixed
     */
    function __smarty_magazine_get_news_related($post_id) {
        return get_post_meta($post_id, '_news_related', true) ?: array();
    }
}

if (!function_exists('__smarty_magazine_get_related_news')) {
    /**
     * Get posts automatically related by categories or tags.
     *
     * @since 1.0.0
     * 
     * @param int $post_id The post ID.
     * 
     * @return array Array of WP_Post objects
     */
    function __smarty_magazine_get_related_news($post_id) {
        // Fetch category IDs
        $news_cats = wp_get_post_terms($post_id, 'news_category', array('fields' => 'ids'));
        // Fetch tag IDs
        $news_tags = wp_get_post_terms($post_id, 'news_tag', array('fields' => 'ids'));

        if (empty($news_cats) && empty($news_tags)) {
            return array(); 
        }

        // Query latest 4 news items matching any (category OR tag)
        $args = array(
            'post_type'      => 'news',
            'post__not_in'   => array($post_id),
            'posts_per_page' => 4,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'tax_query'      => array(
                'relation' => 'OR',
                array(
                    'taxonomy' => 'news_category',
                    'field'    => 'term_id',
                    'terms'    => $news_cats,
                ),
                array(
                    'taxonomy' => 'news_tag',
                    'field'    => 'term_id',
                    'terms'    => $news_tags,
                ),
            ),
        );

        return get_posts($args);
    }
}

if (!function_exists('__smarty_magazine_sort_news_by_date')) {
    /**
     * Sort news by date in the admin area.
     * 
     * @since 1.0.0
     * 
     * @param WP_Query $query The WP_Query object.
     * 
     * @return void
     */
    function __smarty_magazine_sort_news_by_date($query) {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }
    
        // Use post_type instead of screen ID to avoid early calls
        if (isset($query->query['post_type']) && $query->query['post_type'] === 'news') {
            $query->set('orderby', 'date');
            $query->set('order', 'DESC');
        }
    }
    add_action('pre_get_posts', '__smarty_magazine_sort_news_by_date');
}

/**
 * ------------------------------------------------------------------------
 * ------------------------------------------------------------------------
 * ---------------- Dictionary - Post Type Functionality ------------------
 * ------------------------------------------------------------------------
 * ------------------------------------------------------------------------
 */

if (!function_exists('__smarty_magazine_register_dictionary_post_type')) {
    /**
     * Register Dictionary custom post type for this theme.
     *
     * @since 1.0.0
     *
     * @return void
     */
    function __smarty_magazine_register_dictionary_post_type() {
        $labels = array(
            'name'                  => _x('Dictionary', 'Post Type General Name', 'smarty_magazine'),
            'singular_name'         => _x('Dictionary Item', 'Post Type Singular Name', 'smarty_magazine'),
            'menu_name'             => __('Dictionary', 'smarty_magazine'),
            'name_admin_bar'        => __('Dictionary Item', 'smarty_magazine'),
            'archives'              => __('Dictionary Archives', 'smarty_magazine'),
            'add_new'               => __('Add New', 'smarty_magazine'),
            'add_new_item'          => __('Add New Dictionary Item', 'smarty_magazine'),
            'new_item'              => __('New Dictionary Item', 'smarty_magazine'),
            'edit_item'             => __('Edit Dictionary Item', 'smarty_magazine'),
            'view_item'             => __('View Dictionary Item', 'smarty_magazine'),
            'all_items'             => __('All Dictionary Items', 'smarty_magazine'),
            'search_items'          => __('Search Dictionary', 'smarty_magazine'),
            'not_found'             => __('No Dictionary Items found', 'smarty_magazine'),
            'not_found_in_trash'    => __('No Dictionary Items found in Trash', 'smarty_magazine'),
        );

        $args = array(
            'label'                => __('Dictionary', 'smarty_magazine'),
            'labels'               => $labels,
            'supports'             => array('title', 'editor'), // Only title and editor (textarea)
            'public'               => true,
            'show_ui'              => true,
            'show_in_menu'         => true,
            'menu_position'        => 6, // Below News (which is 5)
            'menu_icon'            => 'dashicons-book-alt',
            'show_in_admin_bar'    => true,
            'can_export'           => true,
            'has_archive'          => true,
            'rewrite'              => false,
            'exclude_from_search'  => false,
            'publicly_queryable'   => false,
            'capability_type'      => 'post',
            'show_in_rest'         => true,
            'taxonomies'           => array(), // No categories or tags
        );

        register_post_type('dictionary', $args);
    }
    add_action('init', '__smarty_magazine_register_dictionary_post_type');
}

if (!function_exists('__smarty_magazine_add_dictionary_meta_boxes')) {
    /**
     * Add meta box for Dictionary CPT to select a related article.
     *
     * @since 1.0.0
     * 
     * @return void
     */
    function __smarty_magazine_add_dictionary_meta_boxes() {
        add_meta_box(
            'dictionary_related_article_meta_box',
            __('Related Article', 'smarty_magazine'),
            '__smarty_magazine_dictionary_related_article_callback',
            'dictionary',
            'side',
            'default'
        );
    }
    add_action('add_meta_boxes', '__smarty_magazine_add_dictionary_meta_boxes');
}

if (!function_exists('__smarty_magazine_dictionary_related_article_callback')) {
    /**
     * Callback to display the related article meta box.
     *
     * @since 1.0.0
     * 
     * @param WP_Post $post The post object.
     * 
     * @return void
     */
    function __smarty_magazine_dictionary_related_article_callback($post) {
        wp_nonce_field('__smarty_magazine_dictionary_meta_box', 'dictionary_related_article_nonce');
        $selected_article = get_post_meta($post->ID, '_dictionary_related_article', true);

        // Get all posts (default 'post' type)
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC',
        );
        $articles = get_posts($args);
        ?>
        <p>
            <label for="sm-dictionary-related-article"><b><?php _e('Select an Article', 'smarty_magazine'); ?></b></label>
            <select name="sm-dictionary-related-article" id="sm-dictionary-related-article" class="widefat">
                <option value=""><?php _e('Select a article', 'smarty_magazine'); ?></option>
                <?php foreach ($articles as $article) : ?>
                    <option value="<?php echo esc_attr($article->ID); ?>" <?php selected($selected_article, $article->ID); ?>>
                        <?php echo esc_html($article->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }
}

if (!function_exists('__smarty_magazine_save_dictionary_meta')) {
    /**
     * Save the related article meta data.
     *
     * @since 1.0.0
     * 
     * @param int $post_id The post ID.
     * 
     * @return void
     */
    function __smarty_magazine_save_dictionary_meta($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!isset($_POST['dictionary_related_article_nonce']) || !wp_verify_nonce($_POST['dictionary_related_article_nonce'], '__smarty_magazine_dictionary_meta_box')) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['dictionary_related_article'])) {
            update_post_meta($post_id, '_dictionary_related_article', sanitize_text_field($_POST['dictionary_related_article']));
        } else {
            delete_post_meta($post_id, '_dictionary_related_article');
        }
    }
    add_action('save_post', '__smarty_magazine_save_dictionary_meta');
}

if (!function_exists('__smarty_magazine_dictionary_columns')) {
    /**
     * Customize Dictionary admin columns.
     *
     * @since 1.0.0
     * 
     * @param array $columns The existing columns.
     * 
     * @return array The modified columns.
     */
    function __smarty_magazine_dictionary_columns($columns) {
        $columns = array(
            'cb'              => '<input type="checkbox" />',
            'title'           => __('Title', 'smarty_magazine'),
            'first_letter'    => __('First Letter', 'smarty_magazine'),
            'related_article' => __('Related Article', 'smarty_magazine'),
            'date'            => __('Date', 'smarty_magazine'),
        );
        return $columns;
    }
    add_filter('manage_dictionary_posts_columns', '__smarty_magazine_dictionary_columns');
}

if (!function_exists('__smarty_magazine_dictionary_custom_column')) {
    /**
     * Populate custom columns in Dictionary admin table.
     *
     * @since 1.0.0
     * 
     * @param string $column  The column name.
     * @param int    $post_id The post ID.
     * 
     * @return void
     */
    function __smarty_magazine_dictionary_custom_column($column, $post_id) {
        if ($column === 'first_letter') {
            $title = get_the_title($post_id);
            echo esc_html(mb_strtoupper(mb_substr($title, 0, 1, 'UTF-8')));
        }
    
        if ($column === 'related_article') {
            $article_id = get_post_meta($post_id, '_dictionary_related_article', true);
            if ($article_id) {
                $article_title = get_the_title($article_id);
                $article_link = get_edit_post_link($article_id);
                echo '<a href="' . esc_url($article_link) . '">' . esc_html($article_title) . '</a>';
            } else {
                echo __('None', 'smarty_magazine');
            }
        }
    } 
    add_action('manage_dictionary_posts_custom_column', '__smarty_magazine_dictionary_custom_column', 10, 2);
}

if (!function_exists('__smarty_magazine_dictionary_sortable_columns')) {
    /**
     * Make the first letter column sortable.
     *
     * @since 1.0.0
     * 
     * @param array $columns The existing columns.
     * 
     * @return array The modified columns.
     */
    function __smarty_magazine_dictionary_sortable_columns($columns) {
        $columns['first_letter'] = 'first_letter';
        return $columns;
    }
    add_filter('manage_edit-dictionary_sortable_columns', '__smarty_magazine_dictionary_sortable_columns');
}

if (!function_exists('__smarty_magazine_sort_dictionary_by_first_letter')) {
    /**
     * Sort Dictionary by first letter.
     *
     * @since 1.0.0
     * 
     * @param WP_Query $query The WP_Query object.
     * 
     * @return void
     */
    function __smarty_magazine_sort_dictionary_by_first_letter($query) {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }

        if ($query->get('post_type') === 'dictionary' && $query->get('orderby') === 'first_letter') {
            $query->set('orderby', 'title');
            $query->set('order', 'ASC');
        }
    }
    add_action('pre_get_posts', '__smarty_magazine_sort_dictionary_by_first_letter');
}

if (!function_exists('__smarty_magazine_default_sort_dictionary')) {
    /**
     * Sort Dictionary by title in the admin area.
     * 
     * @since 1.0.0
     * 
     * @param WP_Query $query The WP_Query object.
     * 
     * @return void
     */
    function __smarty_magazine_default_sort_dictionary($query) {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }

        // Check if it's the Dictionary post type
        if ($query->get('post_type') === 'dictionary') {
            // Only apply default sorting if no orderby parameter is set
            if (!$query->get('orderby')) {
                $query->set('orderby', 'title');
                $query->set('order', 'ASC'); // Sort A → Z
            }
        }
    }
    add_action('pre_get_posts', '__smarty_magazine_default_sort_dictionary');
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
            <section id="letter-<?php echo esc_attr($letter); ?>" class="sm-dictionary-section mb-5">
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
                                        echo '<p><a href="' . esc_url($article_link) . '" class="btn btn-link sm-dictionary-read-more">' . __('Read More', 'smarty_magazine') . '</a></p>';
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