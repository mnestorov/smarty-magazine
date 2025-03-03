<?php
/**
 * Template Part: Two Columns Layout
 *
 * Displays content in a two-column format using Bootstrap 5.
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
            <div class="col-lg-6 col-md-6">
                <div class="entry-content">
                    <?php the_content(); ?>
                    <?php
                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'smarty_magazine'),
                        'after'  => '</div>',
                    ));
                    ?>
                </div>
            </div>
            <!-- Right Column (Sidebar / Additional Content) -->
            <div class="col-lg-6 col-md-6">
                
            </div>
        </div>
    </div>
    <footer class="entry-footer">
        <?php
        edit_post_link(
            sprintf(
                esc_html__('Edit %s', 'smarty_magazine'),
                the_title('<span class="screen-reader-text">"', '"</span>', false)
            ),
            '<span class="edit-link">',
            '</span>'
        );
        ?>
    </footer>
</article>