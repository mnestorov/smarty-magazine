jQuery(document).ready(function($) {
    $('.sm-img-upload').on('click', function(e) {
        e.preventDefault();  // Prevent default button behavior

        var button = $(this);
        var customUploader = wp.media({
            title: 'Select or Upload an Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        }).on('select', function() {
            var attachment = customUploader.state().get('selection').first().toJSON();

            // Find the closest hidden input and image preview
            button.prev('.sm-custom-media-image').val(attachment.url);
            button.prev().prev('img').attr('src', attachment.url).show();
        }).open();
    });

    // Ensure the widget updates properly when added or updated in the Customizer
    $(document).on('widget-updated widget-added', function(event, widget) {
        $('.sm-img-upload', widget).off('click').on('click', function(e) {
            e.preventDefault();

            var button = $(this);
            var customUploader = wp.media({
                title: 'Select or Upload an Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false
            }).on('select', function() {
                var attachment = customUploader.state().get('selection').first().toJSON();

                button.prev('.sm-custom-media-image').val(attachment.url);
                button.prev().prev('img').attr('src', attachment.url).show();
            }).open();
        });
    });
});
