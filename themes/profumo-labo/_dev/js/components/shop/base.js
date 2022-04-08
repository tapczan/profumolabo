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

/**
 * Single page on click alert trigger for email
 */
$('#email-alert-modal').on('shown.bs.modal', function (e) {
    $('body').addClass('modal-body-single-product');
});

$('#email-alert-modal').on('hidden.bs.modal', function (e) {
    $('body').removeClass('modal-body-single-product');
});