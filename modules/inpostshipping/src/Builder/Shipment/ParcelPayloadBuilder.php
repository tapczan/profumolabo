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

namespace InPost\Shipping\Builder\Shipment;

use InPost\Shipping\Configuration\CarriersConfiguration;
use InPost\Shipping\DataProvider\CarrierDataProvider;
use InPost\Shipping\DataProvider\OrderDimensionsDataProvider;
use InPost\Shipping\Helper\ParcelDimensionsComparator;
use InPost\Shipping\ShipX\Resource\Service;
use Order;

class ParcelPayloadBuilder
{
    protected $carriersConfiguration;
    protected $carrierDataProvider;
    protected $dimensionsDataProvider;
    protected $dimensionsComparator;

    public function __construct(
        CarriersConfiguration $carriersConfiguration,
        CarrierDataProvider $carrierDataProvider,
        OrderDimensionsDataProvider $dimensionsDataProvider,
        ParcelDimensionsComparator $dimensionsComparator
    ) {
        $this->carriersConfiguration = $carriersConfiguration;
        $this->carrierDataProvider = $carrierDataProvider;
        $this->dimensionsDataProvider = $dimensionsDataProvider;
        $this->dimensionsComparator = $dimensionsComparator;
    }

    public function buildPayloadFromRequestData(array $request)
    {
        if ($request['use_template']) {
            return [
                'template' => $request['template'],
            ];
        } else {
            return [
                'dimensions' => array_map(function ($dimension) {
                    return (float) str_replace(',', '.', $dimension);
                }, $request['dimensions']),
                'weight' => [
                    'amount' => (float) str_replace(',', '.', $request['weight']),
                ],
            ];
        }
    }

    public function buildPayloadByOrder(Order $order, $service)
    {
        if ($this->shouldUseProductDimensions($order) &&
            $parcel = $this->getParcelByProductDimensions($order, $service)
        ) {
            return $parcel;
        } elseif ($dimensions = $this->carriersConfiguration->getDefaultShipmentDimensions($service)) {
            return [
                'weight' => [
                    'amount' => $order->getTotalWeight() ?: $dimensions['weight'],
                ],
                'dimensions' => array_filter($dimensions, function ($key) {
                    return $key !== 'weight';
                }, ARRAY_FILTER_USE_KEY),
            ];
        } elseif ($template = $this->carriersConfiguration->getDefaultDimensionTemplates($service)) {
            return [
                'template' => $template,
            ];
        } else {
            return [
                'weight' => [
                    'amount' => $order->getTotalWeight(),
                ],
            ];
        }
    }

    protected function getParcelByProductDimensions(Order $order, $service)
    {
        $template = $this->getLargestTemplateByOrder($order, $service);
        $orderDimensions = $this->getDimensionsByLargestOrderProduct($order);

        if (null !== $template && (
            null === $orderDimensions ||
            $this->dimensionsComparator->compareTemplateWithDimensions($template, $orderDimensions) >= 0
        )) {
            return [
                'template' => $template,
            ];
        }

        return $orderDimensions;
    }

    protected function getLargestTemplateByOrder(Order $order, $service)
    {
        if (in_array($service, Service::LOCKER_SERVICES) &&
            $templates = $this->dimensionsDataProvider->getProductDimensionTemplatesByOrderId($order->id)
        ) {
            return $this->dimensionsComparator->getLargestTemplate($templates);
        }

        return null;
    }

    protected function getDimensionsByLargestOrderProduct(Order $order)
    {
        if ($dimensions = $this->dimensionsDataProvider->getLargestProductDimensionsByOrderId($order->id)) {
            return [
                'dimensions' => $dimensions,
                'weight' => [
                    'amount' => $order->getTotalWeight(),
                ],
            ];
        }

        return null;
    }

    protected function shouldUseProductDimensions(Order $order)
    {
        $inPostCarrier = $this->carrierDataProvider->getInPostCarrierByCarrierId($order->id_carrier);

        return null !== $inPostCarrier && $inPostCarrier->use_product_dimensions;
    }
}
