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

  function closeFeedBack(feedbackBlock) {
    $.ajax({
      url: '',
      'method': 'POST',
      'data': {
        ajax: 1,
        'action': 'CloseFeedback',
      },
      'success': function (response) {
        $(feedbackBlock).parent().slideUp(300);
      }
    });
  }

  $(document).on('click', '.js-feedback', function () {
    closeFeedBack(this);
  });
});
