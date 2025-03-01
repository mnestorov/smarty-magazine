<?php
/**
 * Template Part for displaying a content for a single news post with enhanced Schema.org markup.
 * 
 * @since 1.0.0
 * 
 * @package Smarty_Magazine
 */

$news_status = __smarty_magazine_get_news_status(get_the_ID());
$status_class = $news_status ? 'news-' . esc_attr($news_status) : '';
$disclaimer = __smarty_magazine_get_news_disclaimer(get_the_ID());
$image_source = __smarty_magazine_get_news_image_source(get_the_ID());
$details = __smarty_magazine_get_news_details(get_the_ID());
$thumbnail_id = get_post_thumbnail_id();
$thumbnail_url = wp_get_attachment_url($thumbnail_id);
$thumbnail_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
$word_count = str_word_count(strip_tags(get_the_content()));
?>

<article id="post-<?php the_ID(); ?>" <?php post_class("sm-single-news $status_class"); ?> itemscope itemtype="https://schema.org/NewsArticle">
    <header class="entry-header">
        <?php the_title('<h1 class="entry-title mb-0" itemprop="headline">', '</h1>'); ?>
        <?php if ($news_status) : ?>
            <span class="badge <?php echo esc_attr("bg-$news_status status-$news_status"); ?> d-inline-block w-100 p-2 text-uppercase rounded-top-0">
                <?php echo esc_html(ucfirst($news_status)); ?>
            </span>
        <?php endif; ?>
        <div class="entry-meta mt-4">
            <?php __smarty_magazine_posted_on(); ?>
        </div>
        <meta itemprop="mainEntityOfPage" content="<?php echo esc_url(get_permalink()); ?>">
        <meta itemprop="description" content="<?php echo esc_attr(get_the_excerpt()); ?>">
        <meta itemprop="wordCount" content="<?php echo esc_attr($word_count); ?>">
        <?php if ($news_status) : ?>
            <meta itemprop="genre" content="<?php echo esc_attr(ucfirst($news_status)); ?>">
        <?php endif; ?>
    </header>
    
    <div class="entry-content">
        <?php if (has_post_thumbnail()) : ?>
            <figure class="sm-single-news-img mb-4 position-relative" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                <?php the_post_thumbnail('sm-featured-post-extra-large', array('class' => 'img-fluid')); ?>
                <meta itemprop="url" content="<?php echo esc_url($thumbnail_url); ?>">
                <meta itemprop="width" content="<?php echo esc_attr(getimagesize($thumbnail_url)[0]); ?>">
                <meta itemprop="height" content="<?php echo esc_attr(getimagesize($thumbnail_url)[1]); ?>">
                <?php if ($thumbnail_alt) : ?>
                    <meta itemprop="description" content="<?php echo esc_attr($thumbnail_alt); ?>">
                <?php endif; ?>
                <?php if ($image_source) : ?>
                    <figcaption class="text-muted small mt-2"><?php printf(__('Image source: %s', 'smarty_magazine'), esc_html($image_source)); ?></figcaption>
                <?php endif; ?>
            </figure>
        <?php else : ?>
            <?php __smarty_magazine_post_img('1'); ?>                                        
        <?php endif; ?>

        <div class="content entry-content mt-4" itemprop="articleBody">
            <?php the_content(); ?>
        </div>

        <?php if ($details) : ?>
            <div class="alert alert-info mt-4" role="alert">
                <h4 class="alert-heading"><?php _e('Additional Information', 'smarty_magazine'); ?></h4>
                <hr>
                <p class="mb-0"><?php echo esc_html($details); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($disclaimer) : ?>
            <div class="alert alert-warning mt-4" role="alert">
                <h4 class="alert-heading"><?php _e('Disclaimer', 'smarty_magazine'); ?></h4>
                <hr>
                <p class="mb-0" itemprop="disclaimer"><?php echo esc_html($disclaimer); ?></p>
            </div>
        <?php endif; ?>
    </div>

    <footer class="entry-footer text-center fw-bold my-4">
        <?php __smarty_magazine_entry_footer(); ?>
    </footer>

    <?php if (get_theme_mod('__smarty_magazine_related_posts_setting', 0) == 1) : ?>
        <?php get_template_part('template-parts/related-news'); ?>
    <?php endif; ?>
</article>