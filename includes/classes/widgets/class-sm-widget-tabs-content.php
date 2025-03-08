<?php

/**
 * Tabs Content Widget.
 *
 * This class serves as a base widget for displaying post content in various tabbed layouts.
 * It provides multiple layout configurations and dynamic content querying features.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class __Smarty_Magazine_Tabs_Content extends WP_Widget
{
    /**
     * @var int Layout identifier to determine the current display style.
     */
    public $layout;

    /**
     * @var string Additional CSS class applied to the widget layout container.
     */
    public $layout_class;

    /**
     * Constructor: Initializes the widget with its ID, name, and options.
     *
     * @param string $id_base         Base ID for the widget.
     * @param string $name            Name of the widget.
     * @param array  $widget_options  Options for the widget's appearance and behavior.
     * @param array  $control_options Options for widget controls.
     */
    public function __construct($id_base = '', $name = '', $widget_options = array(), $control_options = array())
    {
        parent::__construct($id_base, $name, $widget_options, $control_options);
    }

    /**
     * Queries posts based on widget settings.
     *
     * Constructs a WP_Query object based on the widget's configuration, handling categories,
     * post limits, ordering, and other criteria.
     *
     * @param array $instance Widget settings.
     * @return WP_Query The constructed query object.
     */
    public function query($instance = array())
    {
        $instance = wp_parse_args($instance, array());
        $viewing = isset($instance['viewing']) ? explode(',', $instance['viewing']) : array();

        $viewing = array_map('absint', $viewing);
        $viewing = array_filter($viewing);

        if (defined('DOING_AJAX') && DOING_AJAX) {
            $this->layout = isset($instance['_layout']) ? absint($instance['_layout']) : 1;
        }

        if (!isset($instance['category'])) {
            $instance['category'] = [];
        }

        // Ensure category is always an array
        if (is_string($instance['category'])) {
            $instance['category'] = array_map('absint', explode(',', $instance['category']));
        } elseif (is_int($instance['category'])) {
            $instance['category'] = [absint($instance['category'])];  // Convert single int to array
        } elseif (!is_array($instance['category'])) {
            $instance['category'] = [];
        }

        $instance['category'] = array_filter($instance['category']);

        if (empty($viewing)) {
            $show_all = isset($instance['show_all']) ? $instance['show_all'] : '';
            $viewing = ($show_all === 'on' && !empty($instance['category'])) ? $instance['category'] : (is_array($instance['category']) ? current($instance['category']) : []);
        }

        $no_of_posts = absint($instance['no_of_posts'] ?? $instance['posts_per_page'] ?? get_option('posts_per_page'));
        $max_posts = apply_filters('__smarty_magazine_tabs_content_max_posts', 30);
        $no_of_posts = min($no_of_posts, $max_posts);

        $args = array(
            'post_type'           => 'post',
            'category__in'        => $viewing,
            'posts_per_page'      => $no_of_posts,
            'ignore_sticky_posts' => true,
            'paged'               => absint($instance['paged'] ?? 0),
        );

        if (in_array($instance['orderby'] ?? '', ['date', 'title', 'rand', 'comment_count'])) {
            $args['orderby'] = $instance['orderby'];
        }

        if (in_array($instance['order'] ?? '', ['asc', 'desc'])) {
            $args['order'] = $instance['order'];
        }

        return new WP_Query(apply_filters('__smarty_magazine_widget_content_args', $args, $instance, $this));
    }

    /**
     * Prepares widget instance settings by ensuring defaults are applied.
     *
     * @param array $instance Current widget settings.
     * @return array Prepared settings with defaults.
     */
    public function setup_instance($instance)
    {
        $prepared_instance = [];

        foreach ($this->get_configs() as $config) {
            $name = $config['name'] ?? '';
            $prepared_instance[$name] = isset($instance[$name]) ? $instance[$name] : ($config['default'] ?? null);
        }

        // Ensure 'category' is always an array
        if (!isset($prepared_instance['category']) || !is_array($prepared_instance['category'])) {
            $prepared_instance['category'] = is_numeric($prepared_instance['category'])
                ? [(int) $prepared_instance['category']]
                : [];
        }

        // Ensure 'show_all' is set
        if (!isset($prepared_instance['show_all'])) {
            $prepared_instance['show_all'] = '';
        }

        return $prepared_instance;
    }

    /**
     * Outputs the widget content on the front-end.
     *
     * @param array $args     Display arguments including 'before_widget', 'after_widget', etc.
     * @param array $instance The settings for the current widget instance.
     */
    public function widget($args, $instance)
    {
        // Ensure default arguments exist to prevent undefined index warnings
        $args = wp_parse_args($args, array(
            'before_widget' => '<div class="widget %s">',  // Default opening div for widget
            'after_widget'  => '</div>',                   // Default closing div for widget
            'before_title'  => '<h2 class="widget-title">', // Default before title tag
            'after_title'   => '</h2>',                     // Default after title tag
        ));

        // Output the opening widget structure
        echo $args['before_widget'];

        // Setup the instance data if not already done
        if (!isset($instance['__setup_data']) || $instance['__setup_data'] !== false) {
            $instance = $this->setup_instance($instance);
        }

        // Get the title or set a default
        $title = !empty($instance['title']) ? $instance['title'] : __('Default Title', 'smarty_magazine');
        unset($instance['title']);  // Remove title from instance to avoid conflicts in queries

        // Query posts based on widget settings
        $query = $this->query($instance);
        $instance['_layout'] = $this->layout;  // Apply layout information

        // Prepare CSS classes for widget display
        $classes = ['sm-news-list-' . $this->layout];
        if ($this->layout_class) {
            $classes[] = $this->layout_class;
        }
?>
        <div class="<?php echo esc_attr(implode(' ', $classes)); ?>">
            <div class="news-layout-tabs sm-news-layout-wrap" data-ajax="<?php echo esc_url(admin_url('admin-ajax.php')); ?>" data-instance="<?php echo esc_attr(json_encode($instance)); ?>">
                <?php if ($title) : ?>
                    <div class="widget-title filter-inside" style="display: flex; justify-content: space-between; align-items: center;">
                        <?php echo $args['before_title'] . esc_html($title) . $args['after_title']; ?>

                        <?php
                        // Handle category display
                        $first_category = is_array($instance['category']) ? reset($instance['category']) : $instance['category'];
                        if ($term = get_term($first_category, 'category')) :
                        ?>
                            <span class="view-all-link">
                                <a href="<?php echo esc_url(get_term_link($term)); ?>">
                                    <?php esc_html_e('[ View All ]', 'smarty_magazine'); ?>
                                </a>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="news-tab-layout animate sm-news-layout<?php echo esc_attr($this->layout); ?>">
                    <div class="news-tab-posts">
                        <?php
                        // Display posts if available
                        if ($query->have_posts()) {
                            $layout_method = 'layout_' . $this->layout;
                            if (method_exists($this, $layout_method)) {
                                $this->$layout_method($query);
                            } else {
                                $this->layout_content($query);
                            }
                        } else {
                            $this->not_found();  // Fallback if no posts found
                        }
                        ?>
                    </div>
                </div>
                <?php wp_reset_postdata(); ?>
            </div>
        </div>
        <?php

        // Output the closing widget structure
        echo $args['after_widget'];
    }

    /**
     * Default content layout for displaying posts.
     *
     * @param WP_Query $query The query object containing posts to display.
     */
    public function layout_content($query)
    {
        while ($query->have_posts()) : $query->the_post(); ?>
            <div class="sm-news-post">
                <figure class="sm-news-post-img">
                    <?php if (has_post_thumbnail()) : the_post_thumbnail('sm-featured-post-medium');
                    endif; ?>
                    <a href="<?php echo esc_url(get_permalink()); ?>" rel="bookmark">
                        <span><i class="bi bi-search"></i></span>
                    </a>
                </figure>
                <div class="sm-news-post-content">
                    <div class="sm-news-post-meta">
                        <span class="sm-news-post-date"><i class="bi bi-calendar"></i> <?php echo esc_html(get_the_date()); ?></span>
                        <span class="sm-news-post-comments"><i class="bi bi-chat"></i> <?php comments_number(__('No Responses', 'smarty_magazine'), __('One Response', 'smarty_magazine'), __('% Responses', 'smarty_magazine')); ?></span>
                    </div>
                    <h3>
                        <a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(wp_trim_words(get_the_title(), 10, '...')); ?></a>
                    </h3>
                    <div class="sm-news-post-desc"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 40, '...')); ?></div>
                </div>
            </div>
            <?php endwhile;
    }

    /**
     * Displays a message when no posts are found.
     */
    public function not_found()
    {
        echo '<p class="posts-not-found">' . esc_html__('Sorry, no posts found in selected category.', 'smarty_magazine') . '</p>';
    }

    /**
     * Retrieves the widget's configurable fields.
     *
     * @return array Array of configuration fields.
     */
    public function get_configs() {
        return [
            ['type' => 'text', 'name' => 'title', 'label' => esc_html__('Title', 'smarty_magazine')],
            ['type' => 'repeatable', 'name' => 'category', 'label' => esc_html__('Categories', 'smarty_magazine')],
            ['type' => 'checkbox', 'name' => 'show_all', 'label' => esc_html__('Show All Filter', 'smarty_magazine')],
            ['type' => 'text', 'name' => 'no_of_posts', 'label' => esc_html__('Number of Posts', 'smarty_magazine')],
        ];
    }

    public function end_layout($query) {
        wp_reset_postdata();
    }

    public function layout_1($query) {
        $this->layout_content($query);
    }

    public function layout_2( $query ) {
        global $post;
        while ( $query->have_posts() ) { $query->the_post(); ?>
            <div class="sm-news-post">
                <figure class="sm-news-post-img">
                    <?php
                    if (has_post_thumbnail()) :
                        $image = '';
                        $title_attribute = get_the_title($post->ID);
                        $image .= '<a href="' . esc_url(get_permalink()) . '" title="' . esc_html(the_title('', '', false)) . '">';
                        $image .= get_the_post_thumbnail($post->ID, 'sm-featured-post-medium', array('title' => esc_attr($title_attribute), 'alt' => esc_attr($title_attribute))) . '</a>';
                        echo $image;

                    endif;
                    ?>
                    <div class="sm-news-post-meta">
                        <span class="sm-news-post-month"><?php esc_attr(the_time("M")); ?><br/><?php esc_attr(the_time("Y")); ?></span>
                        <span class="sm-news-post-day"><?php esc_attr(the_time("d")); ?></span>
                    </div>
                </figure>

                <div class="sm-news-post-content">
                    <h3>
                        <a href="<?php esc_url(the_permalink()); ?>" title="<?php the_title_attribute(); ?>">
                            <?php echo wp_trim_words(get_the_title(), 15, '...'); ?>
                        </a>
                    </h3>
                    <!--
                    <div class="sm-news-post-desc">
                        <?php //echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                    </div>
                    -->
                </div>
            </div><?php
        };

        $this->end_layout( $query );
    }

    public function layout_3($query) {
        while ( $query->have_posts() ) { $query->the_post(); ?>
            <div class="sm-news-post">
                <figure class="sm-news-post-img">
                    <?php
                    if ( has_post_thumbnail() ) :
                        the_post_thumbnail( 'sm-featured-post-medium' );
                    endif;
                    ?>
                    <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><span><i class="bi bi-search"></i></span></a>
                </figure>
                <div class="sm-news-post-content">
                    <div class="sm-news-post-meta">
                        <span class="sm-news-post-date"><i class="bi bi-calendar"></i> <?php the_time ( get_option ( 'date_format' ) ); ?></span>
                        <span class="sm-news-post-comments"><i class="bi bi-chat"></i> <?php comments_number( 'No Responses', 'one Response', '% Responses' ); ?></span>
                    </div>
                    <h3>
                        <a href="<?php esc_url( the_permalink() ); ?>" title="<?php the_title_attribute(); ?>">
                            <?php echo wp_trim_words( get_the_title(), 10, '...' ); ?>
                        </a>
                    </h3>
                    <div class="sm-news-post-desc">
                        <?php
                        $excerpt = get_the_excerpt();
                        $limit   = "210";
                        $pad     = "...";
                        if ( strlen( $excerpt ) <= $limit ) {
                            echo esc_html( $excerpt );
                        } else {
                            $excerpt = substr( $excerpt, 0, $limit ) . $pad;
                            echo esc_html( $excerpt );
                        }
                        ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div><?php 
        }

        $this->end_layout($query);
    }

    public function layout_4($query) {

        $n = count( $query->posts );
        global $post;

        $i = 0;
        while ( $query->have_posts() ) { $query->the_post();
            $i++;
            $c = 'sm-news-post';
            if ( $n % 2 == 0 ){
                if ( $i == $n ) {
                    $c.= ' last';
                } else if ( $i == $n - 1 ) {
                    $c.= ' e-last';
                }
            } else {
                if ( $i == $n ) {
                    $c.= ' last';
                }
            }

            ?>
            <div class="<?php echo esc_attr( $c ); ?>">
                <figure class="sm-news-post-img">
                    <?php
                    if ( has_post_thumbnail() ) :
                        $image = '';
                        $title_attribute = get_the_title( $post->ID );
                        $image .= '<a href="'. esc_url( get_permalink() ) . '" title="' . esc_html( the_title( '', '', false ) ) .'">';
                        $image .= get_the_post_thumbnail( $post->ID, 'sm-featured-post-small', array( 'title' => esc_attr( $title_attribute ), 'alt' => esc_attr( $title_attribute ) ) ).'</a>';
                        echo $image;

                    endif;
                    ?>
                    <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark"><span><i class="bi bi-search"></i></span></a>
                </figure>
                <div class="sm-news-post-content">
                    <h3><a href="<?php esc_url( the_permalink() ); ?>" title="<?php the_title_attribute(); ?>"><?php esc_html( the_title() ); ?></a></h3>
                </div>
            </div><?php
        };

        $this->end_layout($query);
    }

    /**
     * Render the form field based on its type.
     *
     * @param array $field Field configuration.
     */
    public function field($field) {
        $name = esc_attr($this->get_field_name($field['name']));
        $id = esc_attr($this->get_field_id($field['name']));
        $value = $field['value'];

        switch ($field['type']) {
            case 'text': ?>
                <p>
                    <label for="<?php echo $id; ?>"><?php echo esc_html($field['label']); ?></label>
                    <input class="widefat" id="<?php echo $id; ?>" name="<?php echo $name; ?>" type="text" value="<?php echo $value; ?>">
                </p><?php
                break;

            case 'list_cat':
                // Ensure single value for select input
                $selected_value = is_array($value) ? reset($value) : $value; ?>
                <p>
                    <label for="<?php echo $id; ?>"><?php echo esc_html($field['label']); ?></label>
                    <select class="widefat" id="<?php echo $id; ?>" name="<?php echo $name; ?>">
                        <option value=""><?php esc_html_e('Select a category', 'smarty_magazine'); ?></option>
                        <?php
                        $categories = get_categories(['hide_empty' => false]);
                        foreach ($categories as $category) { ?>
                            <option value="<?php echo esc_attr($category->term_id); ?>" <?php selected($selected_value, $category->term_id); ?>>
                                <?php echo esc_html($category->name); ?>
                            </option>
                        <?php } ?>
                    </select>
                </p><?php
                break;

            case 'orderby':
                $options = [
                    'date'           => __('Date', 'smarty_magazine'),
                    'title'          => __('Title', 'smarty_magazine'),
                    'rand'           => __('Random', 'smarty_magazine'),
                    'comment_count'  => __('Comment Count', 'smarty_magazine'),
                ]; ?>
                <p>
                    <label for="<?php echo $id; ?>"><?php echo esc_html($field['label']); ?></label>
                    <select class="widefat" id="<?php echo $id; ?>" name="<?php echo $name; ?>">
                        <?php foreach ($options as $key => $label) : ?>
                            <option value="<?php echo esc_attr($key); ?>" <?php selected($field['value'], $key); ?>>
                                <?php echo esc_html($label); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </p><?php
                break;

            case 'order': ?>
                <p>
                    <label for="<?php echo $id; ?>"><?php echo esc_html($field['label']); ?></label>
                    <select class="widefat" id="<?php echo $id; ?>" name="<?php echo $name; ?>">
                        <option value="ASC" <?php selected($field['value'], 'ASC'); ?>><?php esc_html_e('Ascending', 'smarty_magazine'); ?></option>
                        <option value="DESC" <?php selected($field['value'], 'DESC'); ?>><?php esc_html_e('Descending', 'smarty_magazine'); ?></option>
                    </select>
                </p><?php
                break;

            default: // Fallback for unhandled field types ?>
                <p>
                    <label for="<?php echo $id; ?>"><?php echo esc_html($field['label']); ?></label>
                    <input class="widefat" id="<?php echo $id; ?>" name="<?php echo $name; ?>" type="text" value="<?php echo $value; ?>">
                </p><?php
                break;
        }
    }

    /**
     * @param array $instance
     * @return void
     */
    public function render_fields($instance = array()) {
        $fields = $this->get_configs();
        foreach ($fields as $field) {
            $field = wp_parse_args($field, array(
                'type'      => 'text',  // Default type
                'name'      => '',
                'label'     => '',
                'value'     => '',
                'default'   => '',
            ));

            // Get saved value or default
            if (isset($instance[$field['name']])) {
                $field['value'] = $instance[$field['name']];
            } else {
                $field['value'] = $field['default'];
            }

            // Render the field
            $this->field($field);
        }
    }

    /**
     * Outputs the widget settings form in the admin panel.
     *
     * @param array $instance Current widget settings.
     */
    public function form($instance) { ?>
        <div class="sm-news-list-1">
            <?php $this->render_fields($instance); ?>
        </div><?php
    }

    /**
     * Updates the widget settings upon save.
     *
     * @param array $new_instance New settings submitted via the widget form.
     * @param array $old_instance Previous settings stored in the database.
     * @return array Sanitized and updated widget settings.
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;  // Retain existing values

        foreach ($this->get_configs() as $field) {
            $name = $field['name'] ?? '';
            $type = $field['type'] ?? 'text';

            if (isset($new_instance[$name])) {
                switch ($type) {
                    case 'list_cat':
                        // Save as integer if it's a single category
                        $instance[$name] = absint($new_instance[$name]);
                        break;

                    case 'repeatable':
                        // For multiple categories, ensure array
                        $instance[$name] = is_array($new_instance[$name])
                            ? array_map('absint', $new_instance[$name])
                            : [absint($new_instance[$name])];
                        break;

                    case 'checkbox':
                        $instance[$name] = ($new_instance[$name] === 'on') ? 'on' : '';
                        break;

                    case 'text':
                    default:
                        $instance[$name] = sanitize_text_field($new_instance[$name]);
                        break;
                }
            } else {
                $instance[$name] = '';
            }
        }

        return $instance;
    }
}
