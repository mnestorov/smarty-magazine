<?php
/**
 * Template part for displaying Bootstrap 5 nav with tabbed content using custom meta fields for titles and content.
 *
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */

// Fetch updated meta fields (ensure the meta keys match those used in the save function)
$tab1_title   = get_post_meta(get_the_ID(), '_smarty_magazine_tab1_title', true) ?: __('Tab 1', 'smarty_magazine');
$tab1_content = get_post_meta(get_the_ID(), '_smarty_magazine_tab1_content', true) ?: '';
$tab1_enabled = get_post_meta(get_the_ID(), '_smarty_magazine_tab1_enabled', true) === '1';
$tab2_title   = get_post_meta(get_the_ID(), '_smarty_magazine_tab2_title', true) ?: __('Tab 2', 'smarty_magazine');
$tab2_content = get_post_meta(get_the_ID(), '_smarty_magazine_tab2_content', true) ?: '';
$tab2_enabled = get_post_meta(get_the_ID(), '_smarty_magazine_tab2_enabled', true) === '1';
$tab3_title   = get_post_meta(get_the_ID(), '_smarty_magazine_tab3_title', true) ?: __('Tab 3', 'smarty_magazine');
$tab3_content = get_post_meta(get_the_ID(), '_smarty_magazine_tab3_content', true) ?: '';
$tab3_enabled = get_post_meta(get_the_ID(), '_smarty_magazine_tab3_enabled', true) === '1';
?>

<div class="nav-pills-container mx-auto my-5">
    <!-- Nav Pills -->
    <ul class="nav nav-underline mb-4" id="pills-tab" role="tablist">
        <?php if ($tab1_enabled): ?>
            <li class="nav-item" role="presentation">
                <a class="nav-link text-uppercase active" id="pills-tab1-tab" data-bs-toggle="pill" href="#pills-tab1" role="tab" aria-controls="pills-tab1" aria-selected="true">
                    <?php echo esc_html($tab1_title); ?>
                </a>
            </li>
        <?php endif; ?>

        <?php if ($tab2_enabled): ?>
            <li class="nav-item" role="presentation">
                <a class="nav-link text-uppercase" id="pills-tab2-tab" data-bs-toggle="pill" href="#pills-tab2" role="tab" aria-controls="pills-tab2" aria-selected="false">
                    <?php echo esc_html($tab2_title); ?>
                </a>
            </li>
        <?php endif; ?>

        <?php if ($tab3_enabled): ?>
            <li class="nav-item" role="presentation">
                <a class="nav-link text-uppercase" id="pills-tab3-tab" data-bs-toggle="pill" href="#pills-tab3" role="tab" aria-controls="pills-tab3" aria-selected="false">
                    <?php echo esc_html($tab3_title); ?>
                </a>
            </li>
        <?php endif; ?>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content sm-tab-content" id="pills-tabContent">
        <?php if ($tab1_enabled): ?>
            <div class="tab-pane fade show active" id="pills-tab1" role="tabpanel" aria-labelledby="pills-tab1-tab">
                <?php echo do_shortcode($tab1_content); ?>
            </div>
        <?php endif; ?>

        <?php if ($tab2_enabled): ?>
            <div class="tab-pane fade" id="pills-tab2" role="tabpanel" aria-labelledby="pills-tab2-tab">
                <?php echo do_shortcode($tab2_content); ?>
            </div>
        <?php endif; ?>

        <?php if ($tab3_enabled): ?>
            <div class="tab-pane fade" id="pills-tab3" role="tabpanel" aria-labelledby="pills-tab3-tab">
                <?php echo do_shortcode($tab3_content); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
