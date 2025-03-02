<?php
/**
 * Custom template tags for this theme.
 * 
 * Eventually, some of the functionality here could be replaced by core features.
 * 
 * @since 1.0.0
 *
 * @package SmartyMagazine
 */
 if (!function_exists('__smarty_magazine_posted_on')) {
	 /**
	  * Prints HTML with meta information for the current post-date/time and author with Schema.org markup.
	  * 
	  * @since 1.0.0
	  *
	  * @return void
	  */
	 function __smarty_magazine_posted_on() {
		 // Prepare the time string with published and modified dates
		 $time_string = '<time class="entry-date published" datetime="%1$s" itemprop="datePublished">%2$s</time>';
		 if (get_the_time('U') !== get_the_modified_time('U')) {
			 $time_string .= '<time class="updated d-none" datetime="%3$s" itemprop="dateModified">%4$s</time>';
		 }
 
		 $time_string = sprintf(
			 $time_string,
			 esc_attr(get_the_date('c')), // ISO 8601 format for Schema.org
			 esc_html(get_the_date()),
			 esc_attr(get_the_modified_date('c')),
			 esc_html(get_the_modified_date())
		 );
 
		 // Posted on markup with Bootstrap styling
		 $posted_on = sprintf(
			 esc_html_x('Posted on %s', 'post date', 'smarty_magazine'),
			 '<a href="' . esc_url(get_permalink()) . '" rel="bookmark" class="text-decoration-none">' . $time_string . '</a>'
		 );
 
		 // Author markup with Schema.org Person
		 $byline = sprintf(
			 esc_html_x('by %s', 'post author', 'smarty_magazine'),
			 '<span class="author vcard" itemprop="author" itemscope itemtype="https://schema.org/Person">' .
				 '<a class="url fn n text-decoration-none" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '" itemprop="url">' .
					 '<span itemprop="name">' . esc_html(get_the_author()) . '</span>' .
				 '</a>' .
			 '</span>'
		 );
 
		 // Output with Bootstrap icons and styling
		 ?>
		 <div class="sm-post-meta text-muted small px-2 my-2" itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
			 <span class="posted-on me-3">
				 <i class="bi bi-calendar me-1"></i><?php echo $posted_on; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			 </span>
			 <span class="byline me-3">
				 <i class="bi bi-person me-1"></i><?php echo $byline; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			 </span>
			 <?php if (comments_open() || get_comments_number()) : ?>
				 <span class="comments-link">
					 <i class="bi bi-chat me-1"></i>
					 <?php comments_number(__('No Responses', 'smarty_magazine'), __('One Response', 'smarty_magazine'), __('% Responses', 'smarty_magazine')); ?>
				 </span>
			 <?php endif; ?>
			 <meta itemprop="name" content="<?php bloginfo('name'); ?>">
		 </div>
		 <?php
	 }
 }

if (!function_exists('__smarty_magazine_entry_footer')) {
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 * 
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function __smarty_magazine_entry_footer() {
		$post_type = get_post_type();

		// =======================================================
		// 1. Categories for 'post' and 'news'
		// =======================================================
		if ($post_type === 'post') {
			if (is_object_in_taxonomy($post_type, 'category')) {
				$categories_list = get_the_category_list('</span><span class="badge text-bg-light ms-1">');

				if ($categories_list && __smarty_magazine_categorized_blog()) {
					echo '<div class="cat-links mb-2 d-flex align-items-center">';
					echo '<span class="fw-bold me-2"><i class="bi bi-folder me-1"></i>' 
						 . esc_html__('Posted in:', 'smarty_magazine') . '</span>';
					printf(
						'<span class="badge text-bg-light">%s</span>',
						$categories_list // Already escaped by get_the_category_list()
					);
					echo '</div>';
				}
			}
		} elseif ($post_type === 'news') {
			if (is_object_in_taxonomy($post_type, 'news_category')) {
				$categories_list = get_the_term_list(get_the_ID(), 'news_category', '', '</span><span class="badge text-bg-light ms-1">', '');

				if ($categories_list) {
					echo '<div class="cat-links mb-2 d-flex align-items-center">';
					echo '<span class="fw-bold me-2"><i class="bi bi-folder me-1"></i>' 
						 . esc_html__('Posted in:', 'smarty_magazine') . '</span>';
					printf(
						'<span class="badge text-bg-light">%s</span>',
						$categories_list // Already escaped by get_the_term_list()
					);
					echo '</div>';
				}
			}
		}

		// =======================================================
		// 2. Tags for 'post' and 'news'
		// =======================================================
		if ($post_type === 'post') {
			if (is_object_in_taxonomy($post_type, 'post_tag')) {
				$tags_list = get_the_tag_list('', '</span><span class="badge text-bg-light ms-1">');

				if ($tags_list) {
					echo '<div class="tags-links mb-2 d-flex align-items-center">';
					echo '<span class="fw-bold me-2"><i class="bi bi-tags me-1"></i>' 
						 . esc_html__('Tagged:', 'smarty_magazine') . '</span>';
					printf(
						'<span class="badge text-bg-light">%s</span>',
						$tags_list // Already escaped by get_the_tag_list()
					);
					echo '</div>';
				}
			}
		} elseif ($post_type === 'news') {
			if (is_object_in_taxonomy($post_type, 'news_tag')) {
				$tags_list = get_the_term_list(get_the_ID(), 'news_tag', '', '</span><span class="badge text-bg-light ms-1">', '');

				if ($tags_list) {
					echo '<div class="tags-links mb-2 d-flex align-items-center">';
					echo '<span class="fw-bold me-2"><i class="bi bi-tags me-1"></i>' 
						 . esc_html__('Tagged:', 'smarty_magazine') . '</span>';
					printf(
						'<span class="badge text-bg-light">%s</span>',
						$tags_list // Already escaped by get_the_term_list()
					);
					echo '</div>';
				}
			}
		}

		// =======================================================
		// 3. Edit Post Link
		// =======================================================
		echo '<div class="edit-post-wrapper mt-2">';
		edit_post_link(
			sprintf(
				esc_html__('[ EDIT POST ]', 'smarty_magazine'),
				get_the_title() // Use get_the_title() to avoid direct output from the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
		echo '</div>';
	}
}

if (!function_exists('__smarty_magazine_categorized_blog')) {
	/**
	 * Returns true if a blog has more than 1 category.
	 * 
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	function __smarty_magazine_categorized_blog() {
		if (false === ($all_the_cool_cats = get_transient('__smarty_magazine_categories'))) {
			$all_the_cool_cats = get_categories(
				array(
					'fields'     => 'ids',
					'hide_empty' => 1,
					'number'     => 2,
				)
			);

			$all_the_cool_cats = count( $all_the_cool_cats );

			set_transient('__smarty_magazine_categories', $all_the_cool_cats);
		}

		return ( $all_the_cool_cats > 1 );
	}
}

if (!function_exists('__smarty_magazine_category_transient_flusher')) {
	/**
	 * Flush out the transients used in __smarty_magazine_categorized_blog.
	 * 
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function __smarty_magazine_category_transient_flusher() {
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
			return;
		}

		delete_transient('__smarty_magazine_categories');
	}
	add_action('edit_category', '__smarty_magazine_category_transient_flusher');
	add_action('save_post', '__smarty_magazine_category_transient_flusher');
}