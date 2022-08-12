function addClassReviewPopUp(){
    $('.js-product-comment-form--overlay').addClass('show-popup');
    $('.header__inner, .header__nav').addClass('product-comment__form--overlay-header');
    $('body, html').addClass('overflow-hidden');
    $('.l-header').addClass('less-z-index');
    $('.sticky-menu-correction').addClass('more-z-index');
}

function removeClassReviewPopUp(){
    $('.js-product-comment-form--overlay').removeClass('show-popup');
    $('.header__inner, .header__nav').removeClass('product-comment__form--overlay-header');
    $('body, html').removeClass('overflow-hidden');
    $('.l-header').removeClass('less-z-index');
    $('.sticky-menu-correction').removeClass('more-z-index');
}

$('.js-product-comment-btn-form').on('click', function(){
    addClassReviewPopUp();
});

$(document).on('mouseup', function(e) {
    if (!$('.js-product-comment-form').is(e.target) && $('.js-product-comment-form').has(e.target).length === 0 ) {
        removeClassReviewPopUp();
    }
});

$('.js-product-comment-form-close').on('click', function(){
    removeClassReviewPopUp();
});

$(document).on('keyup', function(e) {
    if (e.key === "Escape") { 
        removeClassReviewPopUp();
    }
});

/*
* Comment section close
*/
$('.js-comment-close').on('click', function(){
    $('.js-comment-form').slideToggle();
    $(this).toggleClass('product-comment__close--notactive')
});

/*
* Clear comment input fields and close popup form on submit
*/
const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutationRecord) {
        setTimeout(() => {
            removeClassReviewPopUp();
            $('.js-input-comment, .js-textarea-comment').val('');
        }, 100);
    });    
});

const targetElement = document.getElementsByClassName('js-comment-alert')[0];

if( targetElement ){
    observer.observe(targetElement, { attributes : true, attributeFilter : ['style'] });
}
