jQuery(document).ready(function($) {
    /**
     * Initializes a media uploader for elements with the specified button class.
     *
     * @param {string} buttonClass - The class of the button elements that will trigger the media uploader.
     *
     * This function sets up an event listener on the body for clicks on elements with the specified button class.
     * When such an element is clicked, it opens the WordPress media uploader and allows the user to select a media file.
     * The selected media file's URL is then set to a sibling input element with the class 'sm-custom-media-image',
     * and the image is displayed in a sibling img element.
     *
     * If the 'add_media' button is clicked, the custom media flag is set to false, and the default WordPress media
     * uploader behavior is restored.
     */
    
    /**
     * Initializes a media uploader for elements with the specified button class.
     */
    function mediaUploader(buttonClass) {
        var _custom_media = true,
            _orig_send_attachment = wp.media.editor.send.attachment;

        $('body').on('click', buttonClass, function(e) {
            e.preventDefault();
            var button = $(this);
            var custom_uploader = wp.media({
                title: 'Select Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false
            }).on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                button.siblings('.sm-custom-media-image').val(attachment.url);
                button.siblings('img').attr('src', attachment.url).show();
                button.siblings('.sm-remove-img').show();
                button.closest('form').find('input[type="submit"]').prop('disabled', false);
            }).open();
        });

        $('body').on('click', '.sm-remove-img', function(e) {
            e.preventDefault();
            var button = $(this);
            button.siblings('.sm-custom-media-image').val('');
            button.siblings('img').attr('src', '').hide();
            button.hide();
            button.closest('form').find('input[type="submit"]').prop('disabled', false);
        });
    }

    mediaUploader('.sm-custom-media-button');

    jQuery(document).ready(function($) {
        /**
         * Initializes a media uploader for elements with the specified button class.
         */
        function mediaUploader(buttonClass) {
            var _custom_media = true,
                _orig_send_attachment = wp.media.editor.send.attachment;
    
            $('body').on('click', buttonClass, function(e) {
                e.preventDefault();
                var button = $(this);
                var custom_uploader = wp.media({
                    title: 'Select Image',
                    button: { text: 'Use this image' },
                    multiple: false
                }).on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    button.siblings('.sm-custom-media-image').val(attachment.url);
                    button.siblings('img').attr('src', attachment.url).show();
                    button.siblings('.sm-remove-img').show();
                    button.closest('form').find('input[type="submit"]').prop('disabled', false);
                }).open();
            });
    
            $('body').on('click', '.sm-remove-img', function(e) {
                e.preventDefault();
                var button = $(this);
                button.siblings('.sm-custom-media-image').val('');
                button.siblings('img').attr('src', '').hide();
                button.hide();
                button.closest('form').find('input[type="submit"]').prop('disabled', false);
            });
        }
    
        mediaUploader('.sm-custom-media-button');
    
        /**
         * Updates the category dropdown based on the selected post type.
         */
        function updateCategories($widget) {
            let selectedPostType = $widget.find('input.sm-show-posts-type:checked').val();
            let selectedCategories = [];
    
            $widget.find('.sm-category-dropdown select').each(function() {
                selectedCategories.push($(this).val());
            });
    
            console.log("Fetching categories for post type:", selectedPostType);
    
            $.ajax({
                type: 'POST',
                url: sm_ajax_data.ajax_url,
                data: {
                    action: 'sm_get_categories',
                    post_type: selectedPostType,
                    selected_categories: selectedCategories,
                    security: sm_ajax_data.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $widget.find('.sm-category-dropdown select').each(function(index) {
                            $(this).html(response.data.categories);
                            $(this).val(selectedCategories[index]); // Restore selected values
                        });
                    } else {
                        console.log("Category Fetch Error:", response.data.message);
                    }
                }
            });
        }
    
        // Initialize categories on page load
        $('.sm-highlighted-news-grid').each(function() {
            updateCategories($(this));
        });
    
        // Update categories dynamically on post type change
        $(document).on('change', '.sm-show-posts-type', function() {
            let $widget = $(this).closest('.sm-highlighted-news-grid');
            updateCategories($widget);
        });
    });    
});