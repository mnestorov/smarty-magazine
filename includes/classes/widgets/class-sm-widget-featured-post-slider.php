<?php
/**
 * Featured Post Slider Widget Class.
 *
 * Displays a featured news image slider with titles and published dates.
 * 
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class __Smarty_Magazine_Featured_Post_Slider extends WP_Widget {
    /**
     * Constructor: Initializes the widget.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    public function __construct() {
        parent::__construct(
            '__Smarty_Magazine_Featured_Post_Slider',
            __('SM Featured Slider', 'smarty_magazine'),
            array(
                'description' => __('Featured News Image Slider with title and published date', 'smarty_magazine')
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
        $show_posts_from = isset($instance['show_posts_from']) ? $instance['show_posts_from'] : 'recent';
        $category        = isset($instance['category']) ? $instance['category'] : '';
        $no_of_posts     = isset($instance['no_of_posts']) ? $instance['no_of_posts'] : 5;

        // Query posts based on settings
        $query_args = array(
            'post_type'           => 'post',
            'posts_per_page'      => $no_of_posts,
            'ignore_sticky_posts' => true,
        );

        if ($show_posts_from === 'category' && !empty($category)) {
            $query_args['category__in'] = array($category);
        }

        $featured_posts = new WP_Query($query_args);

        if ($featured_posts->have_posts()) {
            echo '<div class="sm-featured-post-slider-wrap">';
            echo '<div class="sm-featured-post-slider"><div class="swiper-wrapper">';
            while ($featured_posts->have_posts()) {
                $featured_posts->the_post();
                if (has_post_thumbnail()) {
                    $this->render_post_slide();
                }
            }
            echo '</div>'; // .swiper-wrapper

            // Navigation arrows
            echo '<div class="swiper-button-next"><i class="bi bi-caret-right"></i></div>';
            echo '<div class="swiper-button-prev"><i class="bi bi-caret-left"></i></div>';
            echo '</div></div>'; // .sm-featured-post-slider, .sm-featured-post-slider-wrap
        } else {
            echo '<p>' . __('Sorry, no posts found in the selected category.', 'smarty_magazine') . '</p>';
        }

        wp_reset_postdata();
    }

    /**
     * Renders a single post slide.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    private function render_post_slide() {
        ?>
        <div class="swiper-slide">
            <div class="sm-featured-posts-wrap">
                <figure class="sm-featured-post-img">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                        <?php the_post_thumbnail('sm-featured-post-medium', array(
                            'title' => esc_attr(get_the_title()),
                            'alt'   => esc_attr(get_the_title()),
                        )); ?>
                    </a>
                </figure>

                <h2>
                    <span class="sm-featured-post-date">
                        <span class="sm-featured-post-month"><?php echo esc_html(get_the_date('M')); ?><br><?php echo esc_html(get_the_date('Y')); ?></span>
                        <span class="sm-featured-post-day"><?php echo esc_html(get_the_date('d')); ?></span>
                    </span>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                </h2>
            </div>
        </div>
        <?php
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
            'title'           => '',
            'show_posts_from' => 'recent',
            'category'        => '',
            'no_of_posts'     => 5,
        );

        $instance = wp_parse_args((array) $instance, $defaults);
        ?>

        <div class="sm-featured-post-slider">
            <div class="sm-admin-input-wrap">
                <label for="<?php echo $this->get_field_id('title'); ?>"><strong><?php _e('Title', 'smarty_magazine'); ?></strong></label>
                <input type="text" 
                       id="<?php echo $this->get_field_id('title'); ?>" 
                       name="<?php echo $this->get_field_name('title'); ?>" 
                       value="<?php echo esc_attr($instance['title']); ?>" 
                       placeholder="<?php _e('Title for Featured Posts', 'smarty_magazine'); ?>">
            </div>

            <div class="sm-admin-input-wrap">
                <label><strong><?php _e('Choose Type', 'smarty_magazine'); ?></strong></label><br>
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
                    <?php _e('Category', 'smarty_magazine'); ?>
                </label>
            </div>

            <div class="sm-admin-input-wrap">
                <label for="<?php echo $this->get_field_id('category'); ?>"><strong><?php _e('Category', 'smarty_magazine'); ?></strong></label>
                <select id="<?php echo $this->get_field_id('category'); ?>" 
                        name="<?php echo $this->get_field_name('category'); ?>">
                    <option value=""><?php _e('Select a Category', 'smarty_magazine'); ?></option>
                    <?php
                    $categories = get_terms(array('taxonomy' => 'category', 'hide_empty' => false));
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

            <div class="sm-admin-input-wrap">
                <label for="<?php echo $this->get_field_id('no_of_posts'); ?>"><strong><?php _e('Number of Posts', 'smarty_magazine'); ?></strong></label>
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
        $instance['category']        = intval($new_instance['category']);
        $instance['no_of_posts']     = absint($new_instance['no_of_posts']);

        return $instance;
    }
}
