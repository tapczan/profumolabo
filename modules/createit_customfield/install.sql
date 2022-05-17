CREATE TABLE IF NOT EXISTS `PREFIX_createit_customfield` (
    `id_createit_customfield` int(10) unsigned NOT NULL auto_increment,
    `id_product` int(10) unsigned NOT NULL,
    `id_shop` int(10) unsigned NOT NULL,
    `id_lang` int(10) unsigned NOT NULL,
    `content` text NOT NULL,
    `lang_iso_code` VARCHAR(255) NULL DEFAULT NULL,
    `id_createit_products_customfield` INT(10) UNSIGNED NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id_createit_customfield`),
    KEY `id_product` (`id_product`),
    KEY `id_shop` (`id_shop`),
    KEY `id_createit_products_customfield` ( `id_createit_products_customfield` ),
    KEY `id_lang` (`id_lang`)
    ) ENGINE=ENGINE_TYPE  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_createit_product_customfield` (
    `id_createit_products_customfield` int(10) unsigned NOT NULL auto_increment,
    `field_name` VARCHAR(255) NULL DEFAULT NULL,
    `field_type` int NULL DEFAULT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id_createit_products_customfield`)
    ) ENGINE=ENGINE_TYPE  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_createit_product_customfield_label_lang` (
    `id_createit_products_customfield` INT NOT NULL,
    `id_lang` INT NOT NULL,
    `content` LONGTEXT NOT NULL,
    PRIMARY KEY (`id_createit_products_customfield` , `id_lang`)
)  ENGINE=ENGINE_TYPE  DEFAULT CHARSET=utf8;
