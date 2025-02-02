<?php

// Include the TGM Plugin Activation library.
require_once get_template_directory() . '/includes/classes/class-tgm-plugin-activation.php';

if (!function_exists('__smarty_register_required_plugins')) {
    /**
     * Register the required plugins for this theme.
     *
     * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action at priority 10.
     * It defines an array of required and recommended plugins and passes them to the TGMPA library for installation.
     */
    function __smarty_register_required_plugins()
    {
        // Array of plugins to be registered. Each plugin array must have 'name' and 'slug'. 'source' is required if not from the .org repo.
        $plugins = array(
            array(
                'name'     => 'Mega Menu plugin for WordPress',  // The name displayed in the plugins list.
                'slug'     => 'easymega',                        // The slug as used in the WordPress.org repository or a custom source.
                'required' => false,                             // Set to true if the plugin is required, false if recommended.
            ),
        );

        // Configuration settings for TGMPA.
        $config = array(
            'id'           => 'wellness',                 // Unique ID for hashing notices when multiple instances of TGMPA are used.
            'default_path' => '',                         // Default absolute path to bundled plugins (if any).
            'menu'         => 'tgmpa-install-plugins',    // Menu slug for the plugin install screen.
            'has_notices'  => true,                       // Whether to show admin notices.
            'dismissable'  => true,                       // Whether the notice can be dismissed by the user.
            'dismiss_msg'  => '',                         // Message to show if 'dismissable' is set to false.
            'is_automatic' => false,                      // Automatically activate plugins after installation.
            'message'      => '',                         // Message to display before the plugins table.
        );

        // Register the plugins and configuration with TGMPA.
        tgmpa($plugins, $config);
    }
    add_action('tgmpa_register', '__smarty_register_required_plugins');
}
