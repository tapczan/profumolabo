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

use Context;
use Db;
use DbQuery;

class ProductRepository
{
    /**
     * Get all products by query
     *
     * @param $query
     * @param int $limit
     *
     * @return array|false|\mysqli_result|\PDOStatement|resource|null
     */
    public static function getProductsByQuery($query, $limit = 3)
    {
        $products = Db::getInstance()->executeS(
            'SELECT ps.`id_product`, pl.`name`, pl.`link_rewrite`, p.`reference`, p.`ean13`, i.`id_image`
            FROM `'._DB_PREFIX_.'product_shop` ps
            JOIN `'._DB_PREFIX_.'product` p ON (p.`id_product` = ps.`id_product`)
            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
                ON (pl.`id_product` = ps.`id_product`
                    AND pl.`id_lang` = "'.(int) Context::getContext()->language->id.'"
                    AND pl.`id_shop` = "'.(int) Context::getContext()->shop->id.'")
            LEFT JOIN `'._DB_PREFIX_.'image_shop` i
                ON (i.`id_product` = ps.`id_product`
                    AND i.cover = "1"
                    AND i.id_shop = "'.(int)Context::getContext()->shop->id.'")
            WHERE ps.`id_shop` = "'.(int)Context::getContext()->shop->id.'"
            HAVING `reference` LIKE "%'.pSQL($query).'%"
                OR `ean13` LIKE "%'.pSQL($query).'%"
                OR `name` LIKE "%'.pSQL($query).'%"
            LIMIT '.(int)$limit
        );

        if (!is_array($products) || empty($products)) {
            return [];
        }

        return $products;
    }

    /**
     * @param $idCurrentProduct
     * @param bool $next
     *
     * @return array|false|\mysqli_result|\PDOStatement|resource|null
     */
    public static function getNextOrPreviousProductId($idCurrentProduct, $next = true)
    {
        $query = new DbQuery();
        $query->select('p.`id_product` as `id_product`');
        $query->from('product', 'p');
        if ($next) {
            $query->where('p.`id_product` > '.(int) $idCurrentProduct);
            $query->orderBy('id_product limit 1');
        } else {
            $query->where('p.`id_product` < '.(int) $idCurrentProduct);
            $query->orderBy('id_product DESC limit 1');
        }
        return Db::getInstance()->executeS($query);
    }

