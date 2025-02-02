<?php

/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package SmartyMagazine
 */

if (!function_exists('__smarty_magazine_posted_on')) {
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 *
	 * @return void
	 */
	function __smarty_magazine_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if (get_the_time('U') !== get_the_modified_time('U') ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr(get_the_date('c')),
			esc_html(get_the_date()),
			esc_attr(get_the_modified_date('c')),
			esc_html(get_the_modified_date())
		);

		$posted_on = sprintf(
			esc_html_x('Posted on %s', 'post date', 'smarty_magazine'),
			'<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
		);

		$byline = sprintf(
			esc_html_x('by %s', 'post author', 'smarty_magazine'),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author() ) . '</a></span>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if (!function_exists('__smarty_magazine_entry_footer')) {
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     *
     * @return void
     */
    function __smarty_magazine_entry_footer() {
        if ('post' === get_post_type()) {
            $categories_list = get_the_category_list(esc_html__(', ', 'smarty_magazine'));
            if ($categories_list && __smarty_magazine_categorized_blog()) {
                printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'smarty_magazine') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

            $tags_list = get_the_tag_list('', esc_html__(', ', 'smarty_magazine'));
            if ($tags_list) {
                printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'smarty_magazine') . '</span>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        }

        if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
            echo '<span class="comments-link">';
            comments_popup_link(esc_html__('Leave a comment', 'smarty_magazine'), esc_html__('1 Comment', 'smarty_magazine'), esc_html__('% Comments', 'smarty_magazine'));
            echo '</span>';
        }

        // Wrap the edit link with a div tag
        echo '<div class="edit-post-wrapper">';
        edit_post_link(
            sprintf(
                esc_html__('[ Edit ]', 'smarty_magazine'),
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