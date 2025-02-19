<?php
/**
 * Implementation of the Custom Header feature.
 *
 * You can add an optional custom header image to header.php like so:
 * 
 * 	if (get_header_image()) : 
 * 		<a href="<?php echo esc_url(home_url( '/' )); ?>" rel="home">
 * 			<img src="<?php header_image(); ?>" width="<?php echo esc_attr(get_custom_header()->width); ?>" height="<?php echo esc_attr(get_custom_header()->height); ?>">
 * 		</a>
 * 	endif; // End header image check.
 * 
 * @since 1.0.0
 *
 * @link http://codex.wordpress.org/Custom_Headers
 * 
 * @package SmartyMagazine
 */

if (!function_exists('__smarty_magazine_header_style')) {
	/**
	 * Set up the WordPress core custom header feature.
	 * 
	 * @since 1.0.0
	 *
	 * @uses __smarty_magazine_header_style()
	 * @uses __smarty_magazine_admin_header_style()
	 * @uses __smarty_magazine_admin_header_image()
	 * 
	 * @return void
	 * 
	 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
	 */
	function __smarty_magazine_custom_header_setup() {
		add_theme_support('custom-header', apply_filters('__smarty_magazine_custom_header_args', array(
			'default-image'          => '',
			'default-text-color'     => '000000',
			'width'                  => 1600,
			'height'                 => 250,
			'flex-height'            => true,
			'wp-head-callback'       => '__smarty_magazine_header_style',
			//'admin-head-callback'    => '__smarty_magazine_admin_header_style',
			//'admin-preview-callback' => '__smarty_magazine_admin_header_image',
		)));
	}
	add_action('after_setup_theme', '__smarty_magazine_custom_header_setup');
}

if (!function_exists('__smarty_magazine_header_style')) {
	/**
	 * Styles the header image and text displayed on the blog
	 * 
	 * @since 1.0.0
	 *
	 * @see __smarty_magazine_custom_header_setup().
	 * 
	 * @return void
	 * 
	 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
	 */
	function __smarty_magazine_header_style() {
		$header_text_color = get_header_textcolor();

		// If no custom options for text are set, let's bail
		// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value.
		if (get_theme_support('custom-header', 'default-text-color') === $header_text_color) {
			return;
		}
		?>
		<style type="text/css">
			<?php if (!display_header_text()) : ?>
				.site-title a,
				.site-description {
					position: absolute;
					clip: rect(1px, 1px, 1px, 1px);
				}
			<?php else: ?>
				.site-title a,
				.site-description {
					color: #<?php echo esc_attr($header_text_color); ?>;
				}
			<?php endif; ?>
		</style>
	<?php
	}
}

if (!function_exists('__smarty_magazine_admin_header_style')) {
	/**
	 * Styles the header image displayed on the Appearance > Header admin panel.
	 * 
	 * @since 1.0.0
	 *
	 * @see __smarty_magazine_custom_header_setup().
	 * 
	 * @return void
	 * 
	 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
	 */
	function __smarty_magazine_admin_header_style() { ?>
		<style type="text/css">
			.appearance_page_custom-header #headimg {
				border: none;
			}
			#headimg h1, #desc {}
			#headimg h1 {}
			#headimg h1 a {}
			#desc {}
			#headimg img {}
		</style><?php
	}
}

if (!function_exists('__smarty_magazine_admin_header_image')) {
	/**
	 * Custom header image markup displayed on the Appearance > Header admin panel.
	 * 
	 * @since 1.0.0
	 *
	 * @see __smarty_magazine_custom_header_setup().
	 * 
	 * @return void
	 * 
	 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
	 */
	function __smarty_magazine_admin_header_image() { ?>
		<div id="headimg">
			<h1 class="displaying-header-text">
				<a id="name" style="<?php echo esc_attr('color: #' . get_header_textcolor()); ?>" onclick="return false;" href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
			</h1>
			<div class="displaying-header-text" id="desc" style="<?php echo esc_attr('color: #' . get_header_textcolor()); ?>"><?php bloginfo('description'); ?></div>
			<?php if (get_header_image()) :  ?>
				<img src="<?php header_image(); ?>" alt="">
			<?php endif; ?>
		</div><?php
	}
}
