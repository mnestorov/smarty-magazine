<?php
/**
 * Related News
 * 
 * @since 1.0.0
 * 
 * @package SmartyMagazine
 */

$__smarty_magazine_related = __smarty_magazine_get_related_news(get_the_ID());
?>

<?php if (!empty($__smarty_magazine_related)) : ?>
	<div class="sm-news-layout-wrap sm-related-posts">
		<h2><?php _e('Related news', 'smarty_magazine'); ?></h2>
		<ul>
			<?php foreach ($__smarty_magazine_related as $related) :
				$related_status = __smarty_magazine_get_news_status($related->ID);
				$related_class = $related_status ? 'news-' . esc_attr($related_status) : ''; ?>
				<li class="sm-news-post">
					<figure class="sm-news-post-img">
						<?php if (has_post_thumbnail($related->ID)) : ?>
							<a href="<?php echo esc_url(get_permalink($related->ID)); ?>" class="card-img-top" title="<?php echo the_title('', '', false); ?>">
								<?php 
								echo get_the_post_thumbnail(
									$related->ID, 
									'sm-featured-post-medium', 
									array(
										'class' => 'img-fluid', 
										'title' => esc_attr(get_the_title($related->ID)), 
										'alt' => esc_attr(get_the_title($related->ID)), 
										'itemprop' => 'image'
									)
								); 
								?>
							</a>
						<?php else : ?>
							<?php __smarty_magazine_post_img('1'); ?>                                         
						<?php endif; ?>
						<a href="<?php echo esc_url(get_permalink($related->ID)); ?>" rel="bookmark"><span><i class="bi bi-search"></i></span></a>
					</figure>
					<h3>
						<a href="<?php echo esc_url(get_permalink($related->ID)); ?>" class="text-decoration-none">
							<?php echo esc_html($related->post_title); ?>
						</a>
					</h3>
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