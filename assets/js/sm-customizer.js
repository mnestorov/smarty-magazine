(function($) {
    // Live preview for Site Title
    wp.customize( 'blogname', function( value ) {
        value.bind( function( to ) {
            $( '.site-title a' ).text( to );
        } );
    });

    // Live preview for Site Description
    wp.customize( 'blogdescription', function( value ) {
        value.bind( function( to ) {
            $( '.site-description' ).text( to );
        } );
    });

    // Live preview for Header Text Color
    wp.customize( 'header_textcolor', function( value ) {
        value.bind( function( to ) {
            if ( 'blank' === to ) {
                $( '.sm-logo .site-title a, .site-description' ).css( {
                    'clip': 'rect(1px, 1px, 1px, 1px)',
                    'position': 'absolute'
                } );
            } else {
                $( '.sm-logo .site-title a, .site-description' ).css( {
                    'clip': 'auto',
                    'color': to,
                    'position': 'relative'
                } );
            }
        });
    });

    // Live Preview for Header Background Color
    wp.customize( '__smarty_magazine_header_bg_color', function( value ) {
        value.bind( function( newval ) {
            $( '.sm-header' ).css( 'background-color', newval );
        });
    });

    // Live Preview for Header Text Logo Color
    wp.customize( '__smarty_magazine_header_text_color', function( value ) {
        value.bind( function( newval ) {
            $( 'h1.site-title a' ).css( 'color', newval );
        });
    });

    // News Ticker Background Color
    wp.customize('__smarty_magazine_news_ticker_bg_color', function(value) {
        value.bind(function(newval) {
            $('.bt-news-ticker').css('background-color', newval);
            $('.bt-news-ticker-tag').css('background-color', newval);
            $('.bt-news-ticker-tag:after').css('border-left-color', newval);
        });
    });

    // News Ticker Tag Color
    wp.customize('__smarty_magazine_news_ticker_tag_color', function(value) {
        value.bind(function(newval) {
            $('.bt-news-ticker-tag').css('color', newval);
        });
    });

    // News Ticker Text Color
    wp.customize('__smarty_magazine_news_ticker_text_color', function(value) {
        value.bind(function(newval) {
            $('.bt-news-ticker li a').css('color', newval);
        });
    });
})(jQuery);
