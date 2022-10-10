<?php
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

namespace InPost\Shipping\Hook;

use InPost\Shipping\Configuration\SzybkieZwrotyConfiguration;

class OrderHistory extends AbstractHook
{
    const HOOK_LIST = [
        'displayOrderDetail',
    ];

    public function hookDisplayOrderDetail()
    {
        /** @var SzybkieZwrotyConfiguration $configuration */
        $configuration = $this->module->getService('inpost.shipping.configuration.szybkie_zwroty');

        if ($link = $configuration->getOrderReturnFormUrl()) {
            $this->context->smarty->assign('returnFormUrl', $link);

            return $this->shopContext->is17()
                ? $this->module->display($this->module->name, 'views/templates/hook/order-detail.tpl')
                : $this->module->display($this->module->name, 'views/templates/hook/16/order-detail.tpl');
        }

        return '';
    }
}
