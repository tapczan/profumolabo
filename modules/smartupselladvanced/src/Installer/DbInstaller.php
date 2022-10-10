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

namespace Invertus\SmartUpsellAdvanced\Installer;

use Db;
use Invertus\SmartUpsellAdvanced\Repository\OptionFieldsRepository;
use SmartUpsellAdvanced;

class DbInstaller
{

    /**
     * @return bool
     */
    public static function install()
    {
        OptionFieldsRepository::initialiseOptionFields();

        $return = true;

        $return &= Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'special_offer` (
                id_special_offer INT NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(32) NOT NULL,
                is_active TINYINT(1) NOT NULL,
                id_main_product INT UNSIGNED NOT NULL,
                `is_type` TINYINT(1) UNSIGNED NOT NULL,
                is_limited_time INT(11) UNSIGNED NOT NULL,
                time_limit VARCHAR(32) NOT NULL,
                id_special_product INT UNSIGNED NOT NULL,
                is_valid_in_specific_interval TINYINT(1) NOT NULL,
                valid_from DATETIME,
                valid_to DATETIME,
                discount DECIMAL(20, 6) NOT NULL,
                discount_type VARCHAR(32) NOT NULL,
                times_used INT(11),
                id_shop INT(11),
                PRIMARY KEY(id_special_offer)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8'
        );

        $return &= Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'special_offer_group` (
                id_special_offer INT(11) NOT NULL,
                id_group INT(11) NOT NULL,
                PRIMARY KEY(id_special_offer, id_group)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8'
        );

        $return &= Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'special_offer_lang` (
                id_special_offer INT(11) NOT NULL AUTO_INCREMENT,
                id_lang INT(11) NOT NULL,
                `name` VARCHAR(32) NOT NULL,
                PRIMARY KEY(id_special_offer, id_lang)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8'
        );

        $return &= Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'upsell_relation` (
                id_product INT(11) NOT NULL,
                id_related_product INT(11) NOT NULL,
                PRIMARY KEY(id_product, id_related_product)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8'
        );

        $return &= Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'special_offer_cart` (
                id_special_offer INT(11) NOT NULL,
                id_cart INT(11) NOT NULL,
                id_specific_price INT(11) NOT NULL,
                id_customer INT(11) NOT NULL,
                date_expires DATETIME
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8'
        );

        return $return;
    }

    /**
     * @return bool
     */
    public static function uninstall()
    {
        OptionFieldsRepository::deleteOptionFields();

        $return = true;
        $return &= Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'special_offer`');
        $return &= Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'special_offer_group`');
        $return &= Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'special_offer_lang`');
        $return &= Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'upsell_relation`');
        $return &= Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'special_offer_cart`');

        return $return;
    }

    /**
     * Install configuration defined in config() method
     *
     * @return bool
     */
    public static function installConfiguration()
    {
        if (!\Configuration::updateValue(SmartUpsellAdvanced::FEEDBACK_CONFIGURATION, 0)) {
            return false;
        }
        return true;
    }
}
