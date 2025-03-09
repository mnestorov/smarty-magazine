<?php

function __smarty_magazine_add_meta_tags() {
    if (is_single() || is_singular('news')) { // For posts and custom post type 'news'
        global $post;
        
        $meta_title = get_the_title($post->ID);
        $meta_description = get_the_excerpt($post->ID);
        $meta_url = get_permalink($post->ID);
        $meta_image = get_the_post_thumbnail_url($post->ID, 'full'); // Get full-size featured image
        
        // Fallback image if no featured image is set
        if (!$meta_image) {
            $meta_image = get_template_directory_uri() . '/assets/default-image.jpg'; // Change to your default image path
        }

        ?>
        <meta property="og:type" content="article" />
        <meta property="og:title" content="<?php echo esc_attr($meta_title); ?>" />
        <meta property="og:description" content="<?php echo esc_attr(wp_strip_all_tags($meta_description)); ?>" />
        <meta property="og:url" content="<?php echo esc_url($meta_url); ?>" />
        <meta property="og:image" content="<?php echo esc_url($meta_image); ?>" />
        <meta property="og:image:alt" content="<?php echo esc_attr($meta_title); ?>" />
        <meta property="og:site_name" content="<?php bloginfo('name'); ?>" />

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="<?php echo esc_attr($meta_title); ?>" />
        <meta name="twitter:description" content="<?php echo esc_attr(wp_strip_all_tags($meta_description)); ?>" />
        <meta name="twitter:image" content="<?php echo esc_url($meta_image); ?>" />
        <meta name="twitter:image:alt" content="<?php echo esc_attr($meta_title); ?>" />
        <?php
    }
}
add_action('wp_head', '__smarty_magazine_add_meta_tags');
