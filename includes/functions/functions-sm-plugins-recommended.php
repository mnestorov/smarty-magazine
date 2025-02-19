<?php

/**
 * Register the required plugins for this theme.
 * 
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action at priority 10.
 * It defines an array of required and recommended plugins and passes them to the TGMPA library for installation.
 * 
 * @since 1.0.0
 */
require_once get_template_directory() . '/includes/classes/class-tgm-plugin-activation.php';

if (!function_exists('__smarty_register_required_plugins')) {
    /**
     * Register the required plugins for this theme.
     *
     * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action at priority 10.
     * It defines an array of required and recommended plugins and passes them to the TGMPA library for installation.
     * 
     * @since 1.0.0
     * 
     * @return void
     * 
     * @see tgmpa()
     */
    function __smarty_register_required_plugins() {
        // Array of plugins to be registered. Each plugin array must have 'name' and 'slug'. 'source' is required if not from the .org repo.
        $plugins = array(
            array(
                'name'     => 'SM- Crypto Data Generator', 
                'source'   => 'smarty-crypto-data-generator',
                'source'   => 'https://github.com/mnestorov/smarty-wordpress-crypto-data-widgets/archive/refs/heads/main.zip',
                'required' => false,
            ),
            array(
                'name'     => 'SM - RSS Feed Generator',
                'source'   => 'smarty-rss-feed-generator',
                'source'   => 'https://github.com/mnestorov/smarty-rss-feed-generator/archive/refs/heads/main.zip',
                'required' => false,
            ),
            array(
                'name'     => 'SM - DeepL Bridge',
                'source'   => 'smarty-deepl-bridge',
                'source'   => 'https://github.com/mnestorov/smarty-deepl-bridge/archive/refs/heads/main.zip',
                'required' => false,
            ),
            array(
                'name'     => 'SM - Google Translate Bridge',
                'source'   => 'smarty-google-translate-bridge',
                'source'   => 'https://github.com/mnestorov/smarty-google-translate-bridge/archive/refs/heads/main.zip',
                'required' => false,
            ),

            // (Optional) Another plugin from WordPress.org by simply providing the 'slug'
            array(
                'name'      => 'Classic Editor',
                'slug'      => 'classic-editor',
                'required'  => false,
            ),
            array(
                'name'      => 'Classic Widgets',
                'slug'      => 'classic-widgets',
                'required'  => false,
            ),
            array(
                'name'      => 'Loco Translate',
                'slug'      => 'loco-translate',
                'required'  => false,
            ),
            array(
                'name'      => 'Site Kit by Google – Analytics, Search Console, AdSense, Speed',
                'slug'      => 'google-site-kit',
                'required'  => false,
            ),
        );

        // Configuration settings for TGMPA.
        $config = array(
            'id'           => 'smarty-magazine-tgmpa',    // Unique ID for hashing notices when multiple instances of TGMPA are used.
            'default_path' => '',                         // Default absolute path to bundled plugins (if any).
            'menu'         => 'sm-install-plugins',       // Menu slug for the plugin install screen.
            'has_notices'  => true,                       // Whether to show admin notices.
            'dismissable'  => true,                       // Whether the notice can be dismissed by the user.
            'dismiss_msg'  => '',                         // Message to show if 'dismissable' is set to false.
            'is_automatic' => false,                      // Automatically activate plugins after installation.
            'message'      => '',                         // Message to display before the plugins table.

            /*
            * Show a banner or notice about recommended plugins?
            * Customize the messages further.
            */
            'strings'      => array(
                'page_title'                      => __( 'Install Recommended Plugins', 'smarty-magazine`' ),
                'menu_title'                      => __( 'Install Plugins', 'smarty-magazine`' ),
                /* translators: 1: plugin name(s). */
                'installing'                      => __( 'Installing Plugin: %s', 'smarty-magazine`' ),
                'oops'                            => __( 'Something went wrong with the plugin API.', 'smarty-magazine`' ),
                'notice_can_install_required'     => _n_noop(
                    'SmartyMagazine requires the following plugin: %1$s.',
                    'SmartyMagazine requires the following plugins: %1$s.',
                    'smarty-magazine`'
                ),
                'notice_can_install_recommended'  => _n_noop(
                    'SmartyMagazine recommends the following plugin: %1$s.',
                    'SmartyMagazine recommends the following plugins: %1$s.',
                    'smarty-magazine`'
                ),
                'notice_cannot_install'           => _n_noop(
                    'Sorry, but you do not have the correct permissions to install the %s plugin.',
                    'Sorry, but you do not have the correct permissions to install the %s plugins.',
                    'smarty-magazine`'
                ),
                // You can continue customizing the other strings here...
            ),
        );

        // Register the plugins and configuration with TGMPA.
        tgmpa($plugins, $config);
    }
    add_action('tgmpa_register', '__smarty_register_required_plugins');
}