<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div class="sm-body-wrap<?php if (get_theme_mod('__smarty_magazine_default_layout', 0) != 'wide_layout') : ?> sm-boxed<?php endif; ?>">
		<?php $sticky_mobile_class = get_theme_mod('__smarty_magazine_sticky_mobile_menu', 1) ? 'sticky-top ' : ''; ?>
        <!-- Top Bar -->
        <?php if (has_nav_menu('top-bar-menu') || get_theme_mod('__smarty_magazine_hide_date_setting', 1) == '' || is_active_sidebar('sm-top-bar-search') || is_active_sidebar('sm-top-bar-social')) : ?>
            <div class="sm-top-bar <?php echo esc_attr($sticky_mobile_class); ?>">
                <div class="container">
                    <div class="row align-items-center justify-content-between">
                        <!-- Left Section: Navigation + Date -->
                        <div class="col-lg-9 col-md-8 col-sm-8 col-8">
                            <div class="sm-bar-left d-flex justify-content-start align-items-center">
                                <!-- Offcanvas Menu Icon (Always Visible) -->
                                <button class="sm-sec-nav navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#responsiveOffcanvasMenu" aria-controls="responsiveOffcanvasMenu">
                                    <i class="bi bi-list"></i>
                                </button>
								
                                <!-- Date (Always Visible if Enabled) -->
                                <?php if (!get_theme_mod('__smarty_magazine_hide_date_setting', 1)) : ?>
                                    <div class="sm-date bg-dark text-white rounded-1 px-2 d-lg-none">
                                        <p><?php echo date_i18n('l, j F Y', time()); ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php if (is_active_sidebar('sm-top-bar-shortcode')) : ?>
                                    <div class="sm-shortcodes me-4 d-none d-lg-block">
                                        <p><?php dynamic_sidebar('sm-top-bar-shortcode'); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Right Section: Search & Social Icons -->
                        <div class="col-lg-3 col-md-4 col-sm-4 col-4">
                            <div class="sm-top-social d-flex justify-content-end align-items-center">
                                <?php if (is_active_sidebar('sm-top-bar-search')) : ?>
                                    <span class="sm-search-icon">
                                        <a data-bs-toggle="offcanvas" href="#offcanvasSearch" role="button" aria-controls="offcanvasSearch"><i class="bi bi-search"></i></a>
                                    </span>
                                <?php endif; ?>
                                <?php if (is_active_sidebar('sm-top-bar-social')) : ?>
                                    <span class="sm-social-icons-lg d-none d-sm-inline">
                                        <?php dynamic_sidebar('sm-top-bar-social'); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

		<!-- Offcanvas Search Bar -->
        <div class="offcanvas offcanvas-top sm-height-offcanvas" data-bs-backdrop="true" tabindex="-1" id="offcanvasSearch" aria-labelledby="offcanvasSearchLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasSearchLabel"><?php _e('Search', 'smarty_magazine'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <?php if (is_active_sidebar('sm-top-bar-search')) : ?>
                    <div class="sm-search-wrap">
                        <?php dynamic_sidebar('sm-top-bar-search'); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Offcanvas Menu (Visible on Desktop and Mobile) -->
        <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="true" tabindex="-1" id="responsiveOffcanvasMenu" aria-labelledby="responsiveOffcanvasMenuLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="responsiveOffcanvasMenuLabel"><?php _e('Menu', 'smarty_magazine'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body">
                <!-- Top-Bar Menu for Desktop -->
                <?php if (has_nav_menu('top-bar-menu')) : ?>
                    <nav class="d-none d-lg-block">
                        <?php 
                        wp_nav_menu(array(
                            'theme_location' => 'top-bar-menu', 
                            'menu_id'        => 'desktop-top-bar-menu',
                            'menu_class'     => 'navbar-nav me-auto mb-2 mb-lg-0'
                        )); 
                        ?>
                    </nav>
                <?php endif; ?>

                <!-- Primary Menu for Mobile -->
                <?php if (has_nav_menu('primary')) : ?>
                    <nav class="d-lg-none">
                        <?php 
                        wp_nav_menu(array(
                            'theme_location' => 'primary', 
                            'menu_id'        => 'mobile-primary-menu',
                            'menu_class'     => 'navbar-nav'
                        )); 
                        ?>
                    </nav>
                <?php endif; ?>
            </div>
        </div>

        <!-- Header Section -->
        <header class="sm-header">
            <div class="container py-2">
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <div class="sm-logo">
                            <?php if (function_exists('get_custom_logo') && has_custom_logo()) { the_custom_logo(); } ?>
                            <?php if (is_front_page() || is_home()) : ?>
                                <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
                            <?php else: ?>
                                <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
                            <?php endif; ?>
                            <?php $description = get_bloginfo('description', 'display'); ?>
                            <?php if ($description || is_customize_preview()) : ?>
                                <p class="site-description"><?php echo $description; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Ads Section -->
                    <div class="col-lg-8 col-md-8">
                        <div class="sm-top-ads">
                            <?php dynamic_sidebar('sm-header-ads728x90'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Navigation -->
        <nav class="sm-menu-bar<?php if (get_theme_mod('__smarty_magazine_sticky_menu', 0) == 1) { ?> sm-sticky<?php } ?>" style="<?php if (get_theme_mod('__smarty_magazine_custom_shortcode', 0) != 1) { echo 'margin-bottom: 0;'; } else { echo 'margin-bottom: 30px;'; } ?>">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="sm-main-menu">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'primary',
                                'menu_id'        => 'primary-menu',
                                'menu_class'     => 'sm-nav-menu'
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Front Page Shortcode -->
        <?php if (is_front_page()) : ?>
            <div class="container-fluid px-0 mb-4">
                <div class="row">
                    <div class="col-12">
                        <?php
                        $header_shortcode = get_theme_mod('__smarty_magazine_header_shortcode', '');
                        if (!empty($header_shortcode)) {
                            echo do_shortcode(esc_html($header_shortcode));
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php elseif (!is_front_page() && !is_home()) : ?>
            <!-- Breadcrumbs -->
            <div class="sm-breadcrumbs">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <?php __smarty_magazine_breadcrumb(); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>