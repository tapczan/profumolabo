<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony;

use Db;

class RawDbActions
{
    public static function createAllTables()
    {
        $ret = [];

        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eparagony_document_status` (
                `id_document_state` INT UNSIGNED AUTO_INCREMENT NOT NULL,
                `id_order` INT UNSIGNED NOT NULL,
                `document_state` VARCHAR(255) NOT NULL,
                `document_type` VARCHAR(255) DEFAULT NULL,
                `text_id` VARCHAR(255) NOT NULL,
                `updated` DATETIME NOT NULL,
                `checked` DATETIME NOT NULL,
                `transitions` LONGTEXT,
                `retry_count` INT,
                `rest` LONGTEXT NOT NULL,
                UNIQUE INDEX UNIQ_2E9C1F201BACD2A8 (`id_order`),
                UNIQUE INDEX UNIQ_2E9C1F20698D3548 (`text_id`),
                PRIMARY KEY(`id_document_state`)
        )';
        $ret[] = Db::getInstance()->execute($sql);

        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eparagony_printer_token` (
                `id_document_state` INT UNSIGNED AUTO_INCREMENT NOT NULL,
                `token` VARCHAR(255) NOT NULL,
                `created` DATETIME NOT NULL,
                `valid_to` DATETIME NOT NULL,
                `privileges` LONGTEXT NOT NULL,
                 UNIQUE INDEX UNIQ_41CE29835F37A13B (`token`),
                 PRIMARY KEY(`id_document_state`)
        )';
        $ret[] = Db::getInstance()->execute($sql);

        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eparagony_cart_config` (
                `id_document_state` INT UNSIGNED AUTO_INCREMENT NOT NULL,
                `id_cart` INT UNSIGNED NOT NULL,
                `rest` LONGTEXT NOT NULL,
                UNIQUE INDEX UNIQ_3FEB9AAA808394B5 (`id_cart`),
                PRIMARY KEY(`id_document_state`)
        )';
        $ret[] = Db::getInstance()->execute($sql);

        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'eparagony_printer_log` (
                `id_printer_log` INT UNSIGNED AUTO_INCREMENT NOT NULL,
                `document_text_id` VARCHAR(255) NOT NULL,
                `created` DATETIME NOT NULL,
                `rest` LONGTEXT NOT NULL,
                PRIMARY KEY(`id_printer_log`)
        );';
        $ret[] = Db::getInstance()->execute($sql);

        $reduceAnd = function ($a, $b) {
            return $a && $b;
        };

        return array_reduce($ret, $reduceAnd, true);
    }
}
