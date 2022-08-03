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

}