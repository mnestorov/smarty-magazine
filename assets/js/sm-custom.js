jQuery(document).ready(function($) {

    // Top bar menu toggle
    $('.sm-sec-nav').on('click', function() {
        $('.sm-sec-menu').toggleClass('sm-sec-menu-show');
        $(this).find('.bi').toggleClass('bi-list bi-x');
    });

    // Close menu when clicking outside
    $(document).on('click', function(e) {
        if ($(e.target).closest('.sm-bar-left, .sm-sec-nav').length === 0) {
            $('.sm-sec-menu').removeClass('sm-sec-menu-show');
            $('.sm-sec-nav .bi-x').addClass('bi-list').removeClass('bi-x');
        }
    });

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

    // Main Menu Mobile
    $('.sm-nav-md-trigger').on('click', function() {
        $('.sm-nav-md').toggleClass('sm-nav-md-expand');
        $(this).find('.bi').toggleClass('bi-list bi-x');
    });

    // Top Social Sticky bar
    $('.sm-social-trigger').on('click', function() {
        $('.sm-social-sticky-bar').toggleClass('sm-social-sticky-bar-show');
        $(this).find('.bi').toggleClass('bi-share bi-x');
    });

    $(document).on('click', function(e) {
        if ($(e.target).closest('.sm-social-sticky-bar, .sm-social-trigger').length === 0) {
            $('.sm-social-sticky-bar').removeClass('sm-social-sticky-bar-show');
            $('.sm-social-trigger .bi-x').addClass('bi-share').removeClass('bi-x');
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

    // Sticky Menu
    var stickyNavTop = $('.sm-sticky');
    if (!stickyNavTop.length) return;

    var p_to_top = stickyNavTop.offset().top;

    var stickyNav = function() {
        var scrollTop = $(window).scrollTop();
        var topbar = $('#wpadminbar').height() || 0;

        if (topbar > 0 && $('#wpadminbar').css('position') !== 'fixed') {
            topbar = 0;
        }

        if (scrollTop > p_to_top && scrollTop > 0) {
            $('.sm-sticky').addClass('sm-menu-bar-sticky');
            stickyNavTop.css('top', topbar + 'px');
        } else {
            $('.sm-sticky').removeClass('sm-menu-bar-sticky');
            stickyNavTop.css('top', 'auto');
        }
    };

    stickyNav();
    $(window).scroll(stickyNav);

    // Mobile sub menu toggle
    $('.sm-nav-md li.menu-item-has-children > span.nav-toggle-subarrow').on('click', function() {
        $(this).next('ul.sub-menu').slideToggle(500);
        $(this).toggleClass('active');
        return false;
    });

});
