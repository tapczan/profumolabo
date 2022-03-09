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
  var searchableInputFieldNames = ['main_product', 'special_product', 'product_search_query'];

  // Checks if input field needs to make product search
  function productSearch(inputTargetId) {

    var clearSearch = function (divId) {
      $('#' + divId).html('');
    };

    for (i in searchableInputFieldNames) {// Iterates over searchable input fields

      if (inputTargetId == searchableInputFieldNames[i] + '_input') { // Checks if input field has product search function

        var inputFieldId = searchableInputFieldNames[i] + '_input';// Current input field id
        var resultDivId = searchableInputFieldNames[i] + '_search_result';

        $query = $('#' + inputFieldId).val();

        if ($query.length < 3) {
          clearSearch(resultDivId);
          return;
        }

        $.ajax({
          url: '',
          'method': 'GET',
          'data': {
            'action' : 'search_products',
            'query': $query
          },
          'dataType': 'html',
          'success': function (response) {
            $('#' + resultDivId).html(response);
          }
        });
      }
    }
  }

  $(document).on('keyup', 'input', function (event) {// On any input key up run
    var targetId = event.target.id;
    productSearch(targetId);
  });
});
