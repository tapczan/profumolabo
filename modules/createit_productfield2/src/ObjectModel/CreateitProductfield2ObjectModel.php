<?php

declare(strict_types=1);

namespace PrestaShop\Module\CreateitProductfield2\ObjectModel;

use PrestaShop\PrestaShop\Adapter\Entity\ObjectModel;

class CreateitProductfield2ObjectModel extends ObjectModel
{
    public $id_createit_productfield2;
    public $id_product;
    public $id_product_linked;
    public $created_at;
    public $updated_at;

    public static $definition = [
        'table' => 'createit_productfield2',
        'primary' => 'id_createit_productfield2',
        'fields' => [
            'id_product' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true],
            'id_product_linked' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId'],
            'created_at' => ['type' => self::TYPE_DATE],
            'updated_at' => ['type' => self::TYPE_DATE]
        ]
    ];

}