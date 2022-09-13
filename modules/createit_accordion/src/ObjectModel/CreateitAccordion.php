<?php

declare(strict_types=1);

namespace PrestaShop\Module\CreateitAccordion\ObjectModel;

use PrestaShop\PrestaShop\Adapter\Entity\ObjectModel;

class CreateitAccordion extends ObjectModel
{
    public $id_createit_accordion_content;
    public $id_createit_accordion;
    public $id_lang;
    public $id_product;
    public $content;
    public $created_at;
    public $updated_at;

    public static $definition = [
        'table' => 'createit_accordion_content',
        'primary' => 'id_createit_accordion_content',
        'fields' => [
            'id_createit_accordion' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true],
            'id_lang' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true],
            'id_product' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true],
            'content' => ['type' => self::TYPE_HTML],
            'created_at' => ['type' => self::TYPE_DATE],
            'updated_at' => ['type' => self::TYPE_DATE]
            ]
        ];
}