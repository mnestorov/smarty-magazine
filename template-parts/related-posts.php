<?php
/**
 * Related Posts
 * 
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */
?>

<?php
$sm_post_per_page = 3;
$__smarty_magazine_related = get_posts(
	array(
		'category__in' 			=> wp_get_post_categories($post->ID),
		'post__not_in' 			=> array($post->ID),
		'orderby'           	=> 'rand',
		'posts_per_page'		=> $sm_post_per_page,
		'ignore_sticky_posts'	=> 1
	)
);
?>

<div class="sm-news-layout-wrap sm-related-posts">
	<h2>Related posts</h2>
	<ul>
		<?php if ($__smarty_magazine_related) foreach ($__smarty_magazine_related as $post) { ?>
			<?php setup_postdata($post); ?>
			<li class="sm-news-post">
				<figure class="sm-news-post-img">
					<?php if (has_post_thumbnail()) {
						$image = '';
						$title_attribute = get_the_title($post->ID);
						$image .= '<a href="' . esc_url(get_permalink()) . '" title="' . the_title('', '', false) . '">';
						$image .= get_the_post_thumbnail($post->ID, 'sm-featured-post-medium', array('title' => esc_attr($title_attribute), 'alt' => esc_attr($title_attribute))) . '</a>';
						echo $image;
					?>
					<?php } else { ?>
						<div class="sm-no-image"></div>
					<?php } ?>
					<a href="<?php echo esc_url(get_permalink()); ?>" rel="bookmark"><span><i class="bi bi-search"></i></span></a>
				</figure>
				<h3><a href="<?php esc_url(the_permalink()); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
			</li>
		<?php } ?>
		<div class="clearfix"></div>
	</ul>
</div>

<?php wp_reset_postdata(); ?>