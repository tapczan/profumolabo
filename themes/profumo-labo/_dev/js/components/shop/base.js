/**
 * Product category filter show and hide
 */
$('.js-filter-top-show').on('click', function () {
    $('.js-filter-wrapper').toggleClass('filter-wrapper--show');
    $('.js-listing-wrapper').toggleClass('listing-wrapper--default');
});

/**
 * Add target blank on terms and condition link for
 * order summary page
 */
if('.js-anchor-target-blank'){
    $('.js-anchor-target-blank a').each(function(){
        $(this).attr('target', '_blank');
    });
}

const checkoutCartGridHeader = $('.cart-grid__header').text().trim();
const checkoutPage = $('#thecheckout-cart-summary');

if(checkoutPage){
    checkoutPage.attr('data-header', checkoutCartGridHeader);
}