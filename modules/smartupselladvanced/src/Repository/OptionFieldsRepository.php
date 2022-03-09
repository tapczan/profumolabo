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

namespace Invertus\SmartUpsellAdvanced\Repository;

use Db;

class OptionFieldsRepository
{

    /**
     * @param int $specOfferLimit
     * @param string $sameOffeToSameClient
     * @param string $sortByFirstKey
     * @param string $sortBySecondKey
     * @param int $maxUpsellProducts
     * @param bool $outOfStockPopup
     *
     * @return bool
     */
    public static function initialiseOptionFields(
        $specOfferLimit = 1,
        $sameOffeToSameClient = 'show',
        $sortByFirstKey = 'highest_price',
        $sortBySecondKey = 'name_a_z',
        $maxUpsellProducts = 2,
        $outOfStockPopup = true
    ) {
        return Db::getInstance()->insert(
            'configuration',
            [
                ['name' =>'SUA_SPEC_OFFER_LIMIT', 'value' =>  (int)$specOfferLimit],
                ['name' =>'SUA_SAME_OFFER_TO_SAME_CLIENT', 'value' => pSQL($sameOffeToSameClient)],
                ['name' =>'SUA_SORT_BY_FIRST_KEY', 'value' =>  pSQL($sortByFirstKey)],
                ['name' => 'SUA_SORT_BY_SECOND_KEY', 'value' =>  pSQL($sortBySecondKey)],
                ['name' => 'SUA_MAX_UPSELL_PRODUCTS', 'value' => (int)$maxUpsellProducts],
                ['name' => 'SUA_OUT_OF_STOCK_POPUP', 'value' => (int)$outOfStockPopup]
            ],
            true
        );
    }

    /**
     * @return bool
     */
    public static function deleteOptionFields()
    {
        return Db::getInstance()->delete(
            'configuration',
            '
            `name` = "SUA_SPEC_OFFER_LIMIT" 
            OR `name` = "SUA_SAME_OFFER_TO_SAME_CLIENT" 
            OR `name` = "SUA_SORT_BY_FIRST_KEY" 
            OR `name` = "SUA_SORT_BY_SECOND_KEY" 
            OR `name` = "SUA_MAX_UPSELL_PRODUCTS" 
            OR `name` = "SUA_OUT_OF_STOCK_POPUP"
            '
        );
    }
}
