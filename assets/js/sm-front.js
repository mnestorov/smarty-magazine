jQuery(document).ready(function($) {
    // Toggle submenu on click
    $("#mobile-primary-menu .menu-item-has-children > a").on("click", function (e) {
        e.preventDefault(); // Prevent default link behavior

        let $parent = $(this).parent();
        let $submenu = $parent.children(".sub-menu");

        // Toggle submenu visibility with animation
        if ($submenu.is(":visible")) {
            $submenu.slideUp(300);
            $parent.removeClass("open");
        } else {
            $submenu.slideDown(300);
            $parent.addClass("open");
        }
    });

    // Prevent Bootstrap Offcanvas from closing on submenu clicks
    $(".offcanvas").on("click", function (e) {
        if ($(e.target).closest(".menu-item-has-children").length) {
            e.stopPropagation();
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
    if ($('.sm-newsticker').length) {
        $('.sm-newsticker').newsTicker({
            row_height: 42,
            max_rows: 1,
            speed: 600,
            direction: 'up',
            duration: 3500,
            autostart: 1,
            pauseOnHover: 1
        });
    }

    // Initialize post slider
    new Swiper('.sm-featured-post-slider', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        speed: 600,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        }
    });

    // Table of Contents
    $(".sm-magazine-toc-link").on("click", function(event) {
        event.preventDefault();
        var target = $(this).attr("href");
        $("html, body").animate({
            scrollTop: $(target).offset().top - 100 // Adjust offset if needed
        }, 500);
    });

    // Back to Top
    if ($('#sm-back-to-top').length) {
        var scrollTrigger = 600, // px
            backToTop = function() {
                var scrollTop = $(window).scrollTop();
                if (scrollTop > scrollTrigger) {
                    $('#sm-back-to-top').addClass('show');
                } else {
                    $('#sm-back-to-top').removeClass('show');
                }
            };
        backToTop();
        $(window).on('scroll', backToTop);
        $('#sm-back-to-top').on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, 600);
        });
    }

    // Lazy load images
    $('img:not([loading])').each(function() {
        $(this).attr('loading', 'lazy');
    });

    // Dictionary page search
    $('#dictionary-search-input').on('keyup', function() {
        var search = $(this).val();
        $.ajax({
            url: smartyMagazine.ajaxUrl,
            type: 'POST',
            data: {
                action: 'dictionary_search',
                nonce: smartyMagazine.nonce,
                search: search
            },
            success: function(response) {
                if (response.success) {
                    $('#dictionary-results').html(response.data);
                    // Bootstrap accordion handles itself, no need to reinitialize
                }
            }
        });
    });
});
