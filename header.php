<?php
/**
 * The header for the theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */
?>

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
		<?php if (has_nav_menu('top-bar-menu') || get_theme_mod('__smarty_magazine_hide_date_setting', 1) == ''|| is_active_sidebar('sm-top-bar-search') || is_active_sidebar('sm-top-bar-social')) : ?>
			<div class="sm-top-bar">
				<div class="container">
					<div class="row align-items-center justify-content-between">
						<!-- Left Section: Navigation + Date -->
						<div class="col-lg-6 col-md-6 col-sm-8 col-8">
							<div class="sm-bar-left d-flex align-items-center">
								<?php if (has_nav_menu('top-bar-menu')) : ?>
									<nav class="sm-sec-menu transition35">
										<?php wp_nav_menu(array('theme_location' => 'top-bar-menu', 'menu_id' => 'top-bar-menu')); ?>
									</nav>
									<div class="sm-sec-nav ms-2">
										<i class="bi bi-list"></i>
									</div>
								<?php endif; ?>

								<?php if (!get_theme_mod('__smarty_magazine_hide_date_setting', 1)) : ?>
									<div class="sm-date ms-3">
										<p><?php echo date_i18n('l, j F Y', time()); ?></p>
									</div>
								<?php endif; ?>
							</div>
						</div>
						<!-- Right Section: Search & Social Icons -->
						<div class="col-lg-6 col-md-6 col-sm-4 col-4">
							<div class="sm-top-social d-flex justify-content-end align-items-center">
								<?php if (is_active_sidebar('sm-top-bar-search')) : ?>
									<span class="sm-search-icon me-2">
										<a><i class="bi bi-search transition35"></i></a>
									</span>
								<?php endif; ?>
								<?php if (is_active_sidebar('sm-top-bar-social')) : ?>
									<span class="sm-social-trigger transition35 me-2">
										<i class="bi bi-share transition35"></i>
									</span>
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
		<?php if (is_active_sidebar('sm-top-bar-search')) : ?>
			<div class="sm-search-bar transition35">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-md-12">
							<div class="sm-search-wrap">
								<?php dynamic_sidebar('sm-top-bar-search'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php if (is_active_sidebar('sm-top-bar-social')) : ?>
			<div class="sm-top-social sm-social-sticky-bar transition35">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-md-12">
							<div class="sm-social-sticky-wrap">
								<?php dynamic_sidebar('sm-top-bar-social'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<header class="sm-header">
			<div class="container">
				<div class="row">
					<div class="col-lg-4 col-md-4">
						<div class="sm-logo">
							<?php
							if (function_exists('get_custom_logo') && has_custom_logo()) :
								the_custom_logo();
							endif;
							?>
							<?php if (is_front_page() || is_home()) { ?>
								<h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
							<?php } else { ?>
								<p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
							<?php
							}
							$description = get_bloginfo('description', 'display');
							if ($description || is_customize_preview()) : ?>
								<p class="site-description"><?php echo $description; ?></p>
							<?php endif; ?>
							<?php  ?>
						</div>
					</div>
					<div class="col-lg-8 col-md-8">
						<div class="sm-top-ads">
							<?php dynamic_sidebar('sm-header-ads728x90'); ?>
						</div>
					</div>
				</div>
			</div>
		</header>
		<?php $header_image = get_header_image();
		if (! empty($header_image)) : ?>
			<div class="sm-header-image">
				<a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home">
					<img src="<?php esc_url(header_image()); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="header image" />
				</a>
			</div>
		<?php endif; ?>
		<nav class="sm-menu-bar<?php if (get_theme_mod('__smarty_magazine_sticky_menu', 0) == 1) { ?> sm-sticky<?php } ?>">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 col-md-12">
						<div class="sm-main-menu">
							<?php
							wp_nav_menu(array(
								'theme_location' => 'primary',
								'menu_id' => 'primary-menu',
								'menu_class' => 'sm-nav-menu'
							));
							?>
						</div>

						<!-- Mobile Menu Section -->
						<div class="sm-main-menu-md">
							<!-- Combine logo and hamburger in the same row -->
							<div class="d-flex justify-content-between align-items-center">
								
								<!-- Logo Section -->
								<div class="sm-logo-md">
									<a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
										<?php bloginfo('name'); ?>
									</a>
								</div>

								<!-- Hamburger Icon -->
								<div class="sm-nav-md-trigger">
									<i class="bi bi-list transition35"></i>
								</div>

							</div>
						</div>

					</div>
				</div>
			</div>
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="sm-nav-md transition35">
							<?php
							wp_nav_menu(array(
								'theme_location' => 'primary', 
								'menu_id' => 'primary-menu',  
								'menu_class' => 'menu',
								'after' => '<span class="nav-toggle-subarrow"></span>'
							)); 
							?>
						</div>
					</div>
				</div>
			</div>
		</nav>
		<?php if (!is_front_page() && ! is_home()) : ?>
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