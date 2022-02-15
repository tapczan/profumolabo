CREATE TABLE IF NOT EXISTS `PREFIX_createit_customfield` (
    `id_createit_customfield` int(10) unsigned NOT NULL auto_increment,
    `id_product` int(10) unsigned NOT NULL,
    `id_shop` int(10) unsigned NOT NULL,
    `id_lang` int(10) unsigned NOT NULL,
    `content` text NOT NULL,
    `field_name` VARCHAR(255) NULL DEFAULT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id_createit_customfield`),
    KEY `id_product` (`id_product`),
    KEY `id_shop` (`id_shop`),
    KEY `id_lang` (`id_lang`)
    ) ENGINE=ENGINE_TYPE  DEFAULT CHARSET=utf8;