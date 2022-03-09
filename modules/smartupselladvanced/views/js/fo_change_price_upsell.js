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

  $('.js-upsell-product').each(function () {
    changePrice($(this));
  });

  $('.js-upsell-product').on('click', '.sua-input, .sua-radio-input', function (event) {
    var $cartObject = $(this).closest('.js-upsell-product');
    changePrice($cartObject);
  });

  function showOutOfStock($upsellCard, show, $outOfStockText) {
    var $addToCartButton = $upsellCard.find('.sua-add-to-cart-upsell');
    var $alertBox = $upsellCard.find('.js-sua-alert-box');
    var $alertText = $upsellCard.find('.js-sua-alert-text');

    if (show) {
      $alertBox.css('background-color', 'red');
      $alertText.html($outOfStockText);
      $addToCartButton.prop('disabled', true);
    } else {
      $alertBox.css('background-color', 'green');
      $alertText.html($outOfStockText);
      $addToCartButton.prop('disabled', false);
    }
  }

  function changePrice($cartObject) {

    var $productId = $cartObject.data('product-id');
    var $productAttributeId = $cartObject.find('.sua-input').val();

    $.ajax({
      url: $moduleLink,
      'method': 'GET',
      'data': {
        'token' : prestashop.static_token,
        'current_url' : prestashop.urls.current_url,
        'action': 'get_upsell_product_price',
        'productAttributeId': $productAttributeId,
        'productId': $productId,
      },
      'dataType': 'JSON',
      'success': function (response) {
        if (response.error != null) {
          return;
        }

        showOutOfStock($cartObject, response.is_out_of_stock, response.out_of_stock_text);

        if (response.discount != 0) {
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
