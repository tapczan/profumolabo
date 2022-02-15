<?php

namespace PrestaShop\Module\CreateITCustomField\Grid\Filters;

use PrestaShop\Module\CreateITCustomField\Grid\Definition\Factory\CreateitCustomFieldDefinitionFactory;
use PrestaShop\PrestaShop\Core\Search\Filters;

class CreateitCustomFieldFilters extends Filters
{
    protected $filterId = CreateitCustomFieldDefinitionFactory::GRID_ID;

    public static function getDefaults()
    {
        return [
            'limit' => 10,
            'offset' => 0,
            'orderBy' => 'id_createit_products_customfield',
            'sortOrder' => 'asc',
            'filters' => [],
        ];
    }
}

