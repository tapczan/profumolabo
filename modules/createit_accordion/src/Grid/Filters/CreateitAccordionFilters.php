<?php

declare(strict_types=1);

namespace PrestaShop\Module\CreateitAccordion\Grid\Filters;

use PrestaShop\Module\CreateitAccordion\Grid\Definition\Factory\CreateitAccordionDefinitionFactory;
use PrestaShop\PrestaShop\Core\Search\Filters;

class CreateitAccordionFilters extends Filters
{
    protected $filterId = CreateitAccordionDefinitionFactory::GRID_ID;

    public static function getDefaults()
    {
        return [
            'limit' => 10,
            'offset' => 0,
            'orderBy' => 'id_createit_accordion',
            'sortOrder' => 'asc',
            'filters' => [],
        ];
    }
}