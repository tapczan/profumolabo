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

class SpecialOfferCartRelationRepository
{
    /**
     * @param $cartId
     *
     * @return array|false|\mysqli_result|\PDOStatement|resource|null
     */
    public static function getSpecialOffersInCartByCartId($cartId)
    {
        $query = new DbQuery();
        $query->select('*');
        $query->from('special_offer_cart', 'soc');
        $query->where('soc.`id_cart` = '.(int) $cartId);
        return Db::getInstance()->executeS($query);
    }

    /**
     * @param $specialOfferId
     *
     * @return array|false|\mysqli_result|\PDOStatement|resource|null
     */
    public static function getSpecialOffersInCartByOfferId($specialOfferId)
    {
        $query = new DbQuery();
        $query->select('*');
        $query->from('special_offer_cart', 'soc');
        $query->where('soc.`id_special_offer` = '.(int) $specialOfferId);
        return Db::getInstance()->executeS($query);
    }

    /**
     * @param $specialOfferId
     * @param $cartId
     * @param $specificPriceId
     * @param $customerId
     * @param null $date
     */
    public static function saveSpecialOfferRelation(
        $specialOfferId,
        $cartId,
        $customerId,
        $specificPriceId,
        $date = null
    ) {
        Db::getInstance()->insert(
            'special_offer_cart',
            [
                'id_special_offer' => (int) $specialOfferId,
                'id_cart' => (int) $cartId,
                'id_specific_price' => (int) $specificPriceId,
                'id_customer' => (int) $customerId,
                'date_expires' => $date
            ]
        );
    }

    /**
     * @param $specialOfferId
     *
     * @return array|false|\mysqli_result|\PDOStatement|resource|null
     */
    public static function getSpecialOfferRelation($specialOfferId, $customerId)
    {
        $query = new DbQuery();
        $query->select('*');
        $query->from('special_offer_cart', 'soc');
        $query->where('soc.`id_special_offer` = '.(int) $specialOfferId. ' AND soc.`id_customer` = '. (int)$customerId);
        return Db::getInstance()->executeS($query);
    }

    /**
     * @param $specialOfferId
     *
     * @return array|false|\mysqli_result|\PDOStatement|resource|null
     */
    public static function getSpecialOfferRelationById($specialOfferId)
    {
        $query = new DbQuery();
        $query->select('*');
        $query->from('special_offer_cart', 'soc');
        $query->where('soc.`id_special_offer` = '.(int) $specialOfferId);
        return Db::getInstance()->executeS($query);
    }

    /**
     * @param $cartId
     * @param int $specialOfferId
     */
    public static function removeSpecialOfferRelation($cartId, $specialOfferId = 0)
    {
        Db::getInstance()->delete(
            'special_offer_cart',
            'id_cart = '.(int) $cartId
            .' && id_cart = '.$cartId. $specialOfferId > 0 ? '&& id_special_offer'.(int) $specialOfferId:''
        );
    }

    /**
     * @param $specialOfferProductId
     *
     * @return array|false|\mysqli_result|\PDOStatement|resource|null
     */
    public static function getSpecialOfferId($specialOfferProductId)
    {
        $query = new DbQuery();
        $query->select('so.`id_special_offer`');
        $query->from('special_offer', 'so');
        $query->where('so.`id_main_product` = '.(int) $specialOfferProductId);
        return Db::getInstance()->executeS($query);
    }

    /**
     * @param $mainProductId
     *
     * @return array|false|\mysqli_result|\PDOStatement|resource|null
     */
    public static function getSpecialOfferProductId($mainProductId)
    {
        $query = new DbQuery();
        $query->select('so.`id_special_product`');
        $query->from('special_offer', 'so');
        $query->where('so.`id_special_offer` = '.(int) $mainProductId);
        return Db::getInstance()->executeS($query);
    }

    /**
     * @param $specialOfferId
     */
    public static function incrementTimesUsed($specialOfferId)
    {
        Db::getInstance()->query('
            UPDATE '._DB_PREFIX_.'special_offer 
            SET times_used = times_used + 1
            WHERE id_special_offer = '.(int)$specialOfferId.'
        ');
    }

    /**
     * @param $cartId
     * @param $dateExpires
     */
    public static function updateDateExpires($cartId, $dateExpires)
    {
        Db::getInstance()->query('
            UPDATE '._DB_PREFIX_.'special_offer_cart 
            SET date_expires = '."'".pSQL($dateExpires)."'".'
            WHERE id_cart = '.(int)$cartId.'
        ');
    }

    /**
     * @param $customerId
     * @param $specialProductId
     *
     * @return array|false|\mysqli_result|\PDOStatement|resource|null
     */
    public static function getRelationsForCustomer($customerId, $specialProductId)
    {
        $query = new DbQuery();
        $query->select('soc.`id_special_offer`, soc.`id_cart`, soc.`id_specific_price`, soc.`date_expires`');
        $query->from('special_offer_cart', 'soc');
        $query->leftJoin(
            'cart',
            'c',
            '(c.`id_cart` = soc.`id_cart`)'
        );
        $query->leftJoin(
            'special_offer',
            'so',
            '(so.`id_special_offer` = soc.`id_special_offer`)'
        );
        $query->where('c.`id_customer` = '.(int) $customerId);
        $query->where('so.`id_special_product` = '.(int) $specialProductId);
        return Db::getInstance()->executeS($query);
    }

    public static function isUsed($customerId, $specialOfferProductId)
    {
        $query = new DbQuery();
        $query->select('*');
        $query->from('special_offer_cart');
        $query->where('id_customer = ' . (int) $customerId);
        $query->where('id_special_offer = ' . (int) $specialOfferProductId);
        return \Db::getInstance()->executeS($query);
    }
}
