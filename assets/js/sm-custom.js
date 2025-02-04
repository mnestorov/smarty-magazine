jQuery(document).ready(function($) {
    // Search icon toggle
    $('.sm-search-icon').on('click', function() {
        $('.sm-search-bar').toggleClass('sm-search-bar-show');
        $(this).find('.bi').toggleClass('bi-search bi-x');
    });

    // Close search when clicking outside
    $(document).on('click', function(e) {
        if ($(e.target).closest('.sm-search-bar, .sm-search-icon').length === 0) {
            $('.sm-search-bar').removeClass('sm-search-bar-show');
            $('.sm-search-icon .bi-x').addClass('bi-search').removeClass('bi-x');
        }
    });

    // Convert Hex to RGBA
    function convertHex(hex, opacity) {
        hex = hex.replace('#', '');
        var r = parseInt(hex.substring(0, 2), 16),
            g = parseInt(hex.substring(2, 4), 16),
            b = parseInt(hex.substring(4, 6), 16);
        return 'rgba(' + r + ',' + g + ',' + b + ',' + opacity / 100 + ')';
    }

    // News Ticker
    $('.sm-newsticker').newsTicker({
        row_height: 42,
        max_rows: 1,
        speed: 600,
        direction: 'up',
        duration: 3500,
        autostart: 1,
        pauseOnHover: 1
    });

    // Initialize post slider
    var sm_banner_slider = new Swiper('.sm-featured-post-slider', {
        paginationClickable: true,
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        autoplay: 3000,
        speed: 600
    });

    // Back to Top
    if ($('#back-to-top').length) {
        var scrollTrigger = 600, // px
            backToTop = function() {
                var scrollTop = $(window).scrollTop();
                if (scrollTop > scrollTrigger) {
                    $('#back-to-top').addClass('show');
                } else {
                    $('#back-to-top').removeClass('show');
                }
            };
        backToTop();
        $(window).on('scroll', backToTop);
        $('#back-to-top').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, 600);
        });
    }
});
