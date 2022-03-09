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

  $('.so-display-div').each(function(){
    changePrice($(this));
  });

  $(document).on('click', '.sua-input, .sua-radio-input', function (event) {
    var $cartObject = $(this).closest('.so-display-div');
    changePrice($cartObject);
  });

  function showOutOfStock($specialOfferCard, show)
  {
    var $outOfStockPopUp = $specialOfferCard.find('.js-sua-out-of-stock-box');
    var $addToCartButton = $specialOfferCard.find('.sua-add-to-cart');

    if(show){
      $outOfStockPopUp.show();
      $addToCartButton.prop('disabled', true);
    } else {
      $outOfStockPopUp.hide();
      $addToCartButton.prop('disabled', false);
    }
  }

  function changePrice($cartObject){

    var $productId = $cartObject.data('product-id');
    var $specialProductId = $cartObject.data('special-product-id');
    var $specialOfferId = $cartObject.data('special-offer-id');
    // Gets all values from dropdown and radio buttons.
    var $dropdownInputObject = $cartObject.find('.sua-input');
    var $radioInputObject = $cartObject.find("input[type='radio'][class='input-color sua-radio-input']:checked");
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
        'action': 'get_product_price',
        'dropdownAttributeId': $dropdownAttributeId,
        'radioAttributeId': $radioAttributeId,
        'productId': $productId,
        'specialOfferId': $specialOfferId,
        'specialProductId': $specialProductId
      },
      'dataType': 'JSON',
      'success': function (response) {
        showOutOfStock($cartObject, response.is_out_of_stock);
        if (response.error != null) {
          return;
        } else if (response.discount) {
          $cartObject.find('.regular-price').html(response.full_price);
          $cartObject.find('.price').html(response.discount.discounted_price);
          $cartObject.find('.discount-percentage').html('-' + response.discount.discount_percent);
        } else {
          $cartObject.find('.price').html(response.full_price);

        }
      }
    });
  }

});
