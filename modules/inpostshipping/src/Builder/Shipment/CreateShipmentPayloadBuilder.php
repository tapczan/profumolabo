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

use Address;
use Country;
use Currency;
use InPost\Shipping\Configuration\CarriersConfiguration;
use InPost\Shipping\Configuration\SendingConfiguration;
use InPost\Shipping\DataProvider\CarrierDataProvider;
use InPost\Shipping\DataProvider\CustomerChoiceDataProvider;
use InPost\Shipping\Helper\DefaultShipmentReferenceExtractor;
use InPost\Shipping\ShipX\Resource\SendingMethod;
use InPost\Shipping\ShipX\Resource\Service;
use Order;

class CreateShipmentPayloadBuilder
{
    protected $sendingConfiguration;
    protected $customerChoiceDataProvider;
    protected $carriersConfiguration;
    protected $carrierDataProvider;
    protected $referenceExtractor;
    protected $parcelPayloadBuilder;

    public function __construct(
        SendingConfiguration $sendingConfiguration,
        CustomerChoiceDataProvider $customerChoiceDataProvider,
        CarriersConfiguration $carriersConfiguration,
        CarrierDataProvider $carrierDataProvider,
        DefaultShipmentReferenceExtractor $referenceExtractor,
        ParcelPayloadBuilder $parcelPayloadBuilder
    ) {
        $this->sendingConfiguration = $sendingConfiguration;
        $this->customerChoiceDataProvider = $customerChoiceDataProvider;
        $this->carriersConfiguration = $carriersConfiguration;
        $this->carrierDataProvider = $carrierDataProvider;
        $this->referenceExtractor = $referenceExtractor;
        $this->parcelPayloadBuilder = $parcelPayloadBuilder;
    }

    public function buildPayload(Order $order, array $request = [])
    {
        $currency = Currency::getCurrencyInstance($order->id_currency);

        if (!empty($request)) {
            $payload = $this->buildPayloadFromRequestData($request, $currency);
        } else {
            $payload = $this->buildPayloadFromOrderData($order, $currency);
        }

        if (!empty($payload)) {
            $address = new Address($order->id_address_delivery);

            $payload['receiver'] = array_merge($payload['receiver'], [
                'first_name' => $address->firstname,
                'last_name' => $address->lastname,
                'address' => [
                    'line1' => $address->address1,
                    'line2' => $address->address2,
                    'city' => $address->city,
                    'post_code' => $address->postcode,
                    'country_code' => Country::getIsoById($address->id_country),
                ],
            ]);

            if ($address->company) {
                $payload['receiver']['company_name'] = $address->company;
            }

            $payload['external_customer_id'] = 'PrestaShop';

            if ($sender = $this->sendingConfiguration->getSenderDetails()) {
                $sender['phone'] = preg_replace('/\s+/', '', $sender['phone']);
                $payload['sender'] = $sender;
            }
        }

        return $payload;
    }

    protected function buildPayloadFromRequestData(array $request, Currency $currency)
    {
        $payload = [
            'service' => $request['service'],
            'receiver' => [
                'email' => $request['email'],
                'phone' => $request['phone'],
            ],
            'custom_attributes' => [
                'sending_method' => $request['sending_method'],
            ],
            'parcels' => [],
        ];

        $lockerService = in_array($request['service'], Service::LOCKER_SERVICES);

        if ($request['sending_method'] === SendingMethod::POP && $lockerService) {
            $payload['custom_attributes']['dropoff_point'] = $request['dropoff_pop'];
        } elseif ($request['sending_method'] === SendingMethod::PARCEL_LOCKER) {
            $payload['custom_attributes']['dropoff_point'] = $request['dropoff_locker'];
        }

        $payload['parcels'][] = $this->parcelPayloadBuilder->buildPayloadFromRequestData($request);

        if ($request['service'] === Service::INPOST_LOCKER_STANDARD) {
            $payload['custom_attributes']['target_point'] = $request['target_point'];

            if ($request['weekend_delivery']) {
                $payload['end_of_week_collection'] = true;
            }
        }

        if ($request['reference']) {
            if ($lockerService) {
                $payload['reference'] = $request['reference'];
            } else {
                $payload['comments'] = $request['reference'];
            }
        }

        if ($request['cod']) {
            $payload['cod'] = [
                'amount' => (float) str_replace(',', '.', $request['cod_amount']),
                'currency' => $currency->iso_code,
            ];
        }

        if ($request['insurance']) {
            $payload['insurance'] = [
                'amount' => (float) str_replace(',', '.', $request['insurance_amount']),
                'currency' => $currency->iso_code,
            ];
        }

        return $payload;
    }

    protected function buildPayloadFromOrderData(Order $order, Currency $currency)
    {
        if ($customerChoice = $this->customerChoiceDataProvider->getDataByCartId($order->id_cart)) {
            $payload = [
                'service' => $customerChoice->service,
                'receiver' => [
                    'email' => $customerChoice->email,
                    'phone' => $customerChoice->phone,
                ],
                'parcels' => [],
            ];

            $reference = $this->referenceExtractor->getShipmentReference($order);

            if (in_array($customerChoice->service, Service::LOCKER_SERVICES)) {
                $payload['reference'] = $reference;
            } else {
                $payload['comments'] = $reference;
            }

            $inPostCarrier = $this->carrierDataProvider->getInPostCarrierByCarrierId($order->id_carrier);

            if ($sendingMethod = $this->carriersConfiguration->getDefaultSendingMethods($customerChoice->service)) {
                $payload['custom_attributes']['sending_method'] = $sendingMethod;

                if ($sendingMethod === SendingMethod::POP &&
                    ($point = $this->sendingConfiguration->getDefaultPOP()) ||
                    $sendingMethod === SendingMethod::PARCEL_LOCKER &&
                    $point = $this->sendingConfiguration->getDefaultLocker()
                ) {
                    $payload['custom_attributes']['dropoff_point'] = $point['name'];
                }
            }

            $payload['parcels'][] = $this->parcelPayloadBuilder->buildPayloadByOrder($order, $customerChoice->service);

            if (null !== $inPostCarrier && $inPostCarrier->cod) {
                $payload['cod'] = [
                    'amount' => $order->total_paid,
                    'currency' => $currency->iso_code,
                ];
            }

            if ($customerChoice->service === Service::INPOST_LOCKER_STANDARD) {
                $payload['custom_attributes']['target_point'] = $customerChoice->point;

                if (null !== $inPostCarrier && $inPostCarrier->weekend_delivery) {
                    $payload['end_of_week_collection'] = true;
                }
            } elseif (null !== $inPostCarrier && $inPostCarrier->cod) {
                $payload['insurance'] = [
                    'amount' => $order->total_paid,
                    'currency' => $currency->iso_code,
                ];
            }

            return $payload;
        }

        return null;
    }
}
