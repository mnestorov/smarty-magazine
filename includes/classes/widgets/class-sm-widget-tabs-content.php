<?php
/**
 * Tabs Content Widget.
 *
 * This class serves as a base widget for displaying post content in various tabbed layouts.
 * It provides multiple layout configurations and dynamic content querying features.
 * 
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class __Smarty_Magazine_Tabs_Content extends WP_Widget {
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
     * @since 1.0.0
     *
     * @param string $id_base         Base ID for the widget.
     * @param string $name            Name of the widget.
     * @param array  $widget_options  Options for the widget's appearance and behavior.
     * @param array  $control_options Options for widget controls.
     * 
     * @return void
     */
    public function __construct($id_base = '', $name = '', $widget_options = array(), $control_options = array()) {
        parent::__construct($id_base, $name, $widget_options, $control_options);
    }

    /**
     * Queries posts based on widget settings.
     *
     * Constructs a WP_Query object based on the widget's configuration, handling categories,
     * post limits, ordering, and other criteria.
     * 
     * @since 1.0.0
     *
     * @param array $instance Widget settings.
     * 
     * @return WP_Query The constructed query object.
     */
    public function query($instance = array()) {
        $instance = wp_parse_args($instance, array(
            'post_type' => 'post' // Default post type
        ));
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
            $instance['category'] = [absint($instance['category'])];
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

        // Determine post type and taxonomy
        $post_type = $instance['post_type'] === 'news' ? 'news' : 'post';
        $taxonomy = $post_type === 'news' ? 'news_category' : 'category';

        $args = array(
            'post_type'           => $post_type,
            'posts_per_page'      => $no_of_posts,
            'ignore_sticky_posts' => true,
            'paged'               => absint($instance['paged'] ?? 0),
        );

        // Use tax_query instead of category__in for more flexibility with custom taxonomies
        if (!empty($viewing)) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $viewing,
                ),
            );
        }

        if (in_array($instance['orderby'] ?? '', ['date', 'title', 'rand', 'comment_count'])) {
            $args['orderby'] = $instance['orderby'];
        }

        if (in_array($instance['order'] ?? '', ['asc', 'desc'])) {
            $args['order'] = strtoupper($instance['order']);
        }

        return new WP_Query(apply_filters('__smarty_magazine_widget_content_args', $args, $instance, $this));
    }

    /**
     * Prepares widget instance settings by ensuring defaults are applied.
     * 
     * This method ensures that all expected settings are present in the instance data.
     * 
     * @since 1.0.0
     *
     * @param array $instance Current widget settings.
     * 
     * @return array Prepared settings with defaults.
     */
    public function setup_instance($instance) {
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

        // Ensure 'post_type' is set
        if (!isset($prepared_instance['post_type'])) {
            $prepared_instance['post_type'] = 'post';
        }

        return $prepared_instance;
    }

    /**
     * Outputs the widget content on the front-end.
     * 
     * This method is used to display the widget content on the front-end.
     * 
     * @since 1.0.0
     *
     * @param array $args     Display arguments including 'before_widget', 'after_widget', etc.
     * @param array $instance The settings for the current widget instance.
     * 
     * @return void
     */
    public function widget($args, $instance) {
        $args = wp_parse_args($args, array(
            'before_widget' => '<div class="widget %s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));

        // Extract existing classes from before_widget
        preg_match('/class=["\'](.*?)["\']/i', $args['before_widget'], $matches);
        $existing_classes = isset($matches[1]) ? explode(' ', $matches[1]) : array('widget');

        if (!isset($instance['__setup_data']) || $instance['__setup_data'] !== false) {
            $instance = $this->setup_instance($instance);
        }

        $title = !empty($instance['title']) ? $instance['title'] : __('Default Title', 'smarty_magazine');
        unset($instance['title']);

        $query = $this->query($instance);
        $instance['_layout'] = $this->layout;

        // Handle spacing options
        $top_spacing = !empty($instance['top_spacing']) && $instance['top_spacing'] === 'yes' ? 'mt-5' : '';
        $bottom_spacing = !empty($instance['bottom_spacing']) && $instance['bottom_spacing'] === 'yes' ? 'mb-5' : '';

        // Merge classes
        $widget_classes = array_merge($existing_classes, array('sm-news-list-' . $this->layout));
        if ($this->layout_class) {
            $widget_classes[] = $this->layout_class;
        }
        if ($top_spacing) {
            $widget_classes[] = $top_spacing;
        }
        if ($bottom_spacing) {
            $widget_classes[] = $bottom_spacing;
        }

        // Remove duplicates and empty values
        $widget_classes = array_filter(array_unique($widget_classes));

        // Replace or add class attribute in before_widget
        $before_widget = preg_replace(
            '/class=["\'].*?["\']/i',
            'class="' . esc_attr(implode(' ', $widget_classes)) . '"',
            $args['before_widget']
        );
        if (!preg_match('/class=["\'].*?["\']/i', $before_widget)) {
            // If no class attribute exists, add one
            $before_widget = str_replace('>', ' class="' . esc_attr(implode(' ', $widget_classes)) . '">', $args['before_widget']);
        }

        // Output the widget
        echo $before_widget;

        $taxonomy = $instance['post_type'] === 'news' ? 'news_category' : 'category';
        ?>
        <div class="news-layout-tabs sm-news-layout-wrap">
            <?php if ($title) : ?>
                <div class="widget-title filter-inside" style="display: flex; justify-content: space-between; align-items: center;">
                    <?php echo $args['before_title'] . esc_html($title) . $args['after_title']; ?>
                    <?php
                    $first_category = is_array($instance['category']) ? reset($instance['category']) : $instance['category'];
                    if ($term = get_term($first_category, $taxonomy)) :
                    ?>
                        <span class="view-all-link">
                            <a href="<?php echo esc_url(get_term_link($term)); ?>">
                                <?php esc_html_e('[ View All ]', 'smarty_magazine'); ?>
                            </a>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="news-tab-layout sm-news-layout<?php echo esc_attr($this->layout); ?>">
                <div class="news-tab-posts">
                    <?php
                    if ($query->have_posts()) {
                        $layout_method = 'layout_' . $this->layout;
                        if (method_exists($this, $layout_method)) {
                            $this->$layout_method($query);
                        } else {
                            $this->layout_content($query);
                        }
                    } else {
                        $this->not_found();
                    }
                    ?>
                </div>
            </div>
            <?php wp_reset_postdata(); ?>
        </div>
        <?php
        echo $args['after_widget'];
    }

    /**
     * Default content layout for displaying posts.
     * 
     * This method is used to display posts in a default layout when no specific layout is defined.
     * 
     * @since 1.0.0
     *
     * @param WP_Query $query The query object containing posts to display.
     * 
     * @return void
     */
    public function layout_content($query) {
        while ($query->have_posts()) : $query->the_post(); ?>
            <div class="sm-news-post">
                <figure class="sm-news-post-img">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php 
                        the_post_thumbnail('sm-featured-post-large', array(
                            'title' => esc_attr(get_the_title()),
                            'alt'   => esc_attr(get_the_title()),
                        ));
                        ?>
                    <?php endif; ?>
                    <a href="<?php echo esc_url(get_permalink()); ?>" rel="bookmark">
                        <span class="search-icon"></span>
                    </a>
                </figure>
                <div class="sm-news-post-content">
                    <div class="sm-news-post-meta">
                        <span class="sm-news-post-date"><i class="bi bi-calendar"></i> <?php echo esc_html(get_the_date()); ?></span>
                        <!--<span class="sm-news-post-comments"><i class="bi bi-chat"></i> <?php //comments_number(__('No Responses', 'smarty_magazine'), __('One Response', 'smarty_magazine'), __('% Responses', 'smarty_magazine')); ?></span> -->
                    </div>
                    <h3>
                        <a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(wp_trim_words(get_the_title(), 15, '...')); ?></a>
                    </h3>
                    <div class="sm-news-post-desc"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 50, '...')); ?></div>
                </div>
            </div>
            <?php endwhile;
    }

    /**
     * Displays a message when no posts are found.
     * 
     * This method is used to display a message when no posts are found in the selected category.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    public function not_found() {
        echo '<p class="posts-not-found">' . esc_html__('Sorry, no posts found in selected category.', 'smarty_magazine') . '</p>';
    }

    /**
     * Retrieves the widget's configurable fields.
     * 
     * This method is used to define the fields that can be configured in the widget settings.
     * 
     * @since 1.0.0
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

    /**
     * Ends the layout display.
     * 
     * This method is used to reset the post data after displaying the layout content.
     * 
     * @since 1.0.0
     * 
     * @param WP_Query $query The query object containing posts to display.
     * 
     * @return void
     */
    public function end_layout($query) {
        wp_reset_postdata();
    }

    /**
     * Layout 1 for displaying posts.
     * 
     * This method is used to display posts in a specific layout style.
     * 
     * @since 1.0.0
     * 
     * @param WP_Query $query The query object containing posts to display.
     * 
     * @return void
     */
    public function layout_1($query) {
        $this->layout_content($query);
    }

    /**
     * Layout 2 for displaying posts.
     * 
     * This method is used to display posts in a specific layout style.
     * 
     * @since 1.0.0
     * 
     * @param WP_Query $query The query object containing posts to display.
     * 
     * @return void
     */
    public function layout_2($query) {
        global $post;
        while ($query->have_posts()) { $query->the_post(); ?>
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
                </div>
            </div><?php
        };
        $this->end_layout($query);
    }

    /**
     * Layout 3 for displaying posts.
     * 
     * This method is used to display posts in a specific layout style.
     * 
     * @since 1.0.0
     * 
     * @param WP_Query $query The query object containing posts to display.
     * 
     * @return void
     */
    public function layout_3($query) {
        while ($query->have_posts()) { $query->the_post(); ?>
            <div class="sm-news-post">
                <figure class="sm-news-post-img">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php
                        the_post_thumbnail('sm-featured-post-large', array(
                            'title' => esc_attr(get_the_title()),
                            'alt'   => esc_attr(get_the_title()),
                        ));
                        ?>
                    <?php endif; ?>
                    <a href="<?php echo esc_url(get_permalink()); ?>" rel="bookmark"> <span class="search-icon"></span></a>
                </figure>
                <div class="sm-news-post-content">
                    <div class="sm-news-post-meta">
                        <span class="sm-news-post-date"><i class="bi bi-calendar"></i> <?php the_time(get_option('date_format')); ?></span>
                        <!--<span class="sm-news-post-comments"><i class="bi bi-chat"></i> <?php //comments_number(__('No Responses', 'smarty_magazine'), __('One Response', 'smarty_magazine'), __('% Responses', 'smarty_magazine')); ?></span>-->
                    </div>
                    <h3>
                        <a href="<?php esc_url(the_permalink()); ?>" title="<?php the_title_attribute(); ?>">
                            <?php echo wp_trim_words(get_the_title(), 10, '...'); ?>
                        </a>
                    </h3>
                    <!--
                    <div class="sm-news-post-desc">
                        <?php
                        //$excerpt = get_the_excerpt();
                        //$limit = "210";
                        //$pad = "...";
                        //if (strlen($excerpt) <= $limit) {
                            //echo esc_html($excerpt);
                        //} else {
                            //$excerpt = substr($excerpt, 0, $limit) . $pad;
                            //echo esc_html($excerpt);
                        //}
                        ?>
                    </div>
                    -->
                </div>
                <div class="clearfix"></div>
            </div><?php 
        }
        $this->end_layout($query);
    }

    /**
     * Layout 4 for displaying posts.
     * 
     * This method is used to display posts in a specific layout style.
     * 
     * @since 1.0.0
     * 
     * @param WP_Query $query The query object containing posts to display.
     * 
     * @return void
     */
    public function layout_4($query) {
        $n = count($query->posts);
        global $post;

        $i = 0;
        while ($query->have_posts()) { $query->the_post();
            $i++;
            $c = 'sm-news-post';
            if ($n % 2 == 0) {
                if ($i == $n) {
                    $c .= ' last';
                } else if ($i == $n - 1) {
                    $c .= ' e-last';
                }
            } else {
                if ($i == $n) {
                    $c .= ' last';
                }
            }
            ?>
            <div class="<?php echo esc_attr($c); ?>">
                <figure class="sm-news-post-img">
                    <?php
                    if (has_post_thumbnail()) :
                        $image = '';
                        $title_attribute = get_the_title($post->ID);
                        $image .= '<a href="' . esc_url(get_permalink()) . '" title="' . esc_html(the_title('', '', false)) . '">';
                        $image .= get_the_post_thumbnail($post->ID, 'sm-featured-post-small', array('title' => esc_attr($title_attribute), 'alt' => esc_attr($title_attribute))) . '</a>';
                        echo $image;
                    endif;
                    ?>
                    <a href="<?php echo esc_url(get_permalink()); ?>" rel="bookmark"> <span class="search-icon"></span></a>
                </figure>
                <div class="sm-news-post-content">
                    <div class="sm-news-post-meta">
                        <span class="sm-news-post-date"><i class="bi bi-calendar"></i> <?php the_time(get_option('date_format')); ?></span>
                        <!--<span class="sm-news-post-comments"><i class="bi bi-chat"></i> <?php //comments_number(__('No Responses', 'smarty_magazine'), __('One Response', 'smarty_magazine'), __('% Responses', 'smarty_magazine')); ?></span>-->
                    </div>
                    <h3><a href="<?php esc_url(the_permalink()); ?>" title="<?php the_title_attribute(); ?>"> <?php echo wp_trim_words(get_the_title(), 10, '...'); ?></a></h3>
                </div>
            </div><?php
        };
        $this->end_layout($query);
    }

    /**
     * Render the form field based on its type.
     * 
     * This method is used to render the form field based on the field type.
     * 
     * @since 1.0.0
     *
     * @param array $field Field configuration.
     * 
     * @return void
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

            default: ?>
                <p>
                    <label for="<?php echo $id; ?>"><?php echo esc_html($field['label']); ?></label>
                    <input class="widefat" id="<?php echo $id; ?>" name="<?php echo $name; ?>" type="text" value="<?php echo $value; ?>">
                </p><?php
                break;
        }
    }

    /**
     * Renders the widget settings form in the admin panel.
     * 
     * @since 1.0.0
     * 
     * @param array $instance
     * 
     * @return void
     */
    public function render_fields($instance = array()) {
        $fields = $this->get_configs();
        foreach ($fields as $field) {
            $field = wp_parse_args($field, array(
                'type'      => 'text',
                'name'      => '',
                'label'     => '',
                'value'     => '',
                'default'   => '',
            ));

            if (isset($instance[$field['name']])) {
                $field['value'] = $instance[$field['name']];
            } else {
                $field['value'] = $field['default'];
            }

            $this->field($field);
        }
    }

    /**
     * Outputs the widget settings form in the admin panel.
     * 
     * This method is used to display the widget settings form in the admin panel.
     * 
     * @since 1.0.0
     *
     * @param array $instance Current widget settings.
     * 
     * @return void
     */
    public function form($instance) { ?>
        <div class="sm-news-list-1">
            <?php $this->render_fields($instance); ?>
        </div><?php
    }

    /**
     * Updates the widget settings upon save.
     * 
     * This method is used to update the widget settings upon saving changes.
     * 
     * @since 1.0.0
     *
     * @param array $new_instance New settings submitted via the widget form.
     * @param array $old_instance Previous settings stored in the database.
     * 
     * @return array Sanitized and updated widget settings.
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;

        foreach ($this->get_configs() as $field) {
            $name = $field['name'] ?? '';
            $type = $field['type'] ?? 'text';

            if (isset($new_instance[$name])) {
                switch ($type) {
                    case 'list_cat':
                        $instance[$name] = absint($new_instance[$name]);
                        break;

                    case 'repeatable':
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
