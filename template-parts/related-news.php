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
$__smarty_magazine_related = __smarty_magazine_get_related_news(get_the_ID(), $sm_post_per_page);
?>

<?php if (!empty($__smarty_magazine_related)) : ?>
	<div class="sm-news-layout-wrap sm-related-posts">
		<h2><?php _e('Related posts', 'smarty-magazine'); ?></h2>
		<ul>
			<?php foreach ($__smarty_magazine_related as $related) :
				$related_status = __smarty_magazine_get_news_status($related->ID);
				$related_class = $related_status ? 'news-' . esc_attr($related_status) : '';
			?>
				<li class="sm-news-post">
					<figure class="sm-news-post-img">
						<?php if (has_post_thumbnail($related->ID)) :
							$image = '';
							$title_attribute = get_the_title($post->ID);
							$image .= '<a href="' . esc_url(get_permalink()) . '" title="' . the_title('', '', false) . '">';
							$image .= get_the_post_thumbnail($post->ID, 'sm-featured-post-medium', array('title' => esc_attr($title_attribute), 'alt' => esc_attr($title_attribute))) . '</a>';
							echo $image;
						?>
						<?php else : ?>
							<div class="sm-no-image"></div>
						<?php endif; ?>
						<a href="<?php echo esc_url(get_permalink()); ?>" rel="bookmark"><span><i class="bi bi-search"></i></span></a>
					</figure>
					<h3><a href="<?php echo esc_url(get_permalink($related->ID)); ?>" class="text-decoration-none"><?php echo esc_html($related->post_title); ?></a></h3>
					<?php if ($related_status) : ?>
						<span class="badge <?php echo esc_attr("bg-$related_status status-$related_status"); ?>">
							<?php echo esc_html(ucfirst($related_status)); ?>
						</span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
			<div class="clearfix"></div>
		</ul>
	</div>

	<?php wp_reset_postdata(); ?>
<?php endif; ?>