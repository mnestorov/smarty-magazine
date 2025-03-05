<?php
/**
 * Template part for displaying content in a two-column format using Bootstrap 5.
 *
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */

// Fetch updated meta fields (ensure the meta keys match those used in the save function)
$nav_tab1_title   = get_post_meta(get_the_ID(), '_smarty_magazine_nav_tab1_title', true) ?: __('Tab 1', 'smarty_magazine');
$nav_tab1_content = get_post_meta(get_the_ID(), '_smarty_magazine_nav_tab1_content', true) ?: '';
$nav_tab1_enabled = get_post_meta(get_the_ID(), '_smarty_magazine_nav_tab1_enabled', true) === '1';
$nav_tab2_title   = get_post_meta(get_the_ID(), '_smarty_magazine_nav_tab2_title', true) ?: __('Tab 2', 'smarty_magazine');
$nav_tab2_content = get_post_meta(get_the_ID(), '_smarty_magazine_nav_tab2_content', true) ?: '';
$nav_tab2_enabled = get_post_meta(get_the_ID(), '_smarty_magazine_nav_tab2_enabled', true) === '1';
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
                <?php if ($nav_tab1_enabled || $nav_tab2_enabled) : ?>
                    <div class="nav-pills-container mx-auto">
                        <ul class="nav nav-underline mb-4" id="pills2-tab" role="tablist">
                            <?php if ($nav_tab1_enabled): ?>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-uppercase active" id="pills2-tab1-tab" data-bs-toggle="pill" href="#pills2-tab1" role="tab" aria-controls="pills2-tab1" aria-selected="true">
                                        <?php echo esc_html($nav_tab1_title); ?>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if ($nav_tab2_enabled): ?>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-uppercase" id="pills2-tab2-tab" data-bs-toggle="pill" href="#pills2-tab2" role="tab" aria-controls="pills2-tab2" aria-selected="false">
                                        <?php echo esc_html($nav_tab2_title); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content sm-tab-content" id="pills2-tabContent">
                            <?php if ($nav_tab1_enabled): ?>
                                <div class="tab-pane fade show active" id="pills2-tab1" role="tabpanel" aria-labelledby="pills2-tab1-tab">
                                    <?php echo do_shortcode($nav_tab1_content); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($nav_tab2_enabled): ?>
                                <div class="tab-pane fade" id="pills2-tab2" role="tabpanel" aria-labelledby="pills2-tab2-tab">
                                    <?php echo do_shortcode($nav_tab2_content); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <footer class="entry-footer text-center fw-bold my-4">
        <?php __smarty_magazine_entry_footer(); ?>
    </footer>
</article>