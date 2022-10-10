/**
 * Copyright 2021-2022 InPost S.A.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the EUPL-1.2 or later.
 * You may not use this work except in compliance with the Licence.
 *
 * You may obtain a copy of the Licence at:
 * https://joinup.ec.europa.eu/software/page/eupl
 * It is also bundled with this package in the file LICENSE.txt
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the Licence is distributed on an AS IS basis,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the Licence for the specific language governing permissions
 * and limitations under the Licence.
 *
 * @author    InPost S.A.
 * @copyright 2021-2022 InPost S.A.
 * @license   https://joinup.ec.europa.eu/software/page/eupl
 */
$(function () {
    const inpostChooseMachineButtonSelector = '.js-inpost-shipping-choose-machine';
    const inpostCustomerChangeButtonSelector = '.js-inpost-shipping-customer-change';
    const inpostCustomerSaveButtonSelector = '.js-inpost-shipping-customer-form-save-button';
    const inpostCustomerEmailSelector = '.js-inpost-shipping-email';
    const inpostCustomerPhoneSelector = '.js-inpost-shipping-phone';
    const inpostCustomerInfoEmail = $('.js-inpost-shipping-customer-info-email');
    const inpostCustomerInfoPhone = $('.js-inpost-shipping-customer-info-phone');
    const map = new InPostShippingModalMap();

    $(document).on('click', inpostChooseMachineButtonSelector, function (e) {
        e.preventDefault();

        const $that = $(this);
        const $paczkomatInput = $that.parents('.carrier-extra-content').find('.js-inpost-shipping-input');
        const payment = parseInt($that.attr('data-inpost-shipping-payment'));
        const weekendDelivery = parseInt($that.attr('data-inpost-shipping-weekend-delivery'));

        map.openMap({
            payment: payment,
            weekendDelivery: weekendDelivery,
            pointName: $paczkomatInput.val(),
            callback: function (point) {
                const $choosedMethod = $paczkomatInput.parents('.carrier-extra-content');
                const $machineInfo = $choosedMethod.find('.js-inpost-shipping-machine-info');
                const $customerInfo = $choosedMethod.find('.js-inpost-shipping-machine-customer-info');
                const $machineName = $choosedMethod.find('.js-inpost-shipping-machine-name');
                const $machineAddress = $choosedMethod.find('.js-inpost-shipping-machine-address');
                const $inpostChooseMachineButton = $choosedMethod.find('.js-inpost-shipping-choose-machine');
                const $errorsContainer = $choosedMethod.find('.js-inpost-shipping-locker-errors');
                const inpostChooseMachineButtonSelectorText = $inpostChooseMachineButton.attr('data-inpost-shipping-existing-text');

                const formData = new FormData();
                formData.append($paczkomatInput.attr('name'), point.name);
                formData.append('action', 'updateTargetLocker');

                $.ajax({
                    method: 'post',
                    url: inPostAjaxController,
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            $errorsContainer.html('');
                            $machineName.html(point.name);
                            $machineAddress.html(`${point.address.line1}, ${point.address.line2}`);
                            $machineInfo.removeClass('hidden');
                            $customerInfo.removeClass('hidden');
                            $inpostChooseMachineButton.html(inpostChooseMachineButtonSelectorText);

                            $paczkomatInput.val(point.name);
                        } else if ('locker' in response.errors) {
                            $errorsContainer.html(`<li class="alert alert-danger">${response.errors.locker}</li>`);
                        } else {
                            alert(response.errors[0]);
                        }
                    },
                });
            }
        });

        $('.widget-modal').parent('div').addClass('inpost-shipping-backdrop');
    });

    $(document).on('click', inpostCustomerChangeButtonSelector, function () {
        const $that = $(this);
        const $inpostCustomerChangeForm = $that.parents('.carrier-extra-content').find('.inpost-shipping-customer-change-form');

        $inpostCustomerChangeForm.slideToggle(300);
    })

    $(document).on('click', inpostCustomerSaveButtonSelector, function () {
        const $that = $(this);
        const $inpostCustomerChangeForm = $that.parents('.carrier-extra-content').find('.inpost-shipping-customer-change-form');
        const $emailField = $inpostCustomerChangeForm.find(inpostCustomerEmailSelector);
        const $emailGroup = $emailField.closest('.form-group');
        const $emailErrorsContainer = $emailGroup.find('.help-block ul');
        const $phoneField = $inpostCustomerChangeForm.find(inpostCustomerPhoneSelector);
        const $phoneGroup = $phoneField.closest('.form-group');
        const $phoneErrorsContainer = $phoneGroup.find('.help-block ul');

        const formData = new FormData();
        formData.append($emailField.attr('name'), $emailField.val());
        formData.append($phoneField.attr('name'), $phoneField.val());
        formData.append('action', 'updateReceiverDetails');

        $.ajax({
            method: 'post',
            url: inPostAjaxController,
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    $emailGroup.removeClass('has-errors');
                    $emailErrorsContainer.html('');
                    $phoneGroup.removeClass('has-errors');
                    $phoneErrorsContainer.html('');
                    $inpostCustomerChangeForm.slideUp(300);
                } else {
                    if ('email' in response.errors) {
                        $emailGroup.addClass('has-errors');
                        $emailErrorsContainer.html(`<li class="alert alert-danger">${response.errors.email}</li>`);
                    }
                    if ('phone' in response.errors) {
                        $phoneGroup.addClass('has-errors');
                        $phoneErrorsContainer.html(`<li class="alert alert-danger">${response.errors.phone}</li>`);
                    }
                }
            },
        });
    });

    $(document).on('input', inpostCustomerEmailSelector, function () {
        let val = $(this).val();

        $(inpostCustomerEmailSelector).val(val);
        inpostCustomerInfoEmail.html(val);
    });

    $(document).on('input', inpostCustomerPhoneSelector, function () {
        let val = $(this).val();

        $(inpostCustomerPhoneSelector).val($(this).val());
        inpostCustomerInfoPhone.html(val);
    });
});
