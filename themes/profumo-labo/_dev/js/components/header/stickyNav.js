var stickyNavTop = $('.header__nav').offset().top;
 
function stickyNav(){
    var removalStickyNavPoint = $('#blockEmailSubscription_displayFooterBefore').offset().top;
    var removalStickyNavPintHeight = $('#blockEmailSubscription_displayFooterBefore').innerHeight();
    var stickytNavHeigh = $('.header__nav').innerHeight();
    var scrollTop = $(window).scrollTop();

    if (scrollTop > stickyNavTop && scrollTop < (removalStickyNavPoint + removalStickyNavPintHeight - stickytNavHeigh)) { 
        $('.header__nav').addClass('header__nav--sticky');
        $('.sticky-menu-correction').addClass('correction-padding');
    } else {
        $('.header__nav').removeClass('header__nav--sticky');
        $('.sticky-menu-correction').removeClass('correction-padding');
    }
};

$(window).on('scroll', function() {
    stickyNav();
});

stickyNav();