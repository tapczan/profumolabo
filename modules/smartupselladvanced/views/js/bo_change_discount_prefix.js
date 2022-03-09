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

  $(document).on('click', '#discount_type', function (event) {
    var $discountType = $(this).val();

    $.ajax({
      url: '',
      'method': 'GET',
      'data': {
        'action': 'change_discount_prefix',
        'js_discount_type' : $discountType
      },
      'dataType': 'JSON',
      'success': function (response) {
        $('.input-group-addon').html(response.discount_prefix);
      }
    });
  });
});
