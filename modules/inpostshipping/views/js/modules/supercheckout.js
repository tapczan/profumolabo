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
$(() => {
  if ('function' === typeof addSupercheckoutOrderValidator) {
    addSupercheckoutOrderValidator(() => {
      const $contentWrapper = $('.js-inpost-shipping-container:visible');

      if ($contentWrapper.length > 0) {
        const formData = new FormData();

        formData.append('action', 'updateChoice');
        $contentWrapper.find(':input').each((index, element) => {
          const $input = $(element);
          formData.append($input.attr('name'), $input.val());
        });

        $.ajax({
          async: false,
          method: 'post',
          url: inPostAjaxController,
          data: formData,
          processData: false,
          contentType: false,
          dataType: 'json'
        }).then((response) => {
          if (false === response.success) {
            const errors = $.map(response.errors, (error) => `<li>${error}</li>`).join('');

            throw {
              message: `<ul>${errors}</ul>`
            }
          }
        });
      }
    });
  }
});
