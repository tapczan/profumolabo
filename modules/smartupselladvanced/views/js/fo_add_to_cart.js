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

  $(document).on('click', '.sua-add-to-cart', function () {

    var $cartObject = $(this).closest('.so-display-div');

    var $productId = $cartObject.data('product-id');
    var $specialOfferProductId = $cartObject.data('special-product-id');

    // Gets all values from dropdown and radio buttons.
    var $dropdownInputObject = $cartObject.find('.sua-input');
    var $radioInputObject = $cartObject.find("input[type='radio'][class='input-color sua-radio-input']:checked");
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
        'current_url' : prestashop.urls.current_url,
        'action': 'add_to_cart',
        'dropdownAttributeId': $dropdownAttributeId,
        'radioAttributeId': $radioAttributeId,
        'productId': $productId,
        'specialOfferId': $specialOfferProductId
      },
      'dataType': 'JSON',
      'success': function (response) {
        if (response.error != null) {
          return;
        }

        //@todo maybe it's possible to reload the content via javascript?
        location.reload();
      }
    });
  });

  $(document).on('click', '.remove-from-cart', function (event) {
    //@todo instead of reloading refresh the content via ajax
    location.reload();
  });
});
