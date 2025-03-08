<?php

/**
 * Add theme dashboard page
 *
 * This file adds a custom dashboard page for the Smarty Magazine theme.
 *
 * @package SmartyMagazine
 */

if (!function_exists('__smarty_magazine_theme_info')) {
    /**
     * Hook into the 'admin_menu' action to add the theme dashboard page.
     *
     * @return void
     */
    function __smarty_magazine_theme_info() {
        $theme_data = wp_get_theme();
        add_theme_page(
            sprintf(esc_html__('%s Dashboard', 'smarty_magazine'), $theme_data->Name),
            esc_html__('Theme Options', 'smarty_magazine'),
            'edit_theme_options',
            'smarty_magazine',
            '__smarty_magazine_theme_info_page'
        );
    }
    add_action('admin_menu', '__smarty_magazine_theme_info');
}

if (!function_exists('__smarty_magazine_admin_scripts')) {
    /**
     * Enqueue scripts and styles for the theme info admin page.
     *
     * @param string $hook The current admin page hook.
     * @return void
     */
    function __smarty_magazine_admin_scripts($hook) {
        if ($hook === 'widgets.php' || $hook === 'appearance_page_smarty_magazine') {
            wp_enqueue_style('sm-admin-css', get_template_directory_uri() . '/assets/css/sm-admin.css');
        }
    }
    add_action('admin_enqueue_scripts', '__smarty_magazine_admin_scripts');
}

if (!function_exists('__smarty_magazine_theme_info_page')) {
    /**
     * Render the theme info page content.
     *
     * This function outputs the HTML for the theme info page.
     *
     * @return void
     */
    function __smarty_magazine_theme_info_page() {
        $theme_data = wp_get_theme();
        $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : null; ?>
        <div class="wrap about-wrap theme_info_wrapper">
            <h1><?php printf(esc_html__('%1$s - Version %2$s', 'smarty_magazine'), esc_html($theme_data->Name), esc_html($theme_data->Version)); ?></h1>
            <div class="about-text">
                <?php esc_html_e('Smarty Magazine is a professional WordPress News and Magazine theme built with Bootstrap. It is fully responsive, customizable via the theme customizer, and includes multiple layouts, widgets, and advertisement support.', 'smarty_magazine'); ?>
            </div>
            <a target="_blank" href="#" class="smartystudio-badge wp-badge">
                <span>Smarty Studio</span>
            </a>
            <h2 class="nav-tab-wrapper">
                <a href="?page=smarty_magazine" class="nav-tab<?php echo is_null($tab) ? ' nav-tab-active' : ''; ?>"><?php esc_html_e('General', 'smarty_magazine'); ?></a>
            </h2>
            <?php if (is_null($tab)) : ?>
                <div class="theme_info info-tab-content">
                    <div class="theme_info_column clearfix">
                        <div class="theme_info_left">
                            <div class="theme_link">
                                <h3><?php esc_html_e('Theme Customizer', 'smarty_magazine'); ?></h3>
                                <p class="about">
                                    <?php printf(esc_html__('%s supports the Theme Customizer for all settings. Click "Customize" to begin.', 'smarty_magazine'), esc_html($theme_data->Name)); ?>
                                </p>
                                <p>
                                    <a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="button button-primary">
                                        <?php esc_html_e('Customize', 'smarty_magazine'); ?>
                                    </a>
                                </p>
                            </div>
                            <div class="theme_link">
                                <h3><?php esc_html_e('Theme Documentation', 'smarty_magazine'); ?></h3>
                                <p class="about">
                                    <?php printf(esc_html__('Need help setting up %s? Check our documentation.', 'smarty_magazine'), esc_html($theme_data->Name)); ?>
                                </p>
                                <p>
                                    <a href="<?php echo esc_url('https://github.com/mnestorov/smarty-magazine'); ?>" target="_blank" class="button button-secondary">
                                        <?php esc_html_e('Documentation', 'smarty_magazine'); ?>
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="theme_info_right">
                            <img src="<?php echo esc_url(get_template_directory_uri() . '/screenshot.png'); ?>" alt="<?php esc_attr_e('Theme Screenshot', 'smarty_magazine'); ?>" />
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div><?php
    }
}