    /**
     * @param $currentProductId
     * @param $orderBy
     * @param $orderWay
     * @param $pagination
     * @param $start
     *
     * @return array|false|\mysqli_result|\PDOStatement|resource|null
     */
    public static function getAvailableProductsListContent($currentProductId, $orderBy, $orderWay, $pagination, $start)
    {
        $context = Context::getContext();
        $idShop = $context->shop->id;
        $idLang = $context->language->id;

        $query = new \DbQuery();

        // SQL statements
        $query->select('stock_available.`quantity` as `product_quantity`, pl.`name` as `product_name` ');
        $query->select('cl.`name` as `category_name`, ims.`id_image`, a.`id_product`, ROUND (a.`price`, 2) as `price`');
        $query->select('stock_available.`quantity` as `product_quantity`');

        $query->from('product', 'a');

        $query->leftJoin(
            'stock_available',
            'stock_available',
            '(stock_available.`id_product` = a.`id_product`
                    AND stock_available.`id_product_attribute` = 0
                    AND stock_available.`id_shop` = '.(int)$idShop.')
        '
        );
        $query->leftJoin(
            'product_lang',
            'pl',
            '(pl.`id_product` = a.`id_product`
                    AND pl.`id_lang` = '.(int)$idLang.'
                    AND pl.`id_shop` = '.(int)$idShop.')
        '
        );
        $query->leftJoin(
            'category_lang',
            'cl',
            '(cl.`id_category` = a.`id_category_default`
                    AND cl.`id_lang` = '.(int)$idLang.'
                    AND cl.`id_shop` = '.(int)$idShop.')
        '
        );
        $query->leftJoin(
            'image',
            'i',
            '(i.`id_product` = a.`id_product`)
            INNER JOIN `'._DB_PREFIX_.'image_shop` ims
                ON (ims.`id_image` = i.`id_image`
                    AND ims.`id_shop` = '.(int)$idShop.'
                    AND ims.`cover` = 1)
        '
        );

        $query->where('a.`id_product` != '.(int)$currentProductId);

        $query->where(
            'a.`id_product` 
            NOT IN(SELECT id_related_product FROM '._DB_PREFIX_.'upsell_relation 
                  WHERE id_product = '.(int)$currentProductId.' 
                  GROUP BY id_related_product)'
        );

        $query->orderBy(pSQL($orderBy).' '.pSQL($orderWay));
        if (!is_null($pagination) && !is_null($start)) {
            $query->limit((int)$start, (int)$pagination);
        }

        $products = Db::getInstance()->executeS($query);

        if (!is_array($products) || empty($products)) {
            return [];
        }

        return $products;
    }

    /**
     * @param $currentProductId
     * @param $orderBy
     * @param $orderWay
     * @param $pagination
     * @param $start
     *
     * @return array|false|\mysqli_result|\PDOStatement|resource|null
     */
    public static function getUpsellProductsListContent($currentProductId, $orderBy, $orderWay, $pagination, $start)
    {
        $context = Context::getContext();
        $idShop = $context->shop->id;
        $idLang = $context->language->id;

        $query = new \DbQuery();

        // SQL statements
        $query->select('stock_available.`quantity` as `product_quantity`, pl.`name` as `product_name` ');
        $query->select('ims.`id_image`, a.`id_product`, ROUND (a.`price`, 2) as `price`');
        $query->select('stock_available.`quantity` as `product_quantity`, a.`active` as `product_active`');

        $query->from('product', 'a');

        $query->leftJoin(
            'stock_available',
            'stock_available',
            '(stock_available.`id_product` = a.`id_product`
                    AND stock_available.`id_product_attribute` = 0
                    AND stock_available.`id_shop` = '.(int)$idShop.')'
        );

        $query->leftJoin(
            'product_lang',
            'pl',
            '(pl.`id_product` = a.`id_product`
                    AND pl.`id_lang` = '.(int)$idLang.'
                    AND pl.`id_shop` = '.(int)$idShop.')'
        );

        $query->leftJoin(
            'special_offer',
            'so',
            '(so.`id_main_product` = a.`id_product`)'
        );

        $query->leftJoin(
            'upsell_relation',
            'ur',
            '(ur.`id_product` = '.(int)$currentProductId.')'
        );

        $query->leftJoin(
            'image',
            'i',
            '(i.`id_product` = a.`id_product`)
            INNER JOIN `'._DB_PREFIX_.'image_shop` ims
                ON (ims.`id_image` = i.`id_image`
                    AND ims.`id_shop` = '.(int)$idShop.'
                    AND ims.`cover` = 1)'
        );

        $query->where('a.`id_product` != '.(int)$currentProductId);
        $query->where('a.`id_product` = ur.`id_related_product`');

        $query->orderBy(pSQL($orderBy).' '.pSQL($orderWay));
        if (!is_null($pagination) && !is_null($start)) {
            $query->limit((int)$start, (int)$pagination);
        }

        $products = Db::getInstance()->executeS($query);

        if (!is_array($products) || empty($products)) {
            return [];
        }
        return $products;
    }

    /**
     * @param int $id_selected_product
     * @param int $id_upsell_product
     *
     * @return bool
     */
    public static function insertUpsellRelation($id_selected_product, $id_upsell_product)
    {
        // Sends current product and related product to the database
        if ($id_selected_product != false && $id_upsell_product!= false) {
            return Db::getInstance()->insert(
                'upsell_relation',
                ['id_product' => (int) $id_selected_product, 'id_related_product' => (int) $id_upsell_product]
            );
        }

        return false;
    }

    /**
     * @param int $id_selected_product
     * @param int $id_upsell_product
     *
     * @return bool
     */
    public static function deleteUpsellRelation($id_selected_product, $id_upsell_product)
    {
        // Sends current product and related product to the database
        if ($id_selected_product != false && $id_upsell_product!= false) {
            return Db::getInstance()->delete(
                'upsell_relation',
                'id_product = '.(int) $id_selected_product.'&& id_related_product = '. (int) $id_upsell_product
            );
        }

        return false;
    }

    /**
     * @param $currentProductID
     *
     * @return array|false|\mysqli_result|\PDOStatement|resource|null
     */
    public static function getUpsellProductsForSelectedProduct($currentProductID)
    {
        $query = new DbQuery();
        $query->select('ur.`id_related_product`');
        $query->from('upsell_relation', 'ur');
        $query->where('ur.`id_product` = ' . (int) $currentProductID);
        return Db::getInstance()->executeS($query);
    }

    /**
     * @param $specialOfferID
     *
     * @return array|false|\mysqli_result|\PDOStatement|resource|null
     */
    public static function isSpecialOfferInDb($specialOfferID)
    {
        $query = new DbQuery();
        $query->select('so.`id_main_product`');
        $query->from('special_offer', 'so');
        $query->where('so.`id_main_product` = '.(int) $specialOfferID);
        return Db::getInstance()->executeS($query);
    }

    /**
     * @param $productId
     *
     * @return array|false|\mysqli_result|\PDOStatement|resource|null
     */
    public static function getSpecialOffer($productId)
    {
        $query = new DbQuery();
        $query->select('*');
        $query->from('special_offer', 'so');
        $query->where('so.`id_main_product` = '.(int) $productId);
        return Db::getInstance()->executeS($query);
    }

    public static function getSpecialOfferBySpecialProduct($productId)
    {
        $query = new DbQuery();
        $query->select('*');
        $query->from('special_offer', 'so');
        $query->where('so.`id_special_product` = '.(int) $productId);
        return Db::getInstance()->executeS($query);
    }

    /**
     * @param $productId
     *
     * @return array|false|\mysqli_result|\PDOStatement|resource|null
     */
    public static function getSpecialOfferGroups($productId)
    {
        $query = new DbQuery();
        $query->select('id_group');
        $query->from('special_offer', 'so');
        $query->leftJoin(
            'special_offer_group',
            'sog',
            '(so.`id_special_offer` = sog.`id_special_offer`)'
        );
        $query->where('so.`id_main_product` = '.(int) $productId);
        return Db::getInstance()->executeS($query);
    }

    /**
     * @param $mainProductId
     *
     * @return array|false|\mysqli_result|\PDOStatement|resource|null
     */
    public static function getSpecialOfferDiscount($mainProductId)
    {
        $query = new DbQuery();
        $query->select('so.`discount`, so.`discount_type`');
        $query->from('special_offer', 'so');
        $query->where('so.`id_main_product` = '.(int) $mainProductId);
        return Db::getInstance()->executeS($query);
    }
}
