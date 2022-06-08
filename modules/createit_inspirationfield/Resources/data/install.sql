CREATE TABLE IF NOT EXISTS `PREFIX_createit_inspirationfield` (
    `id_createit_inspirationfield` int(10) unsigned NOT NULL auto_increment,
    `id_product` INT unsigned NOT NULL,
    `id_lang` INT unsigned NOT NULL,
    `content` LONGTEXT NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id_createit_inspirationfield`),
    KEY `id_product` (`id_product`),
    KEY `id_lang` (`id_lang`)
    ) ENGINE=ENGINE_TYPE  DEFAULT CHARSET=utf8;