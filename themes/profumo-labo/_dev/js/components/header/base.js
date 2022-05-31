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

$('.mega-menu-header-about a[href*="#"]').on('click', function(){  
    const windowWidth = $(window).outerWidth();

    if(windowWidth < 992){
        $('.ybc-menu-button-toggle_icon').trigger('click');
    }

    if(windowWidth < 768){
        smoothScrollingToMobile(this.hash);
    }else{
        smoothScrollingTo(this.hash);
    }
});

$(window).on('load', function(){
    const windowWidth = $(window).outerWidth();
    
    if(windowWidth < 768){
        smoothScrollingToMobile(this.hash);
    }else{
        smoothScrollingTo(this.hash);
    }
});

$('#arpl-group-8 .arpl-header').append(`<div class="arplekstrakty">
                                            <p>
                                                Produkty tworzone z myślą o kobietach. Aż <b>40% kompozycji zapachowej</b> oraz <b>0% alkoholu. Alternatywa dla wód perfumowanych</b>, dzięki której <b>unikniesz podrażnień i starzenia się delikatnej skóry szyi</b>.
                                            </p>
                                        </div>`);