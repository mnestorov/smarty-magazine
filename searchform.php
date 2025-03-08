<?php
/**
 * The template for displaying the search form.
 * 
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */
?>

<div class="container my-2 my-lg-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-10">
            <!-- Title / Help Text -->
            <h2 class="mb-3 text-center">
                <?php esc_html_e('Looking for Something?', 'smarty_magazine'); ?>
            </h2>
            <p class="text-center text-muted mb-4">
                <?php esc_html_e('Type your query below and click "Search" to find what you need!', 'smarty_magazine'); ?>
            </p>

            <!-- Actual Search Form -->
            <form role="search"
                  method="get"
                  action="<?php echo esc_url(home_url( '/' )); ?>"
                  class="d-flex justify-content-center">
                
                <div class="input-group input-group-lg w-100">
                    <!-- Search Input -->
                    <label for="s" class="visually-hidden">
                        <?php esc_html_e('Search for:', 'smarty_magazine'); ?>
                    </label>
                    <input type="search"
                           id="s"
                           class="sm-searchform form-control"
                           name="s"
                           value="<?php echo get_search_query(); ?>"
                           placeholder="<?php esc_attr_e('Search the site...', 'smarty_magazine'); ?>" />

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>
                        <?php esc_html_e('Search', 'smarty_magazine'); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
