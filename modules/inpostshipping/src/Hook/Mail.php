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

use InPost\Shipping\Configuration\OrdersConfiguration;
use InPost\Shipping\DataProvider\CustomerChoiceDataProvider;
use InPost\Shipping\DataProvider\PointDataProvider;
use InPost\Shipping\Presenter\PointAddressPresenter;
use InPost\Shipping\Presenter\PointPresenter;
use InPost\Shipping\ShipX\Resource\Service;
use Order;

class Mail extends AbstractHook
{
    const HOOK_LIST = [
        'actionGetExtraMailTemplateVars',
    ];

    protected $orderIndex = [];

    public function hookActionGetExtraMailTemplateVars($params)
    {
        switch ($params['template']) {
            case 'new_order':
                $this->modifyNewOrderTemplateVariables($params);
                break;
            case 'order_conf':
                $this->modifyOrderConfirmationTemplateVariables($params);
                break;
            default:
                break;
        }
    }

    protected function modifyNewOrderTemplateVariables($params)
    {
        if (($order = $this->getOrderByReference($params['template_vars']['{order_name}'])) &&
            $pointName = $this->getPointNameByCartId($order->id_cart)
        ) {
            /** @var PointDataProvider $pointDataProvider */
            $pointDataProvider = $this->module->getService('inpost.shipping.data_provider.point');

            if ($point = $pointDataProvider->getPointData($pointName)) {
                /** @var PointAddressPresenter $pointAddressPresenter */
                $pointAddressPresenter = $this->module->getService('inpost.shipping.presenter.point_address');

                $params['extra_template_vars'] = array_merge($params['extra_template_vars'], [
                    '{delivery_block_txt}' => $pointAddressPresenter->present($point, true, $params['id_lang']),
                    '{delivery_block_html}' => $pointAddressPresenter->present($point, false, $params['id_lang']),
                ]);
            }
        }
    }

    protected function modifyOrderConfirmationTemplateVariables($params)
    {
        /** @var OrdersConfiguration $configuration */
        $configuration = $this->module->getService('inpost.shipping.configuration.orders');

        if ($configuration->shouldDisplayOrderConfirmationLocker() &&
            ($order = $this->getOrderByReference($params['template_vars']['{order_name}'])) &&
            $pointName = $this->getPointNameByCartId($order->id_cart)
        ) {
            /** @var PointPresenter $pointPresenter */
            $pointPresenter = $this->module->getService('inpost.shipping.presenter.point');

            $carrierName = isset($params['extra_template_vars']['{carrier}'])
                ? $params['extra_template_vars']['{carrier}']
                : $params['template_vars']['{carrier}'];

            $params['extra_template_vars']['{carrier}'] = sprintf(
                '%s (%s)',
                $carrierName,
                $pointPresenter->present($pointName, $params['id_lang'], '%s [%s]')
            );
        }
    }

    /** @return Order|null */
    protected function getOrderByReference($reference)
    {
        if (!isset($this->orderIndex[$reference])) {
            $this->orderIndex[$reference] = Order::getByReference($reference)->getFirst();
        }

        return $this->orderIndex[$reference] ?: null;
    }

    protected function getPointNameByCartId($id_cart)
    {
        /** @var CustomerChoiceDataProvider $customerChoiceDataProvider */
        $customerChoiceDataProvider = $this->module->getService('inpost.shipping.data_provider.customer_choice');

        if (($customerChoice = $customerChoiceDataProvider->getDataByCartId($id_cart)) &&
            $customerChoice->service === Service::INPOST_LOCKER_STANDARD
        ) {
            return $customerChoice->point;
        }

        return null;
    }
}
