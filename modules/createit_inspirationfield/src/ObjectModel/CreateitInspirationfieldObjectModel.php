<?php

declare(strict_types=1);

namespace PrestaShop\Module\CreateitInspirationfield\ObjectModel;

use PrestaShop\PrestaShop\Adapter\Entity\ObjectModel;


class CreateitInspirationfieldObjectModel extends ObjectModel
{
    public $id_createit_inspirationfield;
    public $id_product;
    public $id_lang;
    public $content;
    public $created_at;
    public $updated_at;

    public static $definition = [
        'table' => 'createit_inspirationfield',
        'primary' => 'id_createit_inspirationfield',
        'fields' => [
            'id_product' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true],
            'id_lang' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true],
            'content' => ['type' => self::TYPE_HTML],
            'created_at' => ['type' => self::TYPE_DATE],
            'updated_at' => ['type' => self::TYPE_DATE]
        ]
    ];
}