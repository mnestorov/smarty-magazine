<?php
/**
 * Meta Tags & Schema Functions
 * 
 * This file contains functions for:
 * - JSON-LD Schema for Homepage
 * - OpenGraph Meta Tags for Posts
 * - Twitter Meta Tags
 * 
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */

if (!function_exists('__smarty_magazine_homepage_schema_markup')) {
    /**
     * Add JSON-LD Schema markup to the homepage.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    function __smarty_magazine_homepage_schema_markup() {
        if (!is_front_page() && !is_home()) {
            return; // Stop execution if not on the homepage
        }
    
        // Get site details dynamically
        $site_name = get_bloginfo('name');
        $site_url = home_url();
        $site_description = get_bloginfo('description');
    
        // Get custom logo
        $logo_id = get_theme_mod('custom_logo');
        $logo_url = $logo_id ? wp_get_attachment_url($logo_id) : '';
    
        // Define social media links (modify as needed)
        $social_links = [
            "https://x.com/cryptopointbg",
            "https://www.facebook.com/cryptopointbg",
            "https://www.youtube.com/@CryptoPoint-Bulgaria"
        ];
    
        // Define site navigation links
        $navigation_items = [
            ["name" => __("Home",              "smarty_magazine"), "url" => $site_url,                        "description" => $site_description                                                           ],
            ["name" => __("News",              "smarty_magazine"), "url" => $site_url . "/news",              "description" => __("Latest crypto news and analysis.", "smarty_magazine")                   ],
            ["name" => __("Cryptocurrencies",  "smarty_magazine"), "url" => $site_url . "/cryptocurrencies",  "description" => __("Current crypto prices today in real time.", "smarty_magazine")          ],
            ["name" => __("Crypto Exchanges",  "smarty_magazine"), "url" => $site_url . "/crypto-exchanges",  "description" => __("Top crypto exchanges for buying cryptocurrencies.", "smarty_magazine")  ],
            ["name" => __("Crypto Treasuries", "smarty_magazine"), "url" => $site_url . "/crypto-treasuries", "description" => __("Crypto assets and bonds of public companies.", "smarty_magazine")       ],
            ["name" => __("Crypto Dictionary", "smarty_magazine"), "url" => $site_url . "/crypto-dictionary", "description" => __("Crypto Dictionary - crypto terms and their meaning.", "smarty_magazine")],
        ];
    
        // Build Organization Schema
        $organization_schema = [
            "@context"    => "https://schema.org",
            "@type"       => "Organization",
            "name"        => $site_name,
            "url"         => $site_url,
            "description" => $site_description,
            "email"       => "hi@" . parse_url($site_url, PHP_URL_HOST),
            "telephone"   => "+359 876 700 417",
            "sameAs"      => $social_links
        ];
    
        // Add logo only if it exists
        if (!empty($logo_url)) {
            $organization_schema["logo"] = [
                "@type"  => "ImageObject",
                "url"    => $logo_url,
                "width"  => 250,
                "height" => 60
            ];
        }
    
        // Build ItemList (Site Navigation Schema)
        $item_list_schema = [
            "@context"        => "https://schema.org",
            "@type"           => "ItemList",
            "itemListElement" => []
        ];
    
        foreach ($navigation_items as $index => $item) {
            $item_list_schema["itemListElement"][] = [
                "@type"       => "SiteNavigationElement",
                "position"    => $index + 1,
                "name"        => $item["name"],
                "description" => $item["description"],
                "url"         => $item["url"]
            ];
        }
    
        // Build WebSite Schema (with SearchAction)
        $website_schema = [
            "@context"  => "https://schema.org",
            "@type"     => "WebSite",
            "url"       => $site_url,
            "publisher" => [
                "@type" => "Organization",
                "name"  => $site_name
            ],
            "potentialAction" => [
                "@type"           => "SearchAction",
                "target"          => [
                    "@type"       => "EntryPoint",
                    "urlTemplate" => $site_url . "/?s={search_term_string}"
                ],
                "query-input"       => [
                    "@type"         => "PropertyValueSpecification",
                    "valueRequired" => "http://schema.org/True",
                    "valueName"     => "search_term_string"
                ]
            ]
        ];
    
        // Add publisher logo if it exists
        if (!empty($logo_url)) {
            $website_schema["publisher"]["logo"] = [
                "@type"  => "ImageObject",
                "url"    => $logo_url,
                "width"  => 250,
                "height" => 60
            ];
        }
    
        // Output Schema as JSON-LD
        echo '<script type="application/ld+json">' . json_encode($organization_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
        echo '<script type="application/ld+json">' . json_encode($item_list_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
        echo '<script type="application/ld+json">' . json_encode($website_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    }

    // Hook into wp_head (runs only on homepage)
    add_action('wp_head', '__smarty_magazine_homepage_schema_markup');
}

if (!function_exists('__smarty_magazine_add_social_meta_tags')) {
    /**
     * Add OpenGraph and Twitter meta tags for homepage, posts, and news.
     * 
     * @since 1.0.0
     */
    function __smarty_magazine_add_social_meta_tags() {
        // Default values for homepage
        $meta_title = get_bloginfo('name');
        $meta_url = home_url();
        $meta_description = get_bloginfo('description');
        
        // Get custom logo or fallback image
        $logo_id = get_theme_mod('custom_logo');
        $meta_image = $logo_id ? wp_get_attachment_url($logo_id) : esc_url(get_template_directory_uri() . '/assets/default-image.jpg');

        // If viewing a single post or news article, override with post-specific values
        if (is_single() || is_singular('news')) {
            global $post;

            $meta_title = esc_html(get_the_title($post->ID));
            $meta_url = esc_url(get_permalink($post->ID));
            $meta_image = get_the_post_thumbnail_url($post->ID, 'full') ?: $meta_image; // Use featured image or fallback
            $meta_description = get_the_excerpt($post->ID);

            // If excerpt is empty, generate one from content
            if (empty($meta_description)) {
                $meta_description = wp_trim_words(strip_shortcodes(get_the_content($post->ID)), 30, '...');
            }
        }

        ?>
        <!-- OpenGraph Meta Tags -->
        <meta property="og:type" content="<?php echo is_singular() ? 'article' : 'website'; ?>" />
        <meta property="og:title" content="<?php echo esc_attr($meta_title); ?>" />
        <meta property="og:description" content="<?php echo esc_attr(wp_strip_all_tags($meta_description)); ?>" />
        <meta property="og:url" content="<?php echo esc_url($meta_url); ?>" />
        <meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
        
        <?php if ($meta_image) : ?>
            <meta property="og:image" content="<?php echo esc_url($meta_image); ?>" />
            <meta property="og:image:alt" content="<?php echo esc_attr($meta_title); ?>" />
        <?php endif; ?>

        <!-- Twitter Meta Tags -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="<?php echo esc_attr($meta_title); ?>" />
        <meta name="twitter:description" content="<?php echo esc_attr(wp_strip_all_tags($meta_description)); ?>" />
        <meta name="twitter:image" content="<?php echo esc_url($meta_image); ?>" />
        <meta name="twitter:image:alt" content="<?php echo esc_attr($meta_title); ?>" />
        <?php
    }

    // Hook into wp_head to output the meta tags
    add_action('wp_head', '__smarty_magazine_add_social_meta_tags');
}

