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

use Context;
use PrestaShop\Decimal\Number;
use PrestaShop\Decimal\Operation\Rounding;
use Product;
use Tools;

class PriceHelper
{
    const HUNDRED_PERCENT = 100;

    /**
     * @param $productPrice
     * @param $discount
     * @param $discountType
     * @param $currency
     *
     * @return array
     */
    public function convertDiscountNumToReadable($productPrice, $discount, $discountType, $currency)
    {
        $return = [];

        if ($discountType === 'percent' && $discount > 0) {
            $discountPercentToDisplay = Tools::displayNumber($discount) . '%';
            $discountAmmount = $productPrice - $this->convertPercentageToAmount($productPrice, $discount);
            $discountedPrieceToDisplay = Tools::displayPrice($discountAmmount, $currency);

            $return['discount_amount'] = $this->convertPercentageToAmount($productPrice, $discount);
            $return['discounted_price'] = $discountedPrieceToDisplay;
            $return['discount_percent'] = $discountPercentToDisplay;
            $return['product_discount'] = $this->getRealPrecentage(Tools::displayNumber($discount));
            $return['discount_type'] = 'percentage';
        } elseif ($discountType === 'amount' && $discount > 0) {
            $discountedPrieceToDisplay = Tools::displayPrice($productPrice - $discount, $currency);
            $discountPercent = $this->convertAmountToPercentage($productPrice, $discount);
            $discountPercentToDisplay = Tools::displayNumber($discountPercent) . '%';

            $return['discount_amount'] = $discount;
            $return['discounted_price'] = $discountedPrieceToDisplay;
            $return['discount_percent'] = $discountPercentToDisplay;
            $return['product_discount'] = $discount;
            $return['discount_type'] = 'amount';
        } else {
            $return = 0;
        }

        return $return;
    }

    /**
     * @param $productId
     * @param null $attributeId
     * @param null $productAttrbuteId
     *
     * @return float
     */
    public function getProductPrice($productId, $attributeId = null, $productAttrbuteId = null)
    {
        if ($productAttrbuteId === null) {
            $productAttrbuteId = null;
            if ($attributeId != null) {
                $productAttrbuteId = Product::getIdProductAttributeByIdAttributes($productId, $attributeId);
            }
        }

        $product = new Product($productId, false, Context::getContext()->language->id);

        $productPrice = $product->getPrice(
            true,
            $productAttrbuteId,
            6,
            null,
            false,
            false
        );

        return $productPrice;
    }

    /**
     * @param $productId
     * @param null $attributeId
     * @param null $productAttrbuteId
     *
     * @return float
     */
    public function getProductDiscountAmount($productId, $attributeId = null, $productAttrbuteId = null)
    {
        if ($productAttrbuteId === null) {
            $productAttrbuteId = null;
            if ($attributeId != null) {
                $productAttrbuteId = Product::getIdProductAttributeByIdAttributes($productId, $attributeId);
            }
        }
        $product = new Product($productId, false, Context::getContext()->language->id);

        $productPrice = $product->getPrice(
            false,
            $productAttrbuteId,
            6,
            null,
            true,
            false
        );

        return $productPrice;
    }

    /**
     * @param $totalPrice
     * @param $percentage
     *
     * @return float|int
     */
    private function convertPercentageToAmount($totalPrice, $percentage)
    {
        return $totalPrice * ($percentage / self::HUNDRED_PERCENT);
    }

    /**
     * @param $totalPrice
     * @param $priceReduction
     *
     * @return string
     */
    private function convertAmountToPercentage($totalPrice, $priceReduction)
    {
        $ammount = ($priceReduction * self::HUNDRED_PERCENT)/$totalPrice;
        $ammountNumber = new Number((string)$ammount);
        return $ammountNumber->round(0, Rounding::ROUND_HALF_UP);
    }

    private function getRealPrecentage($percentage)
    {
        return $percentage / self::HUNDRED_PERCENT;
    }
}
