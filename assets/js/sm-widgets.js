// Image Uploader
jQuery(document).ready( function( $ ) {
    jQuery( ".sm-img-upload" ).live( "click", function () {
        var jw_attachment_link = wp.media.editor.send.attachment;
        var button = jQuery(this);
        wp.media.editor.send.attachment = function (props, attachment) {
            jQuery(button).prev().prev().attr('src', attachment.url);
            jQuery(button).prev().val(attachment.url);
            wp.media.editor.send.attachment = jw_attachment_link;
        };
        wp.media.editor.open(button);
        return false;
    });


    /* <fs_premium_only> */

    var $document = $( document );

    $( '.list-filters-sortable').sortable({
        handle: '.list-item-title'
    });

    $( 'body').on( 'click', '.sm-repeatable .add', function( e ){
        e.preventDefault();
        var btn = $( this );
        var p =  $( this).parent();
        var c =  $( this).closest( '.sm-repeatable' );
        var s = p.find( 'select' );
        var id = s.val();

        if ( id > 0 ) {
            var label = s.find('option:selected').html();
            var find = '&nbsp;';
            var re = new RegExp(find, 'g');
            label = label.replace(re, '');
            var name = btn.attr('data-name');
            var html = $('<div class="list-item"></div>');
            html.append('<input type="hidden" value="' + id + '" name="' + name + '">');
            html.append('<span class="list-item-title">' + label + '</span>');
            html.append('<a href="#" class="remove"></a>');
            $('.list-filters-sortable', c).append(html);
        }

        s.find('option:selected').removeAttr( 'selected' );

    } );

    $( 'body').on( 'click', '.list-item .remove', function( e ){
        e.preventDefault();
        $( this).closest( '.list-item').remove();
    } );


    // When add new widget
    $document.on( 'widget-added', function( e, widget ) {
        $( '.list-filters-sortable', widget ).sortable({
            handle: '.list-item-title'
        });
    } );

    // When update widget
    $document.on( 'widget-updated', function( e, widget ) {
        $( '.list-filters-sortable', widget ).sortable({
            handle: '.list-item-title'
        });
    } );

    /* </fs_premium_only> */

});
