/**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */
import $ from 'jquery';
import prestashop from 'prestashop';

function setUpCheckout() {
  $(prestashop.themeSelectors.checkout.termsLink).on('click', (event) => {
    event.preventDefault();
    let url = $(event.target).attr('href');

    if (url) {
      // TODO: Handle request if no pretty URL
      url += '?content_only=1';
      $.get(url, (content) => {
        $(prestashop.themeSelectors.modal)
          .find(prestashop.themeSelectors.modalContent)
          .html($(content).find('.page-cms').contents());
      }).fail((resp) => {
        prestashop.emit('handleError', {eventType: 'clickTerms', resp});
      });
    }

    $(prestashop.themeSelectors.modal).modal('show');
  });

  $('.js-gift-checkbox').on('click', () => {
    $('#gift').collapse('toggle');
  });
}

$(document).on('ready', () => {
  if ($('body#checkout').length === 1) {
    setUpCheckout();
  }

  prestashop.on('updatedDeliveryForm', (params) => {
    if (typeof params.deliveryOption === 'undefined' || params.deliveryOption.length === 0) {
      return;
    }

    // Hide all carrier extra content ...
    $(prestashop.themeSelectors.checkout.carrierExtraContent).hide();
    // and show the one related to the selected carrier
    params.deliveryOption.next(prestashop.themeSelectors.checkout.carrierExtraContent).slideDown();
  });
  prestashop.on('changedCheckoutStep', (params) => {
    if (typeof params.event.currentTarget !== 'undefined') {
      $('.collapse', params.event.currentTarget).not('.show').not('.collapse .collapse').collapse('show');
    }
  });
  
});

$(document).on('change', '.checkout-option input[type="radio"]', (event) => {
  const $target = $(event.currentTarget);
  const $block = $target.closest('.checkout-option');
  const $relatedBlocks = $block.parent();

  $relatedBlocks.find('.checkout-option').removeClass('selected');
  $block.addClass('selected');
});

$(document).on('click', '.js-checkout-step-header', (event) => {
  const stepIdentifier = $(event.currentTarget).data('identifier');
  $(`#${stepIdentifier}`).addClass('-current');
  $(`#content-${stepIdentifier}`).collapse('show').scrollTop();
});

$(document).on('focusout', 'form.account-fields input[name=email]', () => {
  $('span.js-inpost-shipping-customer-info-email').html( $('form.account-fields input[name=email]').val() );
  $('input[name=inpost_email]').val( $('form.account-fields input[name=email]').val() );
});

$(document).on('focusout', 'form.address-fields input[name=phone]', () => {
  $('span.js-inpost-shipping-customer-info-phone').html( $('form.address-fields input[name=phone]').val() );
  $('input[name=inpost_phone]').val( $('form.address-fields input[name=phone]').val() ); 
});

$(document).on('ajaxComplete', () => {
  //force reset email phone
  var email = $('form.account-fields input[name=email]').val(),
      phone = $('form.address-fields input[name=phone]').val();

  if( email === '') {
    $('input[name=inpost_email]').val('');
    $('span.js-inpost-shipping-customer-info-email').html('');
  } else {
    $('input[name=inpost_email]').val( email );
    $('span.js-inpost-shipping-customer-info-email').html( email );
  }

  if( phone === '') {
    $('input[name=inpost_phone]').val(''); 
    $('span.js-inpost-shipping-customer-info-phone').html( '' );
  } else {
    $('input[name=inpost_phone]').val( phone ); 
    $('span.js-inpost-shipping-customer-info-phone').html( phone );
  }

});