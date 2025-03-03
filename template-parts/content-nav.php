<?php
/**
 * Template Part: Nav Content
 *
 * Displays Bootstrap 5 nav with tabbed content using custom meta fields for titles and content.
 *
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */

// Fetch updated meta fields (ensure the meta keys match those used in the save function)
$tab1_title = get_post_meta(get_the_ID(), '_smarty_magazine_tab1_title', true) ?: __('Crypto Prices by Market Cap', 'smartymagazine');
$tab1_content = get_post_meta(get_the_ID(), '_smarty_magazine_tab1_content', true) ?: '';
$tab2_title = get_post_meta(get_the_ID(), '_smarty_magazine_tab2_title', true) ?: __('Gainers & Losers', 'smartymagazine');
$tab2_content = get_post_meta(get_the_ID(), '_smarty_magazine_tab2_content', true) ?: '';
$tab3_title = get_post_meta(get_the_ID(), '_smarty_magazine_tab3_title', true) ?: __('Stable Coins', 'smartymagazine');
$tab3_content = get_post_meta(get_the_ID(), '_smarty_magazine_tab3_content', true) ?: '';

?>

<div class="nav-pills-container mx-auto my-5">
    <ul class="nav nav-pills nav-fill justify-content-center mb-4" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="pills-tab1-tab" data-bs-toggle="pill" href="#pills-tab1" role="tab" aria-controls="pills-tab1" aria-selected="true">
                <?php echo esc_html($tab1_title); ?>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="pills-tab2-tab" data-bs-toggle="pill" href="#pills-tab2" role="tab" aria-controls="pills-tab2" aria-selected="false">
                <?php echo esc_html($tab2_title); ?>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="pills-tab3-tab" data-bs-toggle="pill" href="#pills-tab3" role="tab" aria-controls="pills-tab3" aria-selected="false">
                <?php echo esc_html($tab3_title); ?>
            </a>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-tab1" role="tabpanel" aria-labelledby="pills-tab1-tab">
            <?php echo do_shortcode($tab1_content); ?>
        </div>
        <div class="tab-pane fade" id="pills-tab2" role="tabpanel" aria-labelledby="pills-tab2-tab">
            <?php echo do_shortcode($tab2_content); ?>
        </div>
        <div class="tab-pane fade" id="pills-tab3" role="tabpanel" aria-labelledby="pills-tab3-tab">
            <?php echo do_shortcode($tab3_content); ?>
        </div>
    </div>
</div>
