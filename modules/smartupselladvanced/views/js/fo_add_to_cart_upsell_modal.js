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

  var $moduleLink = $('.sua-module-link').data('module-link');

  $('.js-upsell-modal').on('click', '.sua-add-to-cart-upsell-modal', function (event) {

    var $cartObject = $(this).closest('.so-modal-display-div');

    var $productId = $cartObject.data('product-id');

    // Gets all values from dropdown and radio buttons.
    var $dropdownInputObject = $cartObject.find('.sua-input');
    var $radioInputObject = $cartObject.find("input[type='radio'][class='input-color sua-radio-input']:checked");
    var $quantityInput = $cartObject.find('.sua-quantity-input').val();
    var $dropdownAttributeId = [];
    var $radioAttributeId = [];

    if ($dropdownInputObject.length > 0) {
      $dropdownInputObject.each(function(){
        $dropdownAttributeId.push($(this).val());
      });
    }

    if ($radioInputObject.length > 0) {
      $radioInputObject.each(function(){
        $radioAttributeId.push($(this).val());
      });
    }

    $.ajax({
      url: $moduleLink,
      'method': 'POST',
      'data': {
        'token' : prestashop.static_token,
        'action': 'add_to_cart_upsell_modal',
        'dropdownAttributeId': $dropdownAttributeId,
        'radioAttributeId': $radioAttributeId,
        'productId': $productId,
        'quantity' : $quantityInput,
      },
      'dataType': 'JSON',
      'success': function (response) {
        if (response.error != null) {
          return;
        }
        window.location.href = response.cart_link;
      }
    });
  });
});
