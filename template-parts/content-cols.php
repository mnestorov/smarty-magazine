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
        <div class="row justify-content-between">
            <!-- Left Column (Main Content) -->
            <div class="col-lg-5 col-md-6 px-0">
                <div class="entry-content">
                    <?php wpautop(the_content()); ?>
                </div>
            </div>
            <!-- Right Column (Sidebar / Additional Content) -->
            <div class="col-lg-6 col-md-6 my-3 px-0">
                <div class="nav-pills-container mx-auto">
                    <ul class="nav nav-underline mb-4" id="pills2-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-uppercase active" id="pills2-tab1-tab" data-bs-toggle="pill" href="#pills2-tab1" role="tab" aria-controls="pills2-tab1" aria-selected="true">
                                <?php _e('Chain Dominance', 'smarty-magazine'); ?>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-uppercase" id="pills2-tab2-tab" data-bs-toggle="pill" href="#pills2-tab2" role="tab" aria-controls="pills2-tab2" aria-selected="false">
                                <?php _e('Currencies Dominance', 'smarty-magazine'); ?>
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content sm-tab-content" id="pills2-tabContent">
                        <div class="tab-pane fade show active" id="pills2-tab1" role="tabpanel" aria-labelledby="pills2-tab1-tab">
                            <?php echo do_shortcode('[smarty_cdg_tvl_chain_dominance]'); ?>
                        </div>
                        <div class="tab-pane fade" id="pills2-tab2" role="tabpanel" aria-labelledby="pills2-tab2-tab">
                            <?php echo do_shortcode('[smarty_cdg_currencies_dominance]'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="entry-footer text-center fw-bold my-4">
        <?php __smarty_magazine_entry_footer(); ?>
    </footer>
</article>