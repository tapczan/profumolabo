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

  var initializeValues = (function() {
    var executed = false;
    return function() {
      if (!executed) {
        executed = true;
        loadSelectedProductCards();
      }
    };
  })();

  initializeValues();

  $(document).on('click', '.product-link', function (event) {
    event.preventDefault();// prevents from reloading the page
    selectProduct($(this).closest('.list-group-item'));
  });

  $(document).on('click', '.js-sua-close-item', function (event) {
    event.preventDefault();// prevents from reloading the page
    unselectProduct($(this).closest('.form-group'));
  });

  function selectProduct($selectedInputRoot)
  {
    var $formGroup = $selectedInputRoot.closest('.form-group');

    var selectedProductId = $selectedInputRoot.find('.product-link').data('product-id');
    var inputFieldName = $formGroup.find('input.product-search').attr('id').slice(0, -14)+'_product';
    var secretInputDivId = '#id_' + inputFieldName;
    var searchInputDivId = '#'+inputFieldName+'_input';

    $formGroup.find(secretInputDivId).attr('value', selectedProductId);
    $formGroup.find(searchInputDivId).val('');

    $formGroup.find('.product-link').each(function() {
      if($(this).data('product-id') != selectedProductId){
        $(this).closest('.list-group-item').remove();
      }
      if($(this).data('product-id') == selectedProductId){
        $(this).closest('.list-group-item').find('.js-sua-close-item').show();
      }
    });

    $formGroup.find('.product-search').hide();
  }

  function unselectProduct($selectedInputRoot)
  {
    $selectedInputRoot.find('.product-search').show();
    $selectedInputRoot.find('.list-group').remove();
    $selectedInputRoot.find('.js-sua-search-field-product-id').val('');
  }

  function loadSelectedProductCards()
  {
    var searchInputFields = $('.product-search');

    searchInputFields.each(function () {
      var $currentInputField = $(this);
      var $selectedInputRoot = $currentInputField.closest('.form-group');
      var $searchOutputContainer = $currentInputField.closest('.form-group').find('.search_results');

      var selectedProductName = $(this).val();

      $.ajax({
        url: '',
        'method': 'GET',
        'data': {
          'action' : 'search_products',
          'query': selectedProductName
        },
        'dataType': 'html',
        'success': function (response) {
          if(response != 'no_products'){
            $searchOutputContainer.html(response);
            selectProduct($selectedInputRoot);
          }
        }
      });
    });
  }
});
