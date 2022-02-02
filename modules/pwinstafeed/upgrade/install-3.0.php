<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_3_0($object)
{
    $pwdatabase = Db::getInstance()->execute(
        'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'pwinstafeed` (
        `id_pwinstafeed` int(10) unsigned NOT NULL auto_increment,
        `id_shop` int(10) unsigned NOT NULL ,
        `pwinstafeed_pagefeed` int(10) NOT NULL,
        `pwinstafeed_pageuserid` text NOT NULL,
        `pwinstafeed_pagehashtag` text NOT NULL,
        `pwinstafeed_pagelimit` int(10) NOT NULL,
        `pwinstafeed_pagecolumns` int(10) NOT NULL,
        `pwinstafeed_pagemodal` int(10) NOT NULL,
        `pwinstafeed_pagestyle` text NOT NULL,
        PRIMARY KEY (`id_pwinstafeed`))
        ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8'
    );

    $pwdatabase = Db::getInstance()->execute(
        'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'pwinstafeed_lang` (
        `id_pwinstafeed` int(10) unsigned NOT NULL,
        `id_lang` int(10) unsigned NOT NULL,
        `pwinstafeed_pagetitle` varchar(255) NOT NULL,
        `pwinstafeed_pagebreadcrumb` varchar(255) NOT NULL,
        `pwinstafeed_pagecontent` text NOT NULL,
        PRIMARY KEY (`id_pwinstafeed`, `id_lang`))
        ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8'
    );

    $pwdatabase = Db::getInstance()->execute(
        'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'pwinstafeed_stats` (
        `id_pwinstafeed` int(10) unsigned NOT NULL auto_increment,
        `id_shop` int(10) unsigned NOT NULL ,
        `id_product` int(10) unsigned NOT NULL ,
        `nbrimages` int(10) unsigned NOT NULL ,
        `date` date NOT NULL ,
        PRIMARY KEY (`id_pwinstafeed`))
        ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8'
    );
    
    $metas = array();
    $metas[] = setMeta('module-pwinstafeed-pwinstafeed');
    foreach (Theme::getThemes() as $theme) {
        $theme->updateMetas($metas, false);
    }
    
    Configuration::updateValue('pwinstafeed_items1', '8');
    Configuration::updateValue('pwinstafeed_items2', '6');
    Configuration::updateValue('pwinstafeed_items3', '4');
    Configuration::updateValue('pwinstafeed_productitems1', '8');
    Configuration::updateValue('pwinstafeed_productitems2', '6');
    Configuration::updateValue('pwinstafeed_productitems3', '4');
    Configuration::updateValue('pwinstafeed_productlimit', '16');
    Configuration::updateValue('pwinstafeed_productstyle', 'style-1');
    Configuration::updateValue('pwinstafeed_productcolumns', '6');
    Configuration::updateValue('pwinstafeed_bigcolumns', '6');
    Configuration::updateValue('pwinstafeed_smallcolumns', '3');
    Configuration::updateValue('pwinstafeed_productcarousel', '1');
    Configuration::updateValue('pwinstafeed_productinfinite', '1');
    Configuration::updateValue('pwinstafeed_pagefeed', '1');
    Configuration::updateValue('pwinstafeed_pageuserid', 'prestashop');
    Configuration::updateValue('pwinstafeed_pagehashtag', '');
    Configuration::updateValue('pwinstafeed_pagelimit', '12');
    Configuration::updateValue('pwinstafeed_pagecolumns', '4');
    Configuration::updateValue('pwinstafeed_pagemodal', '1');
    Configuration::updateValue('pwinstafeed_pagestyle', 'style-1');
    
    return true;
}

function setMeta($name)
{
    $metas = array();
    $sql = "SELECT id_meta FROM `"._DB_PREFIX_."meta` WHERE page='$name'";
    $id_meta = Db::getInstance()->getValue($sql);
    if ((int)$id_meta==0) {
        $meta = new Meta();
        $meta->page = $name;
        $meta->configurable = 1;
        $meta->add();

        $metas['id_meta'] = (int)$meta->id;
        $metas['left'] = 0;
        $metas['right'] = 0;
    } else {
        $metas['id_meta'] = (int)$id_meta;
        $metas['left'] = 0;
        $metas['right'] = 0;
    }
    return $metas;
}