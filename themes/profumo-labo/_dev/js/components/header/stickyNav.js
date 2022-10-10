$(window).on('load', function() {
    var stickyNavTop = $('.header__nav').offset().top;
    var removalStickyNavPoint = $('#blockEmailSubscription_displayFooterBefore').offset().top;
    var removalStickyNavPintHeight = $('#blockEmailSubscription_displayFooterBefore').innerHeight();
    var stickytNavHeight = $('.header__nav').innerHeight();

    function stickyNav(){
        var scrollTop = $(window).scrollTop();

        if (scrollTop > stickyNavTop && scrollTop <= (removalStickyNavPoint + removalStickyNavPintHeight - stickytNavHeight)) { 
            $('.header__nav').addClass('header__nav--sticky');
            $('.header__inner').addClass('header__nav--sticky-active');
            $('.sticky-menu-correction').addClass('correction-padding');
        } else {
            $('.header__nav').removeClass('header__nav--sticky');
            $('.header__inner').removeClass('header__nav--sticky-active');
            $('.sticky-menu-correction').removeClass('correction-padding');
        }

        if (scrollTop > stickyNavTop) { 
            $('#x13-counter-container').addClass('x13-counter-container-fixed');
        }else{
            $('#x13-counter-container').removeClass('x13-counter-container-fixed');
        }
    };

    $(window).on('scroll', function() {
        stickyNav();
    });

    stickyNav();
});
