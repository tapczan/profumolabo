<?php

namespace PrestaShop\Module\CreateITCustomField\Grid\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;


class CreateitCustomFieldQueryBuilder extends AbstractDoctrineQueryBuilder
{

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return QueryBuilder
     */
    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        $qb = $this->getQueryBuilder($searchCriteria->getFilters());

        $qb->select('c.id_createit_products_customfield, c.field_name')
            ->groupBy('c.id_createit_products_customfield');

        return $qb;

    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return QueryBuilder
     */
    public function getCountQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        $qb = $this->getQueryBuilder($searchCriteria->getFilters())
            ->select('COUNT(DISTINCT c.id_createit_products_customfield)');

        return $qb;
    }

    /**
     * @param array $filters
     * @return QueryBuilder
     */
    private function getQueryBuilder(array $filters)
    {
        $allowedFilters = ['id_createit_products_customfield','field_name'];

        $qb = $this->connection
            ->createQueryBuilder()
            ->from($this->dbPrefix.'createit_product_customfield', 'c');

        foreach ($filters as $name => $value) {

            if (!in_array($name, $allowedFilters, true)) {
                continue;
            }

            $qb->andWhere("$name LIKE :$name");
            $qb->setParameter($name, '%' . $value . '%');

        }

        return $qb;
    }
}