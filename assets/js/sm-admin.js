jQuery(document).ready(function($) {
    /**
     * Initializes a media uploader for elements with the specified button class.
     *
     * @param {string} buttonClass - The class of the button elements that will trigger the media uploader.
     *
     * This function sets up an event listener on the body for clicks on elements with the specified button class.
     * When such an element is clicked, it opens the WordPress media uploader and allows the user to select a media file.
     * The selected media file's URL is then set to a sibling input element with the class 'sm-magazine-custom-media-image',
     * and the image is displayed in a sibling img element.
     *
     * If the 'add_media' button is clicked, the custom media flag is set to false, and the default WordPress media
     * uploader behavior is restored.
     */
    
    /**
     * Initializes a media uploader for elements with the specified button class.
     */
    function mediaUploader(buttonClass) {
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
                button.siblings('.sm-magazine-custom-media-image').val(attachment.url);
                button.siblings('img').attr('src', attachment.url).show();
                button.siblings('.sm-magazine-remove-img').show();
                button.closest('form').find('input[type="submit"]').prop('disabled', false);
            }).open();
        });

        $('body').on('click', '.sm-magazine-remove-img', function(e) {
            e.preventDefault();
            var button = $(this);
            button.siblings('.sm-magazine-custom-media-image').val('');
            button.siblings('img').attr('src', '').hide();
            button.hide();
            button.closest('form').find('input[type="submit"]').prop('disabled', false);
        });
    }

    mediaUploader('.sm-magazine-custom-media-button');

    var faqIndex = $('#sm-magazine-faqs-container .sm-magazine-custom-faq').length;

    $('#sm-magazine-add-faq-button').on('click', function() {
        var newFAQ = `
        <div class="sm-magazine-custom-faq ` + (faqIndex % 2 == 0 ? 'even' : 'odd') + `">
            <div class="sm-magazine-faq-header">
                <h4>FAQ ` + (faqIndex + 1) + ` - <span class="sm-magazine-faq-title">New FAQ</span></h4>
                <button type="button" class="button button-secondary sm-magazine-toggle-faq">Toggle</button>
            </div>
            <div class="sm-magazine-faq-content" style="display: none;">
                <p>
                    <label>Question:</label><br>
                    <input type="text" name="_smarty_magazine_faqs[` + faqIndex + `][question]" style="width: 100%;">
                </p>
                <p>
                    <label>Answer:</label><br>
                    <textarea name="_smarty_magazine_faqs[` + faqIndex + `][answer]" style="width: 100%;"></textarea>
                </p>
                <input type="hidden" name="_smarty_magazine_faqs[` + faqIndex + `][delete]" value="0" class="sm-magazine-delete-input">
                <button type="button" class="button button-secondary sm-magazine-remove-faq-button">Remove FAQ</button>
            </div>
        </div>`;

        $('#sm-magazine-faqs-container').append(newFAQ);
        faqIndex++;
    });

    $(document).on('click', '.sm-magazine-toggle-faq', function() {
        $(this).closest('.sm-magazine-custom-faq').find('.sm-magazine-faq-content').slideToggle();
    });

    $(document).on('click', '.sm-magazine-remove-faq-button', function() {
        var faqDiv = $(this).closest('.sm-magazine-custom-faq');
        var deleteInput = faqDiv.find('.sm-magazine-delete-input');

        if (faqDiv.data('index') !== undefined) {
            deleteInput.val('1');
            faqDiv.hide();
        } else {
            faqDiv.remove();
        }
    });
});