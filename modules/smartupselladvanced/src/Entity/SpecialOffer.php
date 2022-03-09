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

namespace Invertus\SmartUpsellAdvanced\Entity;

use ObjectModel;
use Db;
use Cache;
use DbQuery;

class SpecialOffer extends ObjectModel
{
    /**
     * @var int
     */
    public $id_special_offer;

    /**
     * @var string
     */
    public $name;

    /**
     * @var bool
     */
    public $is_active;

    /**
     * @var int
     */
    public $id_main_product;

    /**
     * @var int
     */
    public $is_type;

    /**
     * @var int
     */
    public $is_limited_time;

    /**
     * @var int
     */
    public $time_limit;

    /**
     * @var int
     */
    public $id_special_product;

    /**
     * @var int
     */
    public $is_valid_in_specific_interval;

    /**
     * @var string
     */
    public $valid_from;

    /**
     * @var string
     */
    public $valid_to;

    /**
     * @var float
     */
    public $discount;

    /**
     * @var string
     */
    public $discount_type;

    /**
     * @var int
     */
    public $times_used;

    public $id_shop;

    public static $definition = [
        'table' => 'special_offer',
        'primary' => 'id_special_offer',
        'multilang' => true,
        'fields' => [
            'is_active' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
            'id_main_product' => ['type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'],
            'is_type' => ['type' => self::TYPE_INT, 'validate' => 'isInt'],
            'is_limited_time' => ['type' => self::TYPE_INT, 'validate' => 'isInt'],
            'time_limit' => ['type' => self::TYPE_STRING],
            'id_special_product' => ['type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'],
            'is_valid_in_specific_interval' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'valid_from' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
            'valid_to' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
            'discount' => ['type' => self::TYPE_FLOAT, 'required' => true, 'validate' => 'isUnsignedFloat'],
            'discount_type' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'times_used' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'id_shop' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],

            // Language fields
            'name' => [
                'type' => self::TYPE_STRING,
                'lang' => true,
                'required' => true
            ],
        ],
    ];

    /**
     * Add Category groups.
     *
     * @param $groups
     * @param bool $specialProductId
     * @throws \PrestaShopDatabaseException
     */
    public function addGroups($groups, $specialProductId = false)
    {
        if ($specialProductId != false) {
            Db::getInstance()->delete(
                'special_offer_group',
                '`id_special_offer` = '. $specialProductId
            );
        }

        foreach ($groups as $group) {
            if ($group !== false) {
                Db::getInstance()->insert(
                    'special_offer_group',
                    ['id_special_offer' => (int) $this->id, 'id_group' => (int) $group]
                );
            }
        }
    }

    public function addTime($time)
    {
        $sql = 'UPDATE '._DB_PREFIX_.'special_offer SET time_limit = "'.pSQL($time).'" WHERE id_special_offer = '.(int) $this->id;
        Db::getInstance()->execute($sql);
    }

    public function addIdShop($idShop)
    {
        $sql = 'UPDATE '._DB_PREFIX_.'special_offer SET id_shop = '.(int) $idShop.' WHERE id_special_offer = '.(int) $this->id;
        Db::getInstance()->execute($sql);
    }

    /**
     * Get Category groups.
     *
     * @return array|mixed
     * @throws \PrestaShopDatabaseException
     */
    public function getGroups()
    {
        $cacheId = 'SpecialOffer::getGroups_' . (int) $this->id_special_offer;
        if (!Cache::isStored($cacheId)) {
            $sql = new DbQuery();
            $sql->select('sog.`id_group`');
            $sql->from('special_offer_group', 'sog');
            $sql->where('sog.`id_special_offer` = ' . (int) $this->id_special_offer);
            $result = Db::getInstance()->executeS($sql);
            $groups = [];
            foreach ($result as $group) {
                $groups[] = $group['id_group'];
            }
            Cache::store($cacheId, $groups);

            return $groups;
        }
        return Cache::retrieve($cacheId);
    }
}
