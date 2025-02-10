<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */
?>

<?php get_header();  ?>

<div class="container">
	<div class="row">
		<div class="col-lg-9 col-md-9">
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">
					<section class="error-404 not-found">
						<header class="page-header">
							<h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'smarty_magazine'); ?></h1>
						</header>
						<div class="page-content">
							<p><?php esc_html_e('It looks like nothing was found at this location.', 'smarty_magazine'); ?></p>
						</div>
					</section>
				</main>
			</div>
		</div>
		<div class="col-lg-3 col-md-3">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>