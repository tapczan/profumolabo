<?php


class CreateitCustomField extends ObjectModel
{
    public $id_createit_customfield;
    public $id_product;
    public $id_shop;
    public $id_lang;
    public $content;
    public $field_name;
    public $created_at;
    public $updated_at;

    public static $definition = [
        'table' => 'createit_customfield',
        'primary' => 'id_createit_customfield',
        'fields' => [
            'id_product' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true],
            'id_shop' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true],
            'id_lang' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true],
            'content' => ['type' => self::TYPE_STRING, 'validate' => 'isMessage', 'size' => 65535],
            'field_name' => ['type' => self::TYPE_STRING, 'validate' => 'isMessage', 'size' => 65535],
            'created_at' => ['type' => self::TYPE_DATE],
            'updated_at' => ['type' => self::TYPE_DATE]
        ]
    ];
}