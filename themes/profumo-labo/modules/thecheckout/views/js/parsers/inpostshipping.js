/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Software License Agreement
 * that is bundled with this package in the file LICENSE.txt.
 *
 *  @author    Peter Sliacky (Zelarg)
 *  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

tc_confirmOrderValidations['inpostshipping'] = function () {
    var targetMachine = $('.delivery-option.inpostshipping input[type=radio]:checked').parents('.delivery-option-row').next('.inpostshipping').find('.js-inpost-shipping-machine-name').text()
    var targetName = $('.delivery-option.inpostshipping input[type=radio]:checked').parents('.delivery-option-row').find('.carrier-name').text()
    
    if (
        $('.delivery-option.inpostshipping input[type=radio]').is(':checked') &&
        "" == jQuery.trim(targetMachine) && targetName.indexOf("Kurier") < 0
    ) {
        var shippingErrorMsg = $('#thecheckout-shipping > .inner-area > .error-msg');
        $('.shipping-validation-details').remove();
        shippingErrorMsg.append('<span class="shipping-validation-details"> (Paczkomat)</span>')
        shippingErrorMsg.show();
        scrollToElement(shippingErrorMsg);
        return false;
    } else {
        return true;
    }
}

checkoutShippingParser.inpostshipping = {
   

}