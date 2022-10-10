/*
* PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
*
* @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
* @copyright 2010-2021 VEKIA
* @license   This program is not free software and you can't resell and redistribute it
*
* CONTACT WITH DEVELOPER http://mypresta.eu
* support@mypresta.eu
*/

prestashop.on("updatedProduct", function (event) {
    if (typeof reinitaddtocart === 'function') {
        reinitaddtocart();
        reinitViewContent();
    }
});

function recalculatePrice(price, from, to, contents, type, currency) {
    $.post(prestashop.urls.base_url, {price: price, ajax: 1, fbpixel_recalculate_currency: 1, fbpixel_currency_from: from, fbpixel_currency_to: to}, function (data) {
        fbq('track', 'AddToCart', {content_ids: contents, content_type: type, value: data, currency: currency});
    });
}