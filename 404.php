<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */
?>

<?php get_header(); ?>

<div class="container d-flex flex-column justify-content-center align-items-center vh-75 text-center py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="display-1 fw-bold text-warning">404</h1>
            <h2 class="fw-bold text-dark"><?php esc_html_e("Oops! Page not found", "smarty_magazine"); ?></h2>
            <p class="lead text-muted">
                <?php esc_html_e("The page you're looking for might have been removed or is temporarily unavailable.", "smarty_magazine"); ?>
            </p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-lg btn-warning mt-3">
				<i class="bi bi-arrow-return-left me-2"></i><?php esc_html_e("Back to Home", "smarty_magazine"); ?>
            </a>
        </div>
    </div>
</div>

<?php get_footer(); ?>