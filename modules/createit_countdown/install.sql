CREATE TABLE IF NOT EXISTS `PREFIX_createit_countdown` (
    `id_createit_countdown` int(10) unsigned NOT NULL auto_increment,
    `setting` VARCHAR(255) NULL DEFAULT NULL,
    `value` VARCHAR(255) NULL DEFAULT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id_createit_countdown`)
    ) ENGINE=ENGINE_TYPE  DEFAULT CHARSET=utf8;