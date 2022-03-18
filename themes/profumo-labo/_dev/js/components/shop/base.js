/**
 * Product category filter show and hide
 */
$('.js-filter-top-show').on('click', function () {
    $('.js-filter-wrapper').toggleClass('filter-wrapper--show');
    $('.js-listing-wrapper').toggleClass('listing-wrapper--default');
});