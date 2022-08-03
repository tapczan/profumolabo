<?php


namespace PrestaShop\Module\FacetedSearch\Filters;


use PrestaShop\Module\FacetedSearch\Adapter\AbstractAdapter;
use PrestaShop\Module\FacetedSearch\Product\PricesDropSearch;
use Validate;

class PricesDropProducts extends Products
{
    /**
     * @var AbstractAdapter
     */
    private $searchAdapter;


    public function __construct(PricesDropSearch $productSearch)
    {
        $this->searchAdapter = $productSearch->getSearchAdapter();
    }


    /**
     * @param int $productsPerPage
     * @param int $page
     * @param string $orderBy
     * @param string $orderWay
     * @param array $selectedFilters
     * @return array
     */
    public function getProductByFilters(
        $productsPerPage,
        $page,
        $orderBy,
        $orderWay,
        $selectedFilters = []
    )
    {
        $orderWay = Validate::isOrderWay($orderWay) ? $orderWay : 'ASC';
        $orderBy = Validate::isOrderBy($orderBy) ? $orderBy : 'position';

        $this->searchAdapter->setLimit((int)$productsPerPage, ((int)$page - 1) * $productsPerPage);
        $this->searchAdapter->setOrderField($orderBy);
        $this->searchAdapter->setOrderDirection($orderWay);

        $this->searchAdapter->addGroupBy('id_product');

        if (isset($selectedFilters['price']) || $orderBy === 'price') {
            $this->searchAdapter->addSelectField('id_product');
            $this->searchAdapter->addSelectField('price');
            $this->searchAdapter->addSelectField('price_min');
            $this->searchAdapter->addSelectField('price_max');
        }

        $matchingProductList = $this->searchAdapter->execute();

        $this->pricePostFiltering($matchingProductList, $selectedFilters);

        $nbrProducts = $this->searchAdapter->count();

        if (empty($nbrProducts)) {
            $matchingProductList = [];
        }

        return [
            'products' => $matchingProductList,
            'count' => $nbrProducts,
        ];

    }
}