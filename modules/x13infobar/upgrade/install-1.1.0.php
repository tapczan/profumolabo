<?php
/**
* @author    Krystian Podemski for x13.pl <krystian@x13.pl>
* @copyright Copyright (c) 2019 Krystian Podemski - www.x13.pl
* @license   Commercial license, only to use on restricted domains
*/
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_1_0($module)
{
    Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'information_bar` ADD closeable tinyint(1) NOT NULL DEFAULT 0 AFTER mobile');

    return true;
}
