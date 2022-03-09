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

$(window).on('load', function () {

  var $moduleLink = $('.sua-module-link').data('module-link');

  var $cartObject = $('.product-information');
  isProductOutOfStock($cartObject);

  $('.product-actions').on('click', '.input-color, .form-control-select', function (event) {
    var $cartObject = $(this).closest('.product-information');
    isProductOutOfStock($cartObject);
  });

  function isProductOutOfStock($productObject) {

    var $productId = $productObject.find('#product_page_product_id').attr('value');

    // // Gets all values from dropdown and radio buttons.
    var $dropdownInputObject = $productObject.find('.form-control-select');
    var $radioInputObject = $productObject.find("input[type='radio'][class='input-color']:checked");
    var $dropdownAttributeId = [];
    var $radioAttributeId = [];

    if ($dropdownInputObject.length > 0) {
      $dropdownInputObject.each(function () {
        $dropdownAttributeId.push($(this).val());
      });
    }

    if ($radioInputObject.length > 0) {
      $radioInputObject.each(function () {
        $radioAttributeId.push($(this).val());
      });
    }

    $.ajax({
      url: $moduleLink,
      'method': 'GET',
      'data': {
        'token' : prestashop.static_token,
        'action': 'is_product_in_stock',
        'dropdownAttributeId': $dropdownAttributeId,
        'radioAttributeId': $radioAttributeId,
        'productId': $productId,
      },
      'dataType': 'JSON',
      'success': function (response) {
        if (response.error != null) {
          return;
        }
        if (response.available_to_buy == false) {
          $('.js-upsell-modal').modal('show');
        }
      }
    });
  }
});
