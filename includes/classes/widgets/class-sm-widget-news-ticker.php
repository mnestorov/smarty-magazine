<?php
/**
 * News Ticker Widget Class.
 *
 * Displays a news ticker with headlines from recent posts or a specific category.
 * 
 * @since 1.0.0
 * 
 * @package Smarty_Magazine
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class __Smarty_Magazine_News_Ticker extends WP_Widget {
    /**
     * Constructor: Initializes the widget.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    public function __construct() {
        parent::__construct(
            '__smarty_magazine_news_ticker',
            __('SM News Ticker', 'smarty_magazine'),
            array(
                'description' => __('Displays a scrolling news ticker with recent headlines.', 'smarty_magazine')
            )
        );
    }

    /**
     * Outputs the widget content on the front-end.
     * 
     * This method is used to display the widget content on the front-end.
     * 
     * @since 1.0.0
     *
     * @param array $args Display arguments including 'before_title', 'after_title', etc.
     * @param array $instance The widget settings.
     * 
     * @return void
     */
    public function widget($args, $instance) {
        $title           = !empty($instance['title']) ? $instance['title'] : __('Headlines', 'smarty_magazine');
        $show_posts_from = !empty($instance['show_posts_from']) ? $instance['show_posts_from'] : 'recent';
        $post_type       = !empty($instance['post_type']) ? $instance['post_type'] : 'post';
        $category        = !empty($instance['category']) ? $instance['category'] : '';
        $no_of_posts     = !empty($instance['no_of_posts']) ? absint($instance['no_of_posts']) : 5;

        // Set up query parameters
        $query_args = array(
            'post_type'           => $post_type,
            'posts_per_page'      => $no_of_posts,
            'ignore_sticky_posts' => true
        );

        // Determine taxonomy based on post type
        $taxonomy = $post_type === 'news' ? 'news_category' : 'category';

        // If the widget is set to display posts from a specific category
        if ($show_posts_from === 'category' && !empty($category)) {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $category,
                ),
            );
        }

        $news_ticker_posts = new WP_Query($query_args);

        // Output the news ticker
        if ($news_ticker_posts->have_posts()) : ?>
            <div class="bt-news-ticker">
                <?php if (!empty($title)) : ?>
                    <div class="bt-news-ticker-tag d-flex align-items-center">
                        <i class="bi bi-fire me-2"></i> <?php echo esc_html($title); ?>
                    </div>
                <?php endif; ?>
                <ul class="sm-newsticker">
                    <?php while ($news_ticker_posts->have_posts()) : $news_ticker_posts->the_post(); ?>
                        <li>
                            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        <?php else : ?>
            <p><?php _e('Sorry, no posts matched your criteria.', 'smarty_magazine'); ?></p>
        <?php endif; ?>

        <?php wp_reset_postdata();
    }

    /**
     * Outputs the widget settings form in the admin dashboard.
     * 
     * This method is used to display the widget settings form in the admin dashboard.
     * 
     * @since 1.0.0
     *
     * @param array $instance Current settings.
     * 
     * @return void
     */
    public function form($instance) {
        $defaults = array(
            'title'           => __('Headlines', 'smarty_magazine'),
            'show_posts_from' => 'recent',
            'post_type'       => 'post',
            'category'        => '',
            'no_of_posts'     => 5
        );

        $instance = wp_parse_args((array) $instance, $defaults);
        ?>

        <div class="sm-news-ticker-settings">
            <!-- Title Field -->
            <div class="sm-admin-input-wrap">
                <label for="<?php echo $this->get_field_id('title'); ?>">
                    <?php _e('Title', 'smarty_magazine'); ?>
                </label>
                <input type="text" 
                       id="<?php echo $this->get_field_id('title'); ?>" 
                       name="<?php echo $this->get_field_name('title'); ?>" 
                       value="<?php echo esc_attr($instance['title']); ?>" 
                       placeholder="<?php _e('News Ticker Title', 'smarty_magazine'); ?>">
            </div>

            <!-- Post Type Selection -->
            <div class="sm-admin-input-wrap">
                <label><?php _e('Choose Type', 'smarty_magazine'); ?></label><br>
                <label>
                    <input type="radio" 
                           name="<?php echo $this->get_field_name('post_type'); ?>" 
                           value="post" 
                           <?php checked($instance['post_type'], 'post'); ?>>
                    <?php _e('Posts', 'smarty_magazine'); ?>
                </label>
                <label>
                    <input type="radio" 
                           name="<?php echo $this->get_field_name('post_type'); ?>" 
                           value="news" 
                           <?php checked($instance['post_type'], 'news'); ?>>
                    <?php _e('News', 'smarty_magazine'); ?>
                </label>
            </div>

            <!-- Post Source Selection -->
            <div class="sm-admin-input-wrap">
                <label><?php _e('Choose Post Source', 'smarty_magazine'); ?></label><br>
                <label>
                    <input type="radio" 
                           name="<?php echo $this->get_field_name('show_posts_from'); ?>" 
                           value="recent" 
                           <?php checked($instance['show_posts_from'], 'recent'); ?>>
                    <?php _e('Recent Posts', 'smarty_magazine'); ?>
                </label>
                <label>
                    <input type="radio" 
                           name="<?php echo $this->get_field_name('show_posts_from'); ?>" 
                           value="category" 
                           <?php checked($instance['show_posts_from'], 'category'); ?>>
                    <?php _e('Specific Category', 'smarty_magazine'); ?>
                </label>
            </div>

            <!-- Category Selection -->
            <div class="sm-admin-input-wrap">
                <label for="<?php echo $this->get_field_id('category'); ?>">
                    <?php _e('Category', 'smarty_magazine'); ?>
                </label>
                <select id="<?php echo $this->get_field_id('category'); ?>" 
                        name="<?php echo $this->get_field_name('category'); ?>" 
                        class="sm-ticker-category">
                    <option value=""><?php _e('Select a Category', 'smarty_magazine'); ?></option>
                    <?php
                    $taxonomy = $instance['post_type'] === 'news' ? 'news_category' : 'category';
                    $categories = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => false));
                    foreach ($categories as $term) {
                        printf(
                            '<option value="%s" %s>%s</option>',
                            esc_attr($term->term_id),
                            selected($instance['category'], $term->term_id, false),
                            esc_html($term->name)
                        );
                    }
                    ?>
                </select>
            </div>

            <!-- Number of Posts Field -->
            <div class="sm-admin-input-wrap">
                <label for="<?php echo $this->get_field_id('no_of_posts'); ?>">
                    <?php _e('Number of Posts', 'smarty_magazine'); ?>
                </label>
                <input type="number" 
                       id="<?php echo $this->get_field_id('no_of_posts'); ?>" 
                       name="<?php echo $this->get_field_name('no_of_posts'); ?>" 
                       value="<?php echo esc_attr($instance['no_of_posts']); ?>" 
                       min="1" step="1">
            </div>
        </div>

        <?php
    }

    /**
     * Sanitizes and saves widget settings.
     * 
     * This method is used to sanitize and save the widget settings.
     * 
     * @since 1.0.0
     *
     * @param array $new_instance New settings for this instance as input by the user.
     * @param array $old_instance Old settings for this instance.
     * 
     * @return array Updated settings.
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;

        $instance['title']           = sanitize_text_field($new_instance['title']);
        $instance['show_posts_from'] = in_array($new_instance['show_posts_from'], array('recent', 'category')) ? $new_instance['show_posts_from'] : 'recent';
        $instance['post_type']       = in_array($new_instance['post_type'], array('post', 'news')) ? $new_instance['post_type'] : 'post';
        $instance['category']        = intval($new_instance['category']);
        $instance['no_of_posts']     = absint($new_instance['no_of_posts']);

        return $instance;
    }
}
