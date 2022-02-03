<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_3_2($object)
{
    $pwdatabase = Db::getInstance()->execute(
        'DROP TABLE IF EXISTS '._DB_PREFIX_.'pwinstafeed'
    );

    $pwdatabase = Db::getInstance()->execute(
        'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'pwinstafeed` (
        `id_pwinstafeed` int(10) unsigned NOT NULL auto_increment,
        `id_shop` int(10) unsigned NOT NULL ,
        `pwinstafeed_pagelimit` int(10) NOT NULL,
        `pwinstafeed_pagegrid_xs` int(10) NOT NULL,
        `pwinstafeed_pagegrid_sm` int(10) NOT NULL,
        `pwinstafeed_pagegrid_md` int(10) NOT NULL,
        `pwinstafeed_pagegrid_lg` int(10) NOT NULL,
        `pwinstafeed_pagegrid_xl` int(10) NOT NULL,
        `pwinstafeed_grid_xs` int(10) NOT NULL,
        `pwinstafeed_grid_sm` int(10) NOT NULL,
        `pwinstafeed_grid_md` int(10) NOT NULL,
        `pwinstafeed_grid_lg` int(10) NOT NULL,
        `pwinstafeed_grid_xl` int(10) NOT NULL,
        `pwinstafeed_pagemodal` tinyint(4) NOT NULL,
        `pwinstafeed_pagelikes` tinyint(4) NOT NULL,
        `pwinstafeed_pagecomments` tinyint(4) NOT NULL,
        `pwinstafeed_pagestyle` text NOT NULL,
        `pwinstafeed_bgcolor` text NOT NULL,
        `pwinstafeed_fgcolor` text NOT NULL,
        `pwinstafeed_pagebgcolor` text NOT NULL,
        `pwinstafeed_pagefgcolor` text NOT NULL,
        `pwinstafeed_pagebtnbgcolor` text NOT NULL,
        `pwinstafeed_pagebtnfgcolor` text NOT NULL,
        `pwinstafeed_pagespacing` text NOT NULL,
        PRIMARY KEY (`id_pwinstafeed`))
        ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8'
    );

    $pwdatabase = Db::getInstance()->execute(
        'DROP TABLE IF EXISTS '._DB_PREFIX_.'pwinstafeed_lang'
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

    Configuration::updateValue('pwinstafeed_accesstoken', '');
    Configuration::updateValue('pwinstafeed_limit', '16');
    Configuration::updateValue('pwinstafeed_items1', '4');
    Configuration::updateValue('pwinstafeed_items2', '5');
    Configuration::updateValue('pwinstafeed_items3', '6');
    Configuration::updateValue('pwinstafeed_items4', '7');
    Configuration::updateValue('pwinstafeed_items5', '8');
    Configuration::updateValue('pwinstafeed_hook', '1');
    Configuration::updateValue('pwinstafeed_style', 'rounded');
    Configuration::updateValue('pwinstafeed_carousel', '1');
    Configuration::updateValue('pwinstafeed_infinite', '1');
    Configuration::updateValue('pwinstafeed_modal', '1');
    Configuration::updateValue('pwinstafeed_likes', '1');
    Configuration::updateValue('pwinstafeed_comments', '1');
    Configuration::updateValue('pwinstafeed_pagelimit', '12');
    Configuration::updateValue('pwinstafeed_pagegrid_xs', '12');
    Configuration::updateValue('pwinstafeed_pagegrid_sm', '6');
    Configuration::updateValue('pwinstafeed_pagegrid_md', '4');
    Configuration::updateValue('pwinstafeed_pagegrid_lg', '3');
    Configuration::updateValue('pwinstafeed_pagegrid_xl', '2');
    Configuration::updateValue('pwinstafeed_grid_xs', '12');
    Configuration::updateValue('pwinstafeed_grid_sm', '6');
    Configuration::updateValue('pwinstafeed_grid_md', '4');
    Configuration::updateValue('pwinstafeed_grid_lg', '3');
    Configuration::updateValue('pwinstafeed_grid_xl', '2');
    Configuration::updateValue('pwinstafeed_pagespacing', '30');
    Configuration::updateValue('pwinstafeed_spacing', '30');
    Configuration::updateValue('pwinstafeed_pagemodal', true);
    Configuration::updateValue('pwinstafeed_pagelikes', true);
    Configuration::updateValue('pwinstafeed_pagecomments', true);
    Configuration::updateValue('pwinstafeed_pagestyle', 'rounded');
    Configuration::updateValue('pwinstafeed_pagebgcolor', 'rgba(0, 0, 0, 0.5)');
    Configuration::updateValue('pwinstafeed_pagefgcolor', '#ffffff');
    Configuration::updateValue('pwinstafeed_bgcolor', 'rgba(0, 0, 0, 0.5)');
    Configuration::updateValue('pwinstafeed_fgcolor', '#ffffff');
    Configuration::updateValue('pwinstafeed_btnbgcolor', '#333333');
    Configuration::updateValue('pwinstafeed_btnfgcolor', '#ffffff');
    Configuration::updateValue('pwinstafeed_pagebtnbgcolor', '#1aafd0');
    Configuration::updateValue('pwinstafeed_pagebtnfgcolor', '#ffffff');

    return true;
}
