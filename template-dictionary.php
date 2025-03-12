<?php
/**
 * Template Name: Dictionary
 * 
 * The template for displaying the dictionary page.
 *
 * @since 1.0.0
 * 
 * @package Smarty_Magazine
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */

get_header(); ?>

<main id="main" class="site-main container py-4">
    <header class="entry-header">
        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
    </header>
    <div class="container border-bottom pb-4">
        <div class="row">
            <div class="col-lg-6 col-md-6 px-0 entry-content">
                <?php the_content(); ?>
                 <?php
                wp_link_pages(array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'smarty_magazine'),
                    'after'  => '</div>',
                ));
                ?>
            </div>
        </div>
    </div>
    <!-- Search Field -->
    <div class="sm-dictionary-search my-5">
        <div class="input-group">
            <input type="text" id="sm-dictionary-search-input" class="form-control" placeholder="<?php _e('Search dictionary...', 'smarty_magazine'); ?>" aria-label="Search dictionary">
            <button class="btn btn-primary"><i class="bi bi-search"></i></button>
        </div>
    </div>
    <!-- Alphabet Navigation -->
    <?php
    $dictionary_items = get_posts(array(
        'post_type'      => 'dictionary',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC',
    ));
    $letters = array();
    foreach ($dictionary_items as $item) {
        $first_letter = mb_strtoupper(mb_substr($item->post_title, 0, 1, 'UTF-8'), 'UTF-8');
        if (!in_array($first_letter, $letters)) {
            $letters[] = $first_letter;
        }
    }
    ?>
    <nav class="sm-dictionary-alphabet mb-5 text-center">
        <ul class="nav nav-pills justify-content-center flex-wrap gap-2">
            <?php foreach ($letters as $index => $letter) : ?>
                <li class="nav-item">
                    <a class="nav-link btn btn-primary fw-bold shadow-sm px-3 py-2" 
                    href="#letter-<?php echo esc_attr($letter); ?>">
                        <?php echo esc_html($letter); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <!-- Dictionary Items -->
    <div id="sm-dictionary-results">
        <?php
        $grouped_items = array();
        foreach ($dictionary_items as $item) {
            $first_letter = mb_strtoupper(mb_substr($item->post_title, 0, 1, 'UTF-8'), 'UTF-8');
            $grouped_items[$first_letter][] = $item;
        }
        foreach ($grouped_items as $letter => $items) : ?>
            <section id="letter-<?php echo esc_attr($letter); ?>" class="sm-dictionary-section mb-5">
                <h2 class="mb-3"><?php echo esc_html($letter); ?></h2>
                <div class="accordion" id="accordion-<?php echo esc_attr($letter); ?>">
                    <?php foreach ($items as $index => $item) : ?>
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="heading-<?php echo esc_attr($item->ID); ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo esc_attr($item->ID); ?>" aria-expanded="false" aria-controls="collapse-<?php echo esc_attr($item->ID); ?>">
                                    <?php echo esc_html($item->post_title); ?>
                                </button>
                            </h3>
                            <div id="collapse-<?php echo esc_attr($item->ID); ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?php echo esc_attr($item->ID); ?>" data-bs-parent="#accordion-<?php echo esc_attr($letter); ?>">
                                <div class="accordion-body">
                                    <?php
                                    echo wpautop(get_the_content(null, false, $item->ID)); // Short description
                                    $article_id = get_post_meta($item->ID, '_dictionary_related_article', true);
                                    if ($article_id) {
                                        $article_link = get_permalink($article_id);
                                        echo '<p><a href="' . esc_url($article_link) . '" class="btn btn-link sm-dictionary-read-more">' . __('Read More', 'smarty_magazine') . '</a></p>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    </div>
    <footer class="entry-footer text-center fw-bold my-4">
        <?php __smarty_magazine_entry_footer(); ?>
    </footer>
</main>

<?php get_footer(); ?>