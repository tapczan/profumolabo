<?php
/**
 * NOTICE OF LICENSE
 *
 * @author    INVERTUS, UAB www.invertus.eu <support@invertus.eu>
 * @copyright Copyright (c) permanent, INVERTUS, UAB
 * @license   Addons PrestaShop license limitation
 * @see       /LICENSE
 *
 * International Registered Trademark & Property of INVERTUS, UAB
 */

namespace Invertus\SmartUpsellAdvanced\Helper;

use Invertus\SmartUpsellAdvanced\Repository\SettingsRepository;
use SpecificPrice;

class CartHelper
{
    const DEFAULT_SPECIAL_OFFER_LIMIT = 10;

    /**
     * @param $productId
     * @param $productAttributeId
     * @param $discountPrice
     * @param int $cartId
     * @param int $shopId
     * @param int $currencyId
     * @param int $countryId
     * @param int $groupId
     * @param int $customerId
     * @param string $price
     * @param int $quantityFrom
     * @param string $reductionType
     * @param int $reductionTax
     * @param string $from
     * @param string $to
     * @return int
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function addSpecificPrice(
        $productId,
        $productAttributeId,
        $discountPrice,
        $reductionType = 'amount',
        $cartId = 0,
        $shopId = 0,
        $currencyId = 0,
        $countryId = 0,
        $groupId = 0,
        $customerId = 0,
        $price = '-1',
        $quantityFrom = 0,
        $reductionTax = 0,
        $from = '0000-00-00 00:00:00',
        $to = '0000-00-00 00:00:00'
    ) {
        $specificPrice = new SpecificPrice();
        $specificPrice->id_product = $productId;
        $specificPrice->id_product_attribute = (int)$productAttributeId;
        $specificPrice->id_cart = $cartId;
        $specificPrice->id_shop = $shopId;
        $specificPrice->id_currency = $currencyId;
        $specificPrice->id_country = $countryId;
        $specificPrice->id_group = $groupId;
        $specificPrice->id_customer = $customerId;
        $specificPrice->price = $price;
        $specificPrice->from_quantity = $quantityFrom;
        $specificPrice->reduction = $discountPrice;
        $specificPrice->reduction_type = $reductionType;
        $specificPrice->reduction_tax = $reductionTax;
        $specificPrice->from = $from;
        $specificPrice->to = $to;
        $specificPrice->add();
        return $specificPrice->id;
    }

    public function updateSpecificPrice(
        $specificPriceId,
        $productId,
        $productAttributeId,
        $discountPrice,
        $reductionType = 'amount',
        $cartId = 0,
        $shopId = 0,
        $currencyId = 0,
        $countryId = 0,
        $groupId = 0,
        $customerId = 0,
        $price = '-1',
        $quantityFrom = 0,
        $reductionTax = 0,
        $from = '0000-00-00 00:00:00',
        $to = '0000-00-00 00:00:00'
    ) {
        $specificPrice = new SpecificPrice($specificPriceId);
        $specificPrice->id_product = $productId;
        $specificPrice->id_product_attribute = (int)$productAttributeId;
        $specificPrice->id_cart = $cartId;
        $specificPrice->id_shop = $shopId;
        $specificPrice->id_currency = $currencyId;
        $specificPrice->id_country = $countryId;
        $specificPrice->id_group = $groupId;
        $specificPrice->id_customer = $customerId;
        $specificPrice->price = $price;
        $specificPrice->from_quantity = $quantityFrom;
        $specificPrice->reduction = $discountPrice;
        $specificPrice->reduction_type = $reductionType;
        $specificPrice->reduction_tax = $reductionTax;
        $specificPrice->from = $from;
        $specificPrice->to = $to;
        $specificPrice->update();
    }


    /**
     * @return int
     */
    public static function getSpecialOffersLimit()
    {
        $return = self::DEFAULT_SPECIAL_OFFER_LIMIT;
        $cartSettings = SettingsRepository::getSettings();
        foreach ($cartSettings as $key => $val) {
            if ($key === 'SUA_SPEC_OFFER_LIMIT') {
                $return = $val;
            }
        }
        return $return;
    }

    public function getSpecificPriceIdByCustomerId($specificPriceId)
    {
        $specificPrice = new SpecificPrice($specificPriceId);
        if ($specificPrice->id !== null) {
            return true;
        }
        return false;
    }
}