if (!function_exists('__smarty_magazine_add_dictionary_schema')) {
    /**
     * Add JSON-LD Schema markup to the dictionary page.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    function __smarty_magazine_add_dictionary_schema() {
        if (is_page_template('template-dictionary.php')) { // template-dictionary.php is the template file for the dictionary page
            $dictionary_items = get_posts(array(
                'post_type'      => 'dictionary',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'orderby'        => 'title',
                'order'          => 'ASC',
            ));
    
            $terms = array();
            foreach ($dictionary_items as $item) {
                $title = get_the_title($item->ID);
                $first_letter = mb_strtoupper(mb_substr($title, 0, 1, 'UTF-8'), 'UTF-8'); // Get first letter
                $anchor_url = get_permalink(get_the_ID()) . '#letter-' . esc_attr($first_letter); // Anchor-based URL
    
                $terms[] = array(
                    "@type"       => "DefinedTerm",
                    "name"        => $title,
                    "description" => wp_strip_all_tags(get_the_excerpt($item->ID)),
                    "url"         => $anchor_url, // Anchor link instead of single page
                );
            }
    
            $schema_data = array(
                "@context"       => "https://schema.org",
                "@type"          => "DefinedTermSet",
                "name"           => get_the_title(),
                "description"    => get_the_excerpt(),
                "url"            => get_permalink(), // The main dictionary page
                "hasDefinedTerm" => $terms
            );
    
            echo '<script type="application/ld+json">' . json_encode($schema_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
        }
    }
    add_action('wp_head', '__smarty_magazine_add_dictionary_schema');
}
