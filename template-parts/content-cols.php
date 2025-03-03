<?php
/**
 * Template part for displaying content in a two-column format using Bootstrap 5.
 *
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
    </header>
    <div class="container">
        <div class="row">
            <!-- Left Column (Main Content) -->
            <div class="col-lg-6 col-md-6 px-0">
                <div class="entry-content">
                    <?php wpautop(the_content()); ?>
                </div>
            </div>
            <!-- Right Column (Sidebar / Additional Content) -->
            <div class="col-lg-6 col-md-6 px-0">
                
            </div>
        </div>
    </div>
    <footer class="entry-footer text-center fw-bold my-4">
        <?php __smarty_magazine_entry_footer(); ?>
    </footer>
</article>