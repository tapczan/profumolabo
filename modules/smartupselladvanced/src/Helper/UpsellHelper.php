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
use Invertus\SmartUpsellAdvanced\ClientBusinessLogicProvider\LinkProvider;
use Invertus\SmartUpsellAdvanced\Repository\SettingsRepository;
use Invertus\SmartUpsellAdvanced\Repository\UpsellRepository;
use Product;
use Tools;

//@todo rename to presenter
class UpsellHelper
{
    const DEFAULT_MAX_UPSELL_PRODUCTS = 2;

    /**
     * @var PriceHelper
     */
    private $priceHelper;
    /**
     * @var LinkProvider
     */
    private $linkProvider;
    /**
     * @var SpecialOfferHelper
     */
    private $specialOfferHelper;

    /**
     * UpsellHelper constructor.
     * @param PriceHelper $priceHelper
     * @param LinkProvider $linkProvider
     * @param SpecialOfferHelper $specialOfferHelper
     */
    public function __construct(
        PriceHelper $priceHelper,
        LinkProvider $linkProvider,
        SpecialOfferHelper $specialOfferHelper
    ) {
        $this->priceHelper = $priceHelper;
        $this->linkProvider = $linkProvider;
        $this->specialOfferHelper = $specialOfferHelper;
    }

    //@todo maybe this method should be removed

    /**
     * @param $productId
     * @param $shopId
     * @param $languageId
     *
     * @return array|false|\mysqli_result|\PDOStatement|resource|null
     */
    public function getUpsells($productId, $shopId, $languageId)
    {

        $firstSortingKey = $this->getFirstSortKey();
        $secondSortingKey = $this->getSecondSortKey();
        $maxUpsellsToShow = $this->getMaxUpsellProducts();

        $firstSortingKeyQuery = $this->getQueryCodeBySetting($firstSortingKey);
        $secondSortingKeyQuery = $this->getQueryCodeBySetting($secondSortingKey);

        return UpsellRepository::getUpsells(
            $productId,
            $shopId,
            $languageId,
            $firstSortingKeyQuery,
            $secondSortingKeyQuery,
            $maxUpsellsToShow
        );
    }

    // TODO change name

    /**
     * @param $productId
     * @param $shopId
     * @param $languageId
     * @param $currency
     * @param $ajaxHook
     *
     * @return array
     */
    public function getSmartyFriendlyUpsells($productId, $shopId, $languageId, $currency, $ajaxHook)
    {
        $priceHelper = $this->priceHelper;
        $smartyFriendlyList = [];
        $LinkProvider = $this->linkProvider;
        $specialOfferHelper = $this->specialOfferHelper;
        $upsells = $this->getUpsells($productId, $shopId, $languageId);

        foreach ($upsells as $upsell) {
            $tempList = [];
            $discount = $priceHelper->convertDiscountNumToReadable(
                $upsell['price'],
                $priceHelper->getProductDiscountAmount($upsell['id_related_product']),
                'amount',
                $currency
            );
            $image = $LinkProvider->getImageLinkById($upsell['id_related_product'], 'home');
            $imageLarge = $LinkProvider->getImageLinkById($upsell['id_related_product'], 'large');
            $tempList['id_product'] = $upsell['id_related_product'];
            $tempList['image'] = $image;
            $tempList['image_large'] = $imageLarge;
            $tempList['price'] =Tools::displayPrice($upsell['price'], $currency);
            $tempList['name'] = $upsell['name'];
            $tempList['module_link'] = $ajaxHook;
            $tempList['discount'] = $discount;
            $tempList['group_attributes'] = $specialOfferHelper->getAttributeGroupArray($upsell['id_related_product']);
            $tempList['product_link'] = $LinkProvider->getProductLinkById($upsell['id_related_product']);
            $tempList['cart_link'] = Context::getContext()->link->getPageLink('cart');
            $tempList['combinations'] = $this->getProductCombinations($upsell['id_related_product'], $languageId);

            $smartyFriendlyList[] = $tempList;
        }

        return $smartyFriendlyList;
    }

    //@TODO move to repository

    /**
     * @param $setting
     *
     * @return int|string
     */
    private function getQueryCodeBySetting($setting)
    {
        $return = 0;

        if ($setting === 'highest_price') {
            $return = ' price DESC ';
        } elseif ($setting === 'lowest_price') {
            $return = ' price ASC ';
        } elseif ($setting === 'highest_discount') {
            $return = ' reduction DESC ';
        } elseif ($setting === 'lowest_discount') {
            $return = ' reduction ASC ';
        } elseif ($setting === 'name_a_z') {
            $return = ' name ASC ';
        } elseif ($setting === 'name_z_a') {
            $return = ' name DESC ';
        } elseif ($setting === 'newest') {
            $return = ' date_add DESC ';
        } elseif ($setting === 'oldest') {
            $return = ' date_add ASC ';
        } elseif ($setting === 'highest_quantity') {
            $return = ' quantity DESC ';
        } elseif ($setting === 'lowest_quantity') {
            $return = ' quantity ASC ';
        }

        return $return;
    }

    /**
     * @return string
     */
    private function getFirstSortKey()
    {
        $return = 'highest_price';
        $cartSettings = SettingsRepository::getSettings();
        foreach ($cartSettings as $key => $val) {
            if ($key === 'SUA_SORT_BY_FIRST_KEY') {
                return $val;
            }
        }
        return $return;
    }

    /**
     * @return string
     */
    private function getSecondSortKey()
    {
        $return = 'highest_price';
        $cartSettings = SettingsRepository::getSettings();
        foreach ($cartSettings as $key => $val) {
            if ($key === 'SUA_SORT_BY_SECOND_KEY') {
                return $val;
            }
        }
        return $return;
    }

    /**
     * @return int
     */
    public function getMaxUpsellProducts()
    {
        $return = self::DEFAULT_MAX_UPSELL_PRODUCTS;
        $cartSettings = SettingsRepository::getSettings();
        foreach ($cartSettings as $key => $val) {
            if ($key === 'SUA_MAX_UPSELL_PRODUCTS') {
                return $val;
            }
        }
        return $return;
    }

    /**
     * @return bool
     */
    public function isPopupShown()
    {
        $return = true;
        $cartSettings = SettingsRepository::getSettings();
        foreach ($cartSettings as $key => $val) {
            if ($key === 'SUA_OUT_OF_STOCK_POPUP') {
                return $val;
            }
        }
        return $return;
    }

    /**
     * @param $productId
     * @param $languageId
     *
     * @return array
     */
    public function getProductCombinations($productId, $languageId)
    {
        $product = new Product($productId);
        $combinations = $product->getAttributeCombinations($languageId);

        $combinationsArray = [];

        if (is_array($combinations)) {
            foreach ($combinations as $combination) {
                $combinationsArray[$combination['id_product_attribute']]['id_product_attribute'] =
                    $combination['id_product_attribute'];
                $combinationsArray[$combination['id_product_attribute']]['attributes'][] =
                    [$combination['group_name'], $combination['attribute_name'], $combination['id_attribute']];
            }
        }

        if (isset($combinationsArray)) {
            foreach ($combinationsArray as $productAttributeId => $product_attribute) {
                $list = '';

                asort($product_attribute['attributes']);

                foreach ($product_attribute['attributes'] as $attribute) {
                    $list .= $attribute[0].' - '.$attribute[1].', ';
                }

                $list = rtrim($list, ', ');
                $combinationsArray[$productAttributeId]['name'] = $list;
            }
        }

        return $combinationsArray;
    }
}
