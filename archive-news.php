<?php
/**
 * Template Name: News Archive
 * 
 * The template for displaying the news archive with priority sections and full-width sections.
 * 
 * @since 1.0.0
 * 
 * @package Smarty_Magazine
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */

get_header(); ?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="sm-news-archive">
                <div id="primary" class="content-area">
                    <main id="main" class="site-main" role="main">
                        <?php if (have_posts()) : ?>
                            <header class="page-header">
                                <?php the_archive_title('<h1 class="page-title">', '</h1>'); ?>
                                <?php the_archive_description('<div class="taxonomy-description lead">', '</div>'); ?>
                            </header>

                            <?php
                            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                            $is_first_page = $paged === 1;

                            // Group posts by status without disrupting main query
                            $statuses_priority = ['breaking', 'featured', 'sponsored'];
                            $statuses_fullwidth = ['normal', 'archived'];
                            $posts_by_status = [];

                            while (have_posts()) : the_post();
                                $status = __smarty_magazine_get_news_status(get_the_ID()) ?: 'normal';
                                if ($status !== 'normal') { // Handle normal separately
                                    $posts_by_status[$status][] = $post;
                                }
                            endwhile;

                            // Priority Sections with Sidebar (Breaking, Featured, Sponsored) - only on first page
                            if ($is_first_page) : ?>
                                <div class="row">
                                    <div class="col-lg-9 col-md-9">
                                        <?php foreach ($statuses_priority as $status) : ?>
                                            <?php if (!empty($posts_by_status[$status])) : ?>
                                                <section class="news-section news-<?php echo esc_attr($status); ?> mb-5">
                                                    <h2 class="section-title <?php echo esc_attr("text-$status"); ?> mb-4">
                                                        <?php echo esc_html(ucfirst($status) . ' News'); ?>
                                                    </h2>
                                                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                                                        <?php foreach ($posts_by_status[$status] as $post) : setup_postdata($post); ?>
                                                            <div class="col">
                                                                <article id="post-<?php the_ID(); ?>" <?php post_class("card h-100 p-0 news-$status"); ?> itemscope itemtype="https://schema.org/NewsArticle">
                                                                    <figure class="sm-news-post-img">
                                                                        <?php if (has_post_thumbnail()) : ?>
                                                                            <a href="<?php the_permalink(); ?>" class="card-img-top">
                                                                                <?php the_post_thumbnail('sm-featured-post-medium', array('class' => 'img-fluid', 'itemprop' => 'image')); ?>
                                                                            </a>
                                                                        <?php else : ?>
                                                                            <div class="card-img-top">
                                                                                <?php __smarty_magazine_post_img('1'); ?>
                                                                            </div>
                                                                    <?php endif; ?>

                                                                    </figure>

                                                                    <div class="text-center text-muted small">
                                                                        <?php __smarty_magazine_posted_on(); ?>
                                                                    </div>

                                                                    <div class="card-body py-0 px-2">
                                                                        <h3 class="card-title" itemprop="headline">
                                                                            <a href="<?php the_permalink(); ?>" class="text-decoration-none"><?php the_title(); ?></a>
                                                                        </h3>
                                                                    </div>

                                                                    <div class="card-footer bg-transparent border-0 p-0">
                                                                        <div class="d-grid gap-2">
                                                                            <a href="<?php the_permalink(); ?>" class="btn btn-<?php echo esc_attr($status === 'breaking' ? 'danger' : ($status === 'featured' ? 'warning' : 'secondary')); ?> rounded-0">
                                                                                <?php _e('Read More', 'smarty_magazine'); ?>
                                                                            </a>
                                                                        </div>
                                                                        <span class="search-icon position-absolute top-0 end-0 m-2">
                                                                            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark" class="btn btn-sm btn-dark">
                                                                                <i class="bi bi-search"></i>
                                                                            </a>
                                                                        </span>
                                                                    </div>

                                                                    <meta itemprop="mainEntityOfPage" content="<?php echo esc_url(get_permalink()); ?>">
                                                                    <?php if (has_post_thumbnail()) : ?>
                                                                        <meta itemprop="image" content="<?php echo esc_url(wp_get_attachment_url(get_post_thumbnail_id())); ?>">
                                                                    <?php endif; ?>
                                                                </article>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </section>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="col-lg-3 col-md-3">
                                        <?php get_sidebar(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Full Width Sections (Normal, Archived) -->
                            <?php
                            // Custom query for Normal news to fix pagination
                            $normal_args = array(
                                'post_type' => 'news',
                                'posts_per_page' => 8, // Adjust as needed (e.g., multiple of 4)
                                'paged' => $paged,
                                'meta_query' => array(
                                    'relation' => 'OR',
                                    array(
                                        'key' => '_news_status',
                                        'compare' => 'NOT EXISTS', // Posts without status
                                    ),
                                    array(
                                        'key' => '_news_status',
                                        'value' => '',
                                        'compare' => '=', // Posts with empty status
                                    )
                                )
                            );
                            $normal_query = new WP_Query($normal_args);
                            // Reset main query for pagination
                            rewind_posts();
                            ?>

                            <?php foreach ($statuses_fullwidth as $status) : ?>
                                <?php if ($status === 'normal' && $normal_query->have_posts()) : ?>
                                    <section class="news-section news-normal mb-5">
                                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 g-3">
                                            <?php while ($normal_query->have_posts()) : $normal_query->the_post(); ?>
                                                <div class="col">
                                                    <article id="post-<?php the_ID(); ?>" <?php post_class("card h-100 p-0 news-normal"); ?> itemscope itemtype="https://schema.org/NewsArticle">
                                                        <figure class="sm-news-post-img">
                                                            <?php if (has_post_thumbnail()) : ?>
                                                                <a href="<?php the_permalink(); ?>" class="card-img-top">
                                                                    <?php the_post_thumbnail('sm-featured-post-medium', array('class' => 'img-fluid', 'itemprop' => 'image')); ?>
                                                                </a>
                                                            <?php else : ?>
                                                                <?php __smarty_magazine_post_img('1'); ?>
                                                            <?php endif; ?>
                                                        </figure>

                                                        <div class="text-center text-muted small">
                                                            <?php __smarty_magazine_posted_on(); ?>
                                                        </div>

                                                        <div class="card-body py-0 px-2">
                                                            <h3 class="card-title" itemprop="headline">
                                                                <a href="<?php the_permalink(); ?>" class="text-decoration-none"><?php the_title(); ?></a>
                                                            </h3>
                                                            <div class="card-text mb-4" itemprop="description">
                                                                <?php
                                                                $excerpt = get_the_excerpt();
                                                                $limit = 260;
                                                                $pad = "...";
                                                                if (strlen($excerpt) <= $limit) {
                                                                    echo esc_html($excerpt);
                                                                } else {
                                                                    $excerpt = substr($excerpt, 0, $limit) . $pad;
                                                                    echo esc_html($excerpt);
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>

                                                        <div class="card-footer bg-transparent border-0 p-0">
                                                            <div class="d-grid gap-2">
                                                                <a href="<?php the_permalink(); ?>" class="btn btn-primary rounded-0 p-2">
                                                                    <?php _e('Read More', 'smarty_magazine'); ?>
                                                                </a>
                                                            </div>
                                                            <span class="search-icon position-absolute top-0 end-0 m-2">
                                                                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark" class="btn btn-sm btn-dark">
                                                                    <i class="bi bi-search"></i>
                                                                </a>
                                                            </span>
                                                        </div>

                                                        <meta itemprop="mainEntityOfPage" content="<?php echo esc_url(get_permalink()); ?>">
                                                        <?php if (has_post_thumbnail()) : ?>
                                                            <meta itemprop="image" content="<?php echo esc_url(wp_get_attachment_url(get_post_thumbnail_id())); ?>">
                                                        <?php endif; ?>
                                                    </article>
                                                </div>
                                            <?php endwhile; ?>
                                        </div>
                                    </section>
                                <?php elseif ($status === 'archived' && !empty($posts_by_status[$status])) : ?>
                                    <section class="news-section news-archived mb-5">
                                        <h2 class="section-title text-archived mb-4">
                                            <?php _e('Archived News', 'smarty_magazine'); ?>
                                        </h2>
                                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                                            <?php foreach ($posts_by_status[$status] as $post) : setup_postdata($post); ?>
                                                <div class="col">
                                                    <article id="post-<?php the_ID(); ?>" <?php post_class("card h-100 p-0 news-archived"); ?> itemscope itemtype="https://schema.org/NewsArticle">
                                                        <figure class="sm-news-post-img">
                                                            <?php if (has_post_thumbnail()) : ?>
                                                                <a href="<?php the_permalink(); ?>" class="card-img-top">
                                                                    <?php the_post_thumbnail('sm-featured-post-medium', array('class' => 'img-fluid', 'itemprop' => 'image')); ?>
                                                                </a>
                                                            <?php else : ?>
                                                                <?php __smarty_magazine_post_img('1'); ?>
                                                            <?php endif; ?>
                                                        </figure>

                                                        <div class="text-center text-muted small">
                                                            <?php __smarty_magazine_posted_on(); ?>
                                                        </div>

                                                        <div class="card-body py-0 px-2">
                                                            <h3 class="card-title" itemprop="headline">
                                                                <a href="<?php the_permalink(); ?>" class="text-decoration-none"><?php the_title(); ?></a>
                                                            </h3>
                                                            <div class="card-text mb-4" itemprop="description">
                                                                <?php
                                                                $excerpt = get_the_excerpt();
                                                                $limit = 260;
                                                                $pad = "...";
                                                                if (strlen($excerpt) <= $limit) {
                                                                    echo esc_html($excerpt);
                                                                } else {
                                                                    $excerpt = substr($excerpt, 0, $limit) . $pad;
                                                                    echo esc_html($excerpt);
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>

                                                        <div class="card-footer bg-transparent border-0 p-0">
                                                            <div class="d-grid gap-2">
                                                                <a href="<?php the_permalink(); ?>" class="btn btn-secondary rounded-0 p-2">
                                                                    <?php _e('Read More', 'smarty_magazine'); ?>
                                                                </a>
                                                            </div>
                                                            <span class="search-icon position-absolute top-0 end-0 m-2">
                                                                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark" class="btn btn-sm btn-dark">
                                                                    <i class="bi bi-search"></i>
                                                                </a>
                                                            </span>
                                                        </div>

                                                        <meta itemprop="mainEntityOfPage" content="<?php echo esc_url(get_permalink()); ?>">
                                                        <?php if (has_post_thumbnail()) : ?>
                                                            <meta itemprop="image" content="<?php echo esc_url(wp_get_attachment_url(get_post_thumbnail_id())); ?>">
                                                        <?php endif; ?>
                                                    </article>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </section>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <?php wp_reset_postdata(); ?>
                            <div class="clearfix"></div>
							<div class="sm-pagination-nav">
								<?php echo paginate_links(); ?>
							</div>
                        <?php else : ?>
                            <p> <?php _e('Sorry, no news items matched your criteria.', 'smarty_magazine'); ?></p>
                        <?php endif; ?>
                    </main>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>