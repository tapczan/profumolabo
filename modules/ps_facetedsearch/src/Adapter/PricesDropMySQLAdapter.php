<?php

namespace PrestaShop\Module\FacetedSearch\Adapter;


class PricesDropMySQLAdapter extends MySQL
{

    public function getQuery(string $queryType = null)
    {
        $filterToTableMapping = $this->getFieldMapping();
        $orderField = $this->computeOrderByField($filterToTableMapping);

        if ($this->getInitialPopulation() === null) {
            $referenceTable = '(SELECT * FROM `'. _DB_PREFIX_ .'product` where id_product in (SELECT id_product FROM `'. _DB_PREFIX_ .'specific_price`))';
        } else {
            $referenceTable = '(' . $this->getInitialPopulation()->getQuery() . ')';
        }

        $query = 'SELECT ';

        $selectFields = $this->computeSelectFields($filterToTableMapping);
        $whereConditions = $this->computeWhereConditions($filterToTableMapping);
        $joinConditions = $this->computeJoinConditions($filterToTableMapping);
        $groupFields = $this->computeGroupByFields($filterToTableMapping);

        $query .= implode(', ', $selectFields) . ' FROM ' . $referenceTable . ' p';

        foreach ($joinConditions as $joinAliasInfos) {
            foreach ($joinAliasInfos as $tableAlias => $joinInfos) {
                $query .= ' ' . $joinInfos['joinType'] . ' ' . _DB_PREFIX_ . $joinInfos['tableName'] . ' ' .
                    $tableAlias . ' ON ' . $joinInfos['joinCondition'];
            }
        }

        if (!empty($whereConditions)) {
            $query .= ' WHERE ' . implode(' AND ', $whereConditions);
        }

        if ($groupFields) {
            $query .= ' GROUP BY ' . implode(', ', $groupFields);
        }

        if ($orderField) {
            $query .= ' ORDER BY ' . $orderField . ' ' . strtoupper($this->getOrderDirection());
            if ($orderField !== 'p.id_product') {
                $query .= ', p.id_product DESC';
            }
        }

        if ($this->limit !== null) {
            $query .= ' LIMIT ' . $this->offset . ', ' . $this->limit;
        }

        return $query;
    }

    /**
     * @return array
     * @throws \PrestaShopDatabaseException
     */
    public function getMinMaxPriceValue()
    {
        $minQuery = 'SELECT MIN(price) as min FROM (SELECT * FROM `'. _DB_PREFIX_ .'product` where id_product in (SELECT id_product FROM `'. _DB_PREFIX_ .'specific_price`)) as prices_drop_table_min';

        $maxQuery = 'SELECT MAX(price) as max FROM (SELECT * FROM `'. _DB_PREFIX_ .'product` where id_product in (SELECT id_product FROM `'. _DB_PREFIX_ .'specific_price`)) as prices_drop_table_max';

        $minArr = $this->getDatabase()->executeS($minQuery);
        $maxArr = $this->getDatabase()->executeS($maxQuery);

        $min = 0;
        $max = 0;

        try {
            $min = floor((float) $minArr[0]['min']);
        }catch (\Exception $exception){
            $min = 0;
        }

        try {
            $max = ceil((float) $maxArr[0]['max']);
        }catch (\Exception $exception){
            $max = 0;
        }

        return [$min, $max];
    }

}