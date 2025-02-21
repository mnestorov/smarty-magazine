<?php
/**
 * Template part for displaying single posts with Schema.org markup.
 *
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */

$thumbnail_id = get_post_thumbnail_id();
$thumbnail_url = wp_get_attachment_url($thumbnail_id);
$thumbnail_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
$word_count = str_word_count(strip_tags(get_the_content()));
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="https://schema.org/Article">
    <header class="entry-header">
        <?php the_title('<h1 class="entry-title" itemprop="headline">', '</h1>'); ?>
        <div class="entry-meta">
            <?php __smarty_magazine_posted_on(); ?>
        </div>
        <meta itemprop="mainEntityOfPage" content="<?php echo esc_url(get_permalink()); ?>">
        <meta itemprop="description" content="<?php echo esc_attr(get_the_excerpt()); ?>">
        <meta itemprop="wordCount" content="<?php echo esc_attr($word_count); ?>">
    </header>

    <div class="entry-content">
        <?php if (has_post_thumbnail()) : ?>
            <figure class="post-thumbnail mb-4" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                <?php the_post_thumbnail('large', array('class' => 'img-fluid')); ?>
                <meta itemprop="url" content="<?php echo esc_url($thumbnail_url); ?>">
                <meta itemprop="width" content="<?php echo esc_attr(getimagesize($thumbnail_url)[0]); ?>">
                <meta itemprop="height" content="<?php echo esc_attr(getimagesize($thumbnail_url)[1]); ?>">
                <?php if ($thumbnail_alt) : ?>
                    <meta itemprop="description" content="<?php echo esc_attr($thumbnail_alt); ?>">
                <?php endif; ?>
            </figure>
        <?php endif; ?>

        <div class="content" itemprop="articleBody">
            <?php the_content(); ?>
            <?php
            wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'smarty_magazine'),
                'after'  => '</div>',
            ));
            ?>
        </div>
    </div>

    <footer class="entry-footer">
        <?php __smarty_magazine_entry_footer(); ?>
    </footer>

    <?php if (get_theme_mod('__smarty_magazine_related_posts_setting', 0) == 1) : ?>
        <?php get_template_part('template-parts/related-posts'); ?>
    <?php endif; ?>
</article>