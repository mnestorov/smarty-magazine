<?php
/**
 * Author Box
 * 
 * @since 1.0.0
 * 
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * 
 * @package SmartyMagazine
 */

if (!function_exists('__smarty_magazine_add_profile_image_field')) {
    /**
     * Add custom profile image field to user profile
     * 
     * @since 1.0.0
     * 
     * @param WP_User $user The user object
     * 
     * @return void
     */
    function __smarty_magazine_add_profile_image_field($user) {
        $profile_image = get_user_meta($user->ID, 'avatar', true);
        ?>
        <h3><?php esc_html_e('Profile Image', 'smarty_magazine'); ?></h3>
        <table class="form-table">
            <tr>
                <th><label for="sm-profile-image"><?php esc_html_e('Upload Profile Image', 'smarty_magazine'); ?></label></th>
                <td>
                    <input type="text" name="sm-profile-image" id="sm-profile-image" class="sm-magazine-custom-media-image regular-text" value="<?php echo esc_attr($profile_image); ?>">
                    <input type="button" class="button button-secondary sm-magazine-custom-media-button" value="<?php esc_attr_e('Upload Image', 'smarty_magazine'); ?>">
                    <input type="button" class="button button-link-delete sm-magazine-remove-img" value="<?php esc_attr_e('Remove Image', 'smarty_magazine'); ?>" style="<?php echo empty($profile_image) ? 'display:none;' : ''; ?>">
                    <br>
                    <?php if (!empty($profile_image)) : ?>
                        <img src="<?php echo esc_url($profile_image); ?>" alt="Profile Image" class="mt-2" width="100">
                    <?php else : ?>
                        <p><?php esc_html_e('No custom avatar set.', 'smarty_magazine'); ?></p>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        <?php
    }
    add_action('show_user_profile', '__smarty_magazine_add_profile_image_field');
    add_action('edit_user_profile', '__smarty_magazine_add_profile_image_field');
}

if (!function_exists('__smarty_magazine_save_profile_image')) {
    /**
     * Save custom profile image field
     * 
     * @since 1.0.0
     * 
     * @param int $user_id The user ID
     * 
     * @return void
     */
    function __smarty_magazine_save_profile_image($user_id) {
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }
        if (isset($_POST['sm-profile-image']) && !empty($_POST['sm-profile-image'])) {
            update_user_meta($user_id, 'avatar', esc_url_raw($_POST['sm-profile-image'])); // Save as default avatar
        } else {
            delete_user_meta($user_id, 'avatar'); // Remove avatar if empty
        }
    }
    add_action('personal_options_update', '__smarty_magazine_save_profile_image');
    add_action('edit_user_profile_update', '__smarty_magazine_save_profile_image');
}

if (!function_exists('__smarty_magazine_custom_avatar')) {
    /**
     * Display custom profile image (avatar) in comments with classes preserved
     * 
     * @since 1.0.0
     * 
     * @param string $avatar The default avatar HTML
     * @param int|string|WP_User $id_or_email The user ID, email, or WP_User object
     * @param int $size The avatar size
     * @param string $default The default avatar URL
     * @param string $alt The avatar alt text
     * @param array $args Additional arguments including classes
     * 
     * @return string The modified avatar HTML
     */
    function __smarty_magazine_custom_avatar($avatar, $id_or_email, $size, $default, $alt, $args) {
        if (is_numeric($id_or_email)) {
            $user_id = (int) $id_or_email;
        } elseif (is_object($id_or_email) && isset($id_or_email->user_id)) {
            $user_id = (int) $id_or_email->user_id;
        } else {
            $user = get_user_by('email', $id_or_email);
            $user_id = $user ? $user->ID : 0;
        }

        if ($user_id) {
            $custom_avatar = get_user_meta($user_id, 'avatar', true);
            if (!empty($custom_avatar)) {
                // Preserve classes from $args, default to 'avatar' if none provided
                $classes = !empty($args['class']) ? (is_array($args['class']) ? implode(' ', $args['class']) : $args['class']) : 'avatar';
                $classes .= ' avatar-' . esc_attr($size) . ' photo'; // Append standard avatar classes
                return '<img src="' . esc_url($custom_avatar) . '" class="' . esc_attr($classes) . '" width="' . esc_attr($size) . '" height="' . esc_attr($size) . '" alt="' . esc_attr($alt) . '" decoding="async" loading="eager">';
            }
        }
        
        return $avatar; // Fallback to default if no custom avatar
    }
    add_filter('get_avatar', '__smarty_magazine_custom_avatar', 10, 6);
}

