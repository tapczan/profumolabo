/**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
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
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2018 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 *
 * Don't forget to prefix your containers with your own identifier
 * to avoid any conflicts with others containers.
 */

$(function () {
    var currentPsVersion = parseInt(_PS_VERSION_.replace('.', ''), 10);
    var wrapperClass = '.form-group';
    
    if (currentPsVersion === 15) {
        wrapperClass = '.margin-form';
    }

    var p = [
        $('[name*="X13_RECIEPTORINVOICE_BORDERCOLOR"]').parents(wrapperClass),
        $('[name*="X13_RECIEPTORINVOICE_BGCOLOR"]').parents(wrapperClass),
        $('[name*="X13_RECIEPTORINVOICE_TEXTCOLOR"]').parents(wrapperClass)
    ];

    function toggle() {
        var customStyling = parseInt($('input[name="X13_RECIEPTORINVOICE_CUSTOMSTYLE"]:checked').val(), 10);
        p.forEach(function (e) {
            if (!customStyling) {
                e.hide();
                if (currentPsVersion === 15) {
                    e.prev('label').hide();
                }
            } else {
                e.show();
                if (currentPsVersion === 15) {
                    e.prev('label').show();
                }
            }
            
        });
    }

    toggle();

    $('input[name="X13_RECIEPTORINVOICE_CUSTOMSTYLE"]').on('change', function () {
        toggle();
    });
});