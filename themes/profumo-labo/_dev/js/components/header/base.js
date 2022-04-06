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