CREATE TABLE IF NOT EXISTS `PREFIX_createit_productfield2` (
    `id_createit_productfield2` int(10) unsigned NOT NULL auto_increment,
    `id_product` int(10) unsigned NOT NULL,
    `id_product_linked` int(10) unsigned NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id_createit_productfield2`),
    KEY `id_product` (`id_product`),
    KEY `id_product_linked` (`id_product_linked`)
    ) ENGINE=ENGINE_TYPE  DEFAULT CHARSET=utf8;