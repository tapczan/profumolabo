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

  // Hide time limit input
  if($('#unlimited').is(':checked')){
    $('#time_limit').closest('.form-group').hide();
  }

  $('#unlimited').click(function(){
    $('#time_limit').closest('.form-group').hide();
  });
  $('#limited').click(function(){
    $('#time_limit').closest('.form-group').show();
  });

  // Hide Datatime inputs
  if($('#is_valid_in_specific_interval_off').is(':checked')){
    $('#valid_from').closest('.form-group').hide();
    $('#valid_to').closest('.form-group').hide();
  }

  $('#is_valid_in_specific_interval_off').click(function(){
    $('#valid_from').closest('.form-group').hide();
    $('#valid_to').closest('.form-group').hide();
  });
  $('#is_valid_in_specific_interval_on').click(function(){
    $('#valid_from').closest('.form-group').show();
    $('#valid_to').closest('.form-group').show();
  });
});
