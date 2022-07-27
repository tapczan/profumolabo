/**
 * Toggle search bar on mobile
 */
$('.jsSearchToggleMobile').on('click', function(){
    $('.jsMobileSearch').toggle();
});

/**
 * Header search inputs
 */
$('.header__inner .js-search-input').keyup(function () {
    const value = $(this).val();

    $(".header__nav .js-search-input").val(value);
});

$('.header__nav .js-search-input').keyup(function () {
    const value = $(this).val();

    $(".header__inner .js-search-input").val(value);
});

/**
 * Header mobile marki collapse
 */
$('.mobile-menu__accordion-header').on('click', function(){
    $('.mobile-menu__accordion-item').stop().removeClass('mobile-menu__accordion-item--active');
    $(this).closest('.mobile-menu__accordion-item').stop().toggleClass('mobile-menu__accordion-item--active');
});


/**
 * Smooth scroll on onas/about page
 */
function smoothScrollingTo(target){
    $('html,body').animate({scrollTop:$(target).offset().top - 50}, 500);
}

function smoothScrollingToMobile(target){
    $('html,body').animate({scrollTop:$(target).offset().top - 100}, 500);
}

function smoothScrolling(target){
    const windowWidth = $(window).outerWidth();
    
    if(target) {
        if(windowWidth < 768){
            smoothScrollingToMobile(target);
        }else{
            smoothScrollingTo(target);
        }
    }
}

$('.mega-menu-header-about a[href*="#"]').on('click', function(){  
    const windowWidth = $(window).outerWidth();

    if(windowWidth < 992){
        $('.ybc-menu-button-toggle_icon').trigger('click');
    }

    if($('.cms-container--onas').length){
        smoothScrolling(this.hash);
    }
});

smoothScrolling(window.location.hash);

$(window).on('load', function(){
    smoothScrolling(window.location.hash);
});

/**
 * Change login menu item text for login users
 */
$('.js-header-user-login .mobile-menu__main-item.mobile-menu__main-border.lm .mm_menu_content_title').text('moje konto');