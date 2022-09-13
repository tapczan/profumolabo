CREATE TABLE IF NOT EXISTS `PREFIX_createit_accordion` (
    `id_createit_accordion` int(10) unsigned NOT NULL auto_increment,
    `id_shop` int(10) unsigned NOT NULL,
    `field_name` VARCHAR(255) NULL DEFAULT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id_createit_accordion`)
    ) ENGINE=ENGINE_TYPE  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_createit_accordion_header` (
    `id_createit_accordion_header` int(10) UNSIGNED NOT NULL auto_increment,
    `id_createit_accordion` INT(10) UNSIGNED NOT NULL,
    `id_lang` INT NOT NULL,
    `content` LONGTEXT NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id_createit_accordion_header`),
    KEY `id_createit_accordion` ( `id_createit_accordion` ),
    KEY `id_lang` (`id_lang`)
    )  ENGINE=ENGINE_TYPE  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_createit_accordion_content` (
    `id_createit_accordion_content` int(10) unsigned NOT NULL auto_increment,
    `id_createit_accordion` INT(10) UNSIGNED NOT NULL,
    `id_lang` int(10) unsigned NOT NULL,
    `id_product` int(10) unsigned NOT NULL,
    `content` LONGTEXT NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id_createit_accordion_content`),
    KEY `id_createit_accordion` ( `id_createit_accordion` ),
    KEY `id_lang` (`id_lang`)
    ) ENGINE=ENGINE_TYPE  DEFAULT CHARSET=utf8;