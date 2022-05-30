<?php
$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'information_bar` (
            `id_information_bar` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `mobile` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
            `closeable` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
            `type` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
            `after_end` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
            `date_from` datetime,
            `date_to` datetime,
            `styling` TEXT,
            `custom_css` TEXT,
            `counter` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
            `counter_options` TEXT,
            `button` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
            `button_options` TEXT,
            `active` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
            `date_add` datetime NOT NULL,
            `date_upd` datetime NOT NULL,
            PRIMARY KEY (`id_information_bar`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'information_bar_lang` (
            `id_information_bar` int(10) UNSIGNED NOT NULL,
            `id_lang` int(10) UNSIGNED NOT NULL,
            `name` varchar(255) NOT NULL,
            `text` text NOT NULL,
            `button_text` text NOT NULL,
            `url` text NOT NULL,
            PRIMARY KEY (`id_information_bar`,`id_lang`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'information_bar_shop` (
            `id_information_bar` int(11) UNSIGNED NOT NULL,
            `id_shop` int(11) UNSIGNED NOT NULL,
            PRIMARY KEY (`id_information_bar`,`id_shop`)
        ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';
