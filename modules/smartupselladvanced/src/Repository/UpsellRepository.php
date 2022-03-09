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
use DbQuery;

class UpsellRepository
{
    /**
     * @param $productId
     * @param $shopId
     * @param $langId
     * @param $sortA
     * @param $sortB
     * @param $maxProducts
     *
     * @return array|false|\mysqli_result|\PDOStatement|resource|null
     */
    public static function getUpsells($productId, $shopId, $langId, $sortA, $sortB, $maxProducts)
    {
        $query = new DbQuery();
        $query->select(
            'a.`id_product`,
            a.`id_related_product`,
            ROUND (p.`price`, 2) as `price`,
            pl.`name`,
            stock_available.`quantity`,
            p.`date_add`,
            sp.`reduction` as `reduction`,
            sp.`id_specific_price`
            '
        );
        $query->from('upsell_relation', 'a');
        $query->leftJoin(
            'product',
            'p',
            '(a.`id_related_product` = p.`id_product`)'
        );
        $query->leftJoin(
            'product_lang',
            'pl',
            '(pl.`id_product` = a.`id_related_product`
                    AND pl.`id_lang` = '.(int)$langId.'
                    AND pl.`id_shop` = '.(int)$shopId.')
        '
        );
        $query->leftJoin(
            'stock_available',
            'stock_available',
            '(stock_available.`id_product` = a.`id_related_product`
                    AND stock_available.`id_product_attribute` = 0
                    AND stock_available.`id_shop` = '.(int)$shopId.')
        '
        );
        $query->leftJoin(
            'specific_price',
            'sp',
            '(sp.`id_product` = a.`id_related_product`
                AND sp.`id_cart` = 0)
        '
        );
        $query->where('a.`id_product` = '.(int)$productId);
        $query->orderBy(pSQL($sortA).', '.pSQL($sortB).'limit '.(int)$maxProducts);

        return Db::getInstance()->executeS($query);
    }
}
