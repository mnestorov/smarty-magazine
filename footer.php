<footer class="sm-footer">
    <?php if (is_active_sidebar('sm-footer1') || is_active_sidebar('sm-footer2') || is_active_sidebar('sm-footer3') || is_active_sidebar('sm-footer4')) : ?>
        <div class="container">
            <div class="sm-footer-cont">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <?php dynamic_sidebar('sm-footer1'); ?>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <?php dynamic_sidebar('sm-footer2'); ?>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <?php dynamic_sidebar('sm-footer3'); ?>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <?php dynamic_sidebar('sm-footer4'); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="sm-footer-bar">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="sm-copyright">
                        <?php
                        // Fetch the custom copyright from the Customizer
                        $custom_copyright = wp_kses_post(get_theme_mod('__smarty_magazine_footer_copyright'));
                        if ($custom_copyright) {
                            echo $custom_copyright; // Display custom copyright if set
                        } else {
                            // Default fallback
                            echo sprintf(
                                esc_html__('Copyright &copy; %1$s %2$s. All rights reserved.', 'smarty_magazine'),
                                date('Y'),
                                '<a href="' . esc_url(home_url('/')) . '" title="' . esc_attr(get_bloginfo('name', 'display')) . '">' . get_bloginfo('name', 'display') . '</a>'
                            );
                        }
                        ?>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="sm-footer-designer">
                        <?php
                        // Fetch the credit section if you want to make it customizable
                        $credit = wp_kses_post(get_theme_mod('footer_credit'));
                        if ($credit) {
                            echo $credit; // Display customized credit if available
                        } else {
                            echo sprintf(
                                esc_html__('Designed by %1$s', 'smarty_magazine'),
                                '<a href="' . esc_url('https://smartystudio.eu') . '" target="_blank" rel="designer">' . esc_html__('Smarty Studio', 'smarty_magazine') . '</a>'
                            );
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<a id="back-to-top"><i class="bi bi-arrow-up"></i></a>

</div><!-- .sm-body-wrap -->

<?php wp_footer(); ?>

</body>
</html>
