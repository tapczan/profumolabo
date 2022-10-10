<?php
/**
* @author    Krystian Podemski for x13.pl <krystian@x13.pl>
* @copyright Copyright (c) 2020 Krystian Podemski - www.x13.pl
* @license   Commercial license, only to use on restricted domains
*/
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_2_0()
{
    Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . 'information_bar_lang` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;');
    
    return true;
}
