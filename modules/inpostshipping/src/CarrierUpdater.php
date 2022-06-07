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

namespace InPost\Shipping;

use Carrier;
use Context;
use Country;
use Db;
use Exception;
use Group;
use InPost\Shipping\DataProvider\LanguageDataProvider;
use InPost\Shipping\ShipX\Resource\Service;
use InPost\Shipping\Traits\ErrorsTrait;
use InPostShipping;
use Language;
use Module;
use PrestaShopCollection;
use RangeWeight;
use TaxRulesGroup;
use Validate;

class CarrierUpdater
{
    use ErrorsTrait;

    const TRANSLATION_SOURCE = 'CarrierUpdater';

    const TRACKING_URL = 'https://inpost.pl/sledzenie-przesylek?number=@';

    protected $module;
    protected $languageDataProvider;

    protected $groups = [];

    protected $id_country;
    protected $id_zone;
    protected $id_tax_rule_group;

    /**
     * @var Carrier
     */
    protected $carrier;
    protected $service;
    protected $updateSettings = true;

    public function __construct(
        InPostShipping $module,
        LanguageDataProvider $languageDataProvider
    ) {
        $this->module = $module;
        $this->languageDataProvider = $languageDataProvider;

        $this->init();
    }

    protected function init()
    {
        if ($this->id_country = Country::getByIso('PL', true)) {
            $this->id_zone = Country::getIdZone($this->id_country);
        }

        foreach (TaxRulesGroup::getAssociatedTaxRatesByIdCountry($this->id_country) as $id_tax_rule_group => $rate) {
            if ((float) $rate === 23.) {
                $this->id_tax_rule_group = $id_tax_rule_group;
                break;
            }
        }

        foreach (Group::getGroups(Context::getContext()->language->id) as $group) {
            $this->groups[] = $group['id_group'];
        }
    }

    public function update(
        Carrier $carrier,
        $service,
        $cashOnDelivery,
        $weekendDelivery = false,
        $updateSettings = true
    ) {
        $this->resetErrors();

        if (Validate::isLoadedObject($carrier)) {
            $this->carrier = $carrier->duplicateObject();
            $this->carrier->copyCarrierData($carrier->id);
        } else {
            $this->carrier = $carrier;
        }

        $weekendDelivery = $weekendDelivery && $service === Service::INPOST_LOCKER_STANDARD;

        $this->service = $service;
        $this->updateSettings = $updateSettings;

        $this->setCarrierFields($weekendDelivery);

        try {
            $this->carrier->save();
            $this->carrier->id_reference = $this->carrier->id_reference ?: $this->carrier->id;

            if ($this->updateSettings) {
                $this->carrier->setGroups($this->groups);
                if ($this->id_tax_rule_group) {
                    $this->carrier->setTaxRulesGroup($this->id_tax_rule_group);
                }

                $this->addRange()
                    ->addZone();

                if ($cashOnDelivery) {
                    $this->limitPaymentModules();
                }
            }

            $this->copyImage($weekendDelivery);

            if ($carrier->id !== $this->carrier->id) {
                $carrier->deleted = true;
                $carrier->update();
            }

            return $this->carrier;
        } catch (Exception $exception) {
            $this->addError($exception->getMessage());
            $this->carrier->delete();

            return false;
        }
    }

    protected function setCarrierFields($weekendDelivery)
    {
        $this->carrier->is_module = true;
        $this->carrier->external_module_name = $this->module->name;
        $this->carrier->url = self::TRACKING_URL;
        $this->carrier->shipping_external = $weekendDelivery;
        $this->carrier->need_range = true;

        if ($this->updateSettings) {
            $this->carrier->active = true;
            $this->carrier->is_free = false;
            $this->carrier->shipping_handling = true;
            $this->carrier->shipping_method = Carrier::SHIPPING_METHOD_WEIGHT;
            $this->carrier->range_behavior = true;

            $this->setDelay()
//                ->setMaxDimensions()
                ->setMaxWeight();
        }

        return $this;
    }

    protected function setDelay()
    {
        foreach ($this->languageDataProvider->getLanguages() as $id_lang => $language) {
            $this->carrier->delay[$id_lang] = $this->module->l('48 - 72h', self::TRANSLATION_SOURCE, $language['locale']);
        }

        return $this;
    }

    protected function setMaxDimensions()
    {
        switch ($this->service) {
            case Service::INPOST_LOCKER_STANDARD:
                $this->carrier->max_height = 41;
                $this->carrier->max_width = 38;
                $this->carrier->max_depth = 64;
                break;
            case Service::INPOST_COURIER_C2C:
                $this->carrier->max_height = 50;
                $this->carrier->max_width = 50;
                $this->carrier->max_depth = 80;
                break;
            case Service::INPOST_COURIER_PALETTE:
                $this->carrier->max_height = 120;
                $this->carrier->max_width = 80;
                $this->carrier->max_depth = 180;
                break;
            default:
                $this->carrier->max_height = 350;
                $this->carrier->max_width = 240;
                $this->carrier->max_depth = 240;
                break;
        }

        return $this;
    }

    protected function setMaxWeight()
    {
        switch ($this->service) {
            case Service::INPOST_LOCKER_STANDARD:
            case Service::INPOST_COURIER_C2C:
                $this->carrier->max_weight = 25;
                break;
            case Service::INPOST_COURIER_STANDARD:
            case Service::INPOST_COURIER_EXPRESS_1700:
                $this->carrier->max_weight = 50;
                break;
            case Service::INPOST_COURIER_PALETTE:
                $this->carrier->max_weight = 800;
                break;
            default:
                $this->carrier->max_weight = 30;
                break;
        }

        return $this;
    }

    protected function addZone()
    {
        foreach ($this->carrier->getZones() as $zone) {
            $this->carrier->deleteZone($zone['id_zone']);
        }

        $this->carrier->addZone($this->id_zone);

        return $this;
    }

    protected function addRange()
    {
        $ranges = (new PrestaShopCollection(RangeWeight::class))
            ->where('id_carrier', '=', $this->carrier->id);

        /** @var RangeWeight $range */
        foreach ($ranges as $range) {
            $range->delete();
        }

        $range = new RangeWeight();
        $range->id_carrier = $this->carrier->id;
        $range->delimiter1 = $this->service === Service::INPOST_COURIER_PALETTE ? 50 : 0;
        $range->delimiter2 = $this->carrier->max_weight;
        $range->add();

        return $this;
    }

    protected function limitPaymentModules()
    {
        if (($module = Module::getInstanceByName('ps_cashondelivery')) ||
            ($module = Module::getInstanceByName('cashondelivery'))
        ) {
            $paymentModules = [
                [
                    'id_reference' => (int) $this->carrier->id_reference,
                    'id_module' => (int) $module->id,
                ],
            ];

            $db = Db::getInstance();

            $db->delete('module_carrier', 'id_reference = ' . (int) $this->carrier->id_reference);
            $db->insert('module_carrier', $paymentModules);
        }

        return $this;
    }

    protected function copyImage($weekendDelivery)
    {
        switch ($this->service) {
            case Service::INPOST_LOCKER_STANDARD:
                $logo = $weekendDelivery
                    ? 'logo_weekend.png'
                    : 'logo_locker.png';
                break;
            default:
                $logo = 'logo.png';
                break;
        }

        copy(
            $this->module->getLocalPath() . 'views/img/' . $logo,
            _PS_SHIP_IMG_DIR_ . '/' . $this->carrier->id . '.jpg'
        );

        return $this;
    }
}
