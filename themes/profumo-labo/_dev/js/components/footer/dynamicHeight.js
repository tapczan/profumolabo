/*
* Footer dynamic height and spaces
*/
function dynamicSpaceAndWidth(){
    const winHeight = parseInt($(window).innerHeight());
    const footerItemsHeight = parseInt($('.js-footer-items').innerHeight());
    const footerLogoHeightBase = ((winHeight - footerItemsHeight) / 4) * 3;
    const marginLogoBase = ((winHeight - footerItemsHeight) / 4) / 2;

    $('.js-footer-logo-img').css({
        'height' : footerLogoHeightBase,
        'margin-top' : marginLogoBase,
        'margin-bottom' : marginLogoBase
    });
}

dynamicSpaceAndWidth();

$(window).on('load resize', function(){
    dynamicSpaceAndWidth();
});

$('#accordionFooter').on('shown.bs.collapse', function (e) {
    dynamicSpaceAndWidth();
});

$('#accordionFooter').on('hidden.bs.collapse', function (e) {
    dynamicSpaceAndWidth();
});