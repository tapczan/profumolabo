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

include_once ('../../config/config.inc.php');
include_once ('../../init.php');
include_once ('newslettervoucher.php');
$module = new newslettervoucher;

if (Tools::getValue('key') == $module->secure_key){
    $module->cronJob();
} else {
    echo "access denied";
}