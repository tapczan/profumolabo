<?php

/**
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
 * @copyright 2010-2021 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 */
class nvoucher extends ObjectModel
{
    public $id_nvoucher;
    public $email;
    public $code;
    public $deliverydate;
    public static $definition = array(
        'table' => 'nvoucher',
        'primary' => 'id_nvoucher',
        'multilang' => false,
        'fields' => array(
            'id_nvoucher' => array('type' => ObjectModel :: TYPE_INT),
            'email' => array('type' => ObjectModel :: TYPE_STRING),
            'code' => array('type' => ObjectModel :: TYPE_STRING),
            'deliverydate' => array('type' => ObjectModel :: TYPE_DATE),
        ),
    );

    public static function getOneByEmail($email)
    {
        $record = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'nvoucher` WHERE email="' . $email . '"');
        return $record;
    }

    public static function getNewsletterWithoutVoucher($date = false)
    {
        $where_date = '';
        if ($date != false) {
            $where_date = " AND newsletter_date_add > '$date'";
        }

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
        SELECT * FROM `' . _DB_PREFIX_ . 'customer` c
        WHERE c.`email` NOT IN (SELECT email FROM `' . _DB_PREFIX_ . 'nvoucher`)
        AND c.`newsletter` = 1' . $where_date);
    }

    public static function getNewsletterWithoutVoucherBlockNewsletter($date = false)
    {
        $where_date = '';
        if ($date != false) {
            $where_date = " AND newsletter_date_add > '$date'";
        }

        $ps_emailsubscription = (Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("
        SELECT EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '" . _DB_PREFIX_ . "emailsubscription') AS status
        "));

        if ($ps_emailsubscription != 0) {
            return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT * FROM `' . _DB_PREFIX_ . 'emailsubscription` c
            WHERE c.`email` NOT IN (SELECT email FROM `' . _DB_PREFIX_ . 'nvoucher`)
            AND c.`active` = 1' . $where_date);
        }

        $ps_newsletter = (Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow("
        SELECT EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '" . _DB_PREFIX_ . "newsletter') AS status
        "));

        if ($ps_newsletter != 0) {
            return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT * FROM `' . _DB_PREFIX_ . 'newsletter` c
            WHERE c.`email` NOT IN (SELECT email FROM `' . _DB_PREFIX_ . 'nvoucher`)
            AND c.`active` = 1' . $where_date);
        }

        return 0;
    }

    public function __construct($idnv = null)
    {
        parent::__construct($idnv);
    }
}