<?php


class CreateitCustomField extends ObjectModel
{
    public $id_createit_customfield;
    public $id_product;
    public $id_shop;
    public $id_lang;
    public $content;
    public $id_createit_products_customfield;
    public $created_at;
    public $updated_at;
    public $lang_iso_code;

    public static $definition = [
        'table' => 'createit_customfield',
        'primary' => 'id_createit_customfield',
        'fields' => [
            'id_product' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true],
            'id_shop' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true],
            'id_lang' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true],
            'lang_iso_code' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true],
            'content' => ['type' => self::TYPE_HTML],
            'id_createit_products_customfield' => ['type' => self::TYPE_STRING, 'validate' => 'isMessage', 'size' => 65535],
            'created_at' => ['type' => self::TYPE_DATE],
            'updated_at' => ['type' => self::TYPE_DATE]
        ]
    ];
}