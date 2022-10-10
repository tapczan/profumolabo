<?php

namespace PrestaShop\Module\FacetedSearch\Product;

use Category;
use Configuration;
use Context;
use FrontController;
use Group;
use PrestaShop\Module\FacetedSearch\Adapter\AbstractAdapter;
use Tools;
use PrestaShop\Module\FacetedSearch\Adapter\PricesDropMySQLAdapter as MySQLAdapter;

class PricesDropSearch extends Search
{
    /**
     * @param Context $context
     * @param string $adapterType
     */
    public function __construct(Context $context, $adapterType = MySQLAdapter::TYPE)
    {
        $this->context = $context;

        switch ($adapterType) {
            case MySQLAdapter::TYPE:
            default:
                $this->searchAdapter = new MySQLAdapter();
        }

        if ($this->psStockManagement === null) {
            $this->psStockManagement = (bool) Configuration::get('PS_STOCK_MANAGEMENT');
        }

        if ($this->psOrderOutOfStock === null) {
            $this->psOrderOutOfStock = (bool) Configuration::get('PS_ORDER_OUT_OF_STOCK');
        }
    }
}