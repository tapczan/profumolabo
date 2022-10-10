<?php

namespace PrestaShop\Module\FacetedSearch\Product;

use Context;

class PricesDropSearchFactory
{
    /**
     * @param Context $context
     * @return PricesDropSearch
     */
    public function build(Context $context)
    {
        return new PricesDropSearch($context);
    }
}