if (!function_exists('__smarty_magazine_author_box')) {
    /**
     * Render Author Box with different designs for single posts and author archives
     */
    function __smarty_magazine_author_box() {
        $author_id = get_the_author_meta('ID');
        $author_name = get_the_author();
        $author_bio = get_the_author_meta('description');
        $author_url = get_author_posts_url($author_id);

        // Single avatar for both designs
        $author_avatar = get_avatar($author_id, 120, '', '', array('class' => 'border border-primary-default border-3 rounded-circle img-fluid'));

        // Social Links
        $twitter = get_the_author_meta('twitter');
        $linkedin = get_the_author_meta('linkedin');
        $website = get_the_author_meta('user_url');

        if (!empty($author_bio)) :
            // Design for Single Posts ('post' or 'news')
            if (is_single() && in_array(get_post_type(), ['post', 'news'])) :
            ?>
                <div class="card shadow-lg rounded author-box my-5 p-4">
                    <div class="row align-items-center text-center text-md-start">
                        <!-- Author Avatar -->
                        <div class="col-md-3 text-center">
                            <div class="mb-2">
                                <?php echo $author_avatar; ?>
                            </div>
                        </div>
                        <!-- Author Info (Name with Inline Social Icons & Bio) -->
                        <div class="col-md-8 text-md-start">
                            <h5 class="my-4 d-flex align-items-center flex-wrap gap-2">
                                <a href="<?php echo esc_url($author_url); ?>" class="fw-bold me-2">
                                    <?php echo esc_html($author_name); ?>
                                </a>
                                <!-- Social Media Icons Inline -->
                                <?php if (!empty($twitter)) : ?>
                                    <a href="<?php echo esc_url($twitter); ?>" target="_blank" class="btn btn-primary btn-sm"><i class="bi bi-twitter"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($linkedin)) : ?>
                                    <a href="<?php echo esc_url($linkedin); ?>" target="_blank" class="btn btn-primary btn-sm"><i class="bi bi-linkedin"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($website)) : ?>
                                    <a href="<?php echo esc_url($website); ?>" target="_blank" class="btn btn-primary btn-sm"><i class="bi bi-globe"></i></a>
                                <?php endif; ?>
                            </h5>
                            <p class="text-muted mb-4"><?php echo esc_html($author_bio); ?></p>
                        </div>
                    </div>
                </div>
            <?php
            // Design for Author Archives
            elseif (is_author()) :
            ?>
                <div class="card shadow-lg rounded author-box mb-5 p-4 text-center">
                    <!-- Author Avatar -->
                    <div class="mb-3">
                        <?php echo $author_avatar; ?>
                    </div>
                    <!-- Author Name -->
                    <h5 class="fw-bold mb-2">
                        <?php echo esc_html($author_name); ?>
                    </h5>
                    <!-- Author Bio -->
                    <?php if ($author_bio) : ?>
                        <p class="text-muted"><?php echo esc_html($author_bio); ?></p>
                    <?php endif; ?>
                    <!-- Social Links -->
                    <div class="mb-3">
                        <?php if (!empty($twitter)) : ?>
                            <a href="<?php echo esc_url($twitter); ?>" target="_blank" class="btn btn-primary btn-sm">
                                <i class="bi bi-twitter"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($linkedin)) : ?>
                            <a href="<?php echo esc_url($linkedin); ?>" target="_blank" class="btn btn-primary btn-sm">
                                <i class="bi bi-linkedin"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($website)) : ?>
                            <a href="<?php echo esc_url($website); ?>" target="_blank" class="btn btn-primary btn-sm">
                                <i class="bi bi-globe"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php
            endif;
        endif;
    }
}