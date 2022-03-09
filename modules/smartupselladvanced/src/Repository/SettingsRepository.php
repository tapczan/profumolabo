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

use Configuration;

class SettingsRepository
{
    /**
     * @return array
     */
    public static function getSettings()
    {
        $settings = Configuration::getMultiple([
        'SUA_SPEC_OFFER_LIMIT',
        'SUA_SAME_OFFER_TO_SAME_CLIENT',
        'SUA_SORT_BY_FIRST_KEY',
        'SUA_SORT_BY_SECOND_KEY',
        'SUA_MAX_UPSELL_PRODUCTS',
        'SUA_OUT_OF_STOCK_POPUP',
        ]);
        return $settings;
    }
}
