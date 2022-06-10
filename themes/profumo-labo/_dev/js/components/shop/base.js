/**
 * Product category filter show and hide
 */
$('.js-filter-top-show').on('click', function () {
    $('.js-filter-wrapper').toggleClass('filter-wrapper--show');
    $('.js-listing-wrapper').toggleClass('listing-wrapper--default');

    if( $('.js-filter-wrapper').hasClass('filter-wrapper--show') ) {
        $('.js-filter-top-show').addClass('open');

        if(prestashop.language.iso_code == "pl") {
            $('.js-filter-top-show').html('ZWIŃ FILTRY');
        } else {
            $('.js-filter-top-show').html('FILTER BY');
        }
    } else {
        $('.js-filter-top-show').removeClass('open');
        if(prestashop.language.iso_code == "pl") {
            $('.js-filter-top-show').html('ROZWIŃ FILTRY');
        } else {
            $('.js-filter-top-show').html('FILTER BY');
        }
    }
});

$('#drogeria').on('click', function() {
    var url = $(this).attr('data-url'),
        checkbox = $('input:checkbox[name=drogeria]');
    if(checkbox.prop("checked")) {
        location.replace(url);
    } 
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