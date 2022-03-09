/**
 * NOTICE OF LICENSE
 *
 * @author    INVERTUS, UAB www.invertus.eu <support@invertus.eu>
 * @copyright Copyright (c) permanent, INVERTUS, UAB
 * @license   Addons PrestaShop license limitation
 * @see       /License
 *
 * International Registered Trademark & Property of INVERTUS, UAB
 */

$(document).ready(function () {
  function createForm(currentProduct, upsellProduct, buttonClass){
    var f = document.createElement("form");
    f.setAttribute('method',"post");
    f.setAttribute('action',"")

    var $form = $('<form>', {
      'action': '',
      'method':'POST',
    }).appendTo('body');

    $form.append($('<input>', {
      'type': 'hidden',
      'name': 'selected_product_id',
      'value': currentProduct
    }));

    $form.append($('<input>', {
      'type': 'hidden',
      'name': 'upsell_product_id',
      'value': upsellProduct
    }));

    $form.append($('<input>', {
      'type': 'hidden',
      'name': 'button_class',
      'value': buttonClass
    }));

    $form.submit();
  }

  $(document).on('click','.js-set-upsell-btn', function (event) {
    selectedProductID = $(this).data('select-product');
    upsellProductID = $(this).data('upsell-product');
    buttonClass = 'js-set-upsell-btn';
    createForm(selectedProductID, upsellProductID, buttonClass);
  });
  $(document).on('click','.js-unset-upsell-btn', function (event) {
    selectedProductID = $(this).data('select-product');
    upsellProductID = $(this).data('upsell-product');
    buttonClass = 'js-unset-upsell-btn';
    createForm(selectedProductID, upsellProductID, buttonClass);
  });
});
