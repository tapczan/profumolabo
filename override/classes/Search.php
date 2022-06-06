<?php
 
class Search extends SearchCore
{
    
    public static function find(
        $id_lang,
        $expr,
        $page_number = 1,
        $page_size = 1,
        $order_by = 'position',
        $order_way = 'desc',
        $ajax = false,
        $use_cookie = true,
        Context $context = null
    ) {
        if (!$context) {
            $context = Context::getContext();
        }

        $db = Db::getInstance(_PS_USE_SQL_SLAVE_);

        // TODO : smart page management
        if ($page_number < 1) {
            $page_number = 1;
        }
        if ($page_size < 1) {
            $page_size = 1;
        }

        if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way)) {
            return false;
        }

        $scoreArray = [];
        $fuzzyLoop = 0;
        $eligibleProducts2 = null;
        $words = Search::extractKeyWords($expr, $id_lang, false, $context->language->iso_code);
        $fuzzyMaxLoop = (int) Configuration::get('PS_SEARCH_FUZZY_MAX_LOOP');
        $psFuzzySearch = (int) Configuration::get('PS_SEARCH_FUZZY');
        $psSearchMinWordLength = (int) Configuration::get('PS_SEARCH_MINWORDLEN');

        foreach ($words as $key => $word) {
            if (empty($word) || strlen($word) < $psSearchMinWordLength) {
                unset($words[$key]);
                continue;
            }

            $sql_param_search = self::getSearchParamFromWord($word);
            $sql = 'SELECT DISTINCT si.id_product ' .
                 'FROM ' . _DB_PREFIX_ . 'search_word sw ' .
                 'LEFT JOIN ' . _DB_PREFIX_ . 'search_index si ON sw.id_word = si.id_word ' .
                 'LEFT JOIN ' . _DB_PREFIX_ . 'product_shop product_shop ON (product_shop.`id_product` = si.`id_product`) ' .
                 'WHERE sw.id_lang = ' . (int) $id_lang . ' ' .
                 'AND sw.id_shop = ' . $context->shop->id . ' ' .
                 'AND product_shop.`active` = 1 ' .
                 'AND product_shop.`visibility` IN ("both", "search") ' .
                 'AND product_shop.indexed = 1 ' .
                 'AND sw.word LIKE ';

            while (!($result = $db->executeS($sql . "'" . $sql_param_search . "';", true, false))) {
                if (!$psFuzzySearch
                    || $fuzzyLoop++ > $fuzzyMaxLoop
                    || !($sql_param_search = static::findClosestWeightestWord($context, $word))
                ) {
                    break;
                }
            }

            if (!$result) {
                unset($words[$key]);
                continue;
            }

            $productIds = array_column($result, 'id_product');
            if ($eligibleProducts2 === null) {
                $eligibleProducts2 = $productIds;
            } else {
                $eligibleProducts2 = array_intersect($eligibleProducts2, $productIds);
            }

            $scoreArray[] = 'sw.word LIKE \'' . $sql_param_search . '\'';
        }

        if (!count($words) || !count($eligibleProducts2)) {
            return $ajax ? [] : ['total' => 0, 'result' => []];
        }

        $sqlScore = '';
        if (!empty($scoreArray) && is_array($scoreArray)) {
            $sqlScore = ',( ' .
                'SELECT SUM(weight) ' .
                'FROM ' . _DB_PREFIX_ . 'search_word sw ' .
                'LEFT JOIN ' . _DB_PREFIX_ . 'search_index si ON sw.id_word = si.id_word ' .
                'WHERE sw.id_lang = ' . (int) $id_lang . ' ' .
                'AND sw.id_shop = ' . $context->shop->id . ' ' .
                'AND si.id_product = p.id_product ' .
                'AND (' . implode(' OR ', $scoreArray) . ') ' .
                ') position';
        }

        $sqlGroups = '';
        if (Group::isFeatureActive()) {
            $groups = FrontController::getCurrentCustomerGroups();
            $sqlGroups = 'AND cg.`id_group` ' . (count($groups) ? 'IN (' . implode(',', $groups) . ')' : '=' . (int) Group::getCurrent()->id);
        }

        $results = $db->executeS(
            'SELECT DISTINCT cp.`id_product` ' .
            'FROM `' . _DB_PREFIX_ . 'category_product` cp ' .
            (Group::isFeatureActive() ? 'INNER JOIN `' . _DB_PREFIX_ . 'category_group` cg ON cp.`id_category` = cg.`id_category`' : '') . ' ' .
            'INNER JOIN `' . _DB_PREFIX_ . 'category` c ON cp.`id_category` = c.`id_category` ' .
            'INNER JOIN `' . _DB_PREFIX_ . 'product` p ON cp.`id_product` = p.`id_product` ' .
            Shop::addSqlAssociation('product', 'p', false) . ' ' .
            'WHERE c.`active` = 1 ' .
            'AND product_shop.`active` = 1 ' .
            'AND product_shop.`visibility` IN ("both", "search") ' .
            'AND product_shop.indexed = 1 ' .
            'AND cp.id_product IN (' . implode(',', $eligibleProducts2) . ')' . $sqlGroups,
            true,
            false
        );

        $eligibleProducts = [];
        foreach ($results as $row) {
            $eligibleProducts[] = $row['id_product'];
        }

        if (!count($eligibleProducts)) {
            return $ajax ? [] : ['total' => 0, 'result' => []];
        }

        $product_pool = ' IN (' . implode(',', $eligibleProducts) . ') ';

        if ($ajax) {
            $sql = 'SELECT DISTINCT p.id_product, pl.name pname, cl.name cname,
						cl.link_rewrite crewrite, pl.link_rewrite prewrite ' . $sqlScore . '
					FROM ' . _DB_PREFIX_ . 'product p
					INNER JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (
						p.`id_product` = pl.`id_product`
						AND pl.`id_lang` = ' . (int) $id_lang . Shop::addSqlRestrictionOnLang('pl') . '
					)
					' . Shop::addSqlAssociation('product', 'p') . '
					INNER JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (
						product_shop.`id_category_default` = cl.`id_category`
						AND cl.`id_lang` = ' . (int) $id_lang . Shop::addSqlRestrictionOnLang('cl') . '
					)
					WHERE p.`id_product` ' . $product_pool . '
					ORDER BY position DESC LIMIT 10';

            return $db->executeS($sql, true, false);
        }

        if (strpos($order_by, '.') > 0) {
            $order_by = explode('.', $order_by);
            $order_by = pSQL($order_by[0]) . '.`' . pSQL($order_by[1]) . '`';
        }
        $alias = '';
        if ($order_by == 'price') {
            $alias = 'product_shop.';
        } elseif (in_array($order_by, ['date_upd', 'date_add'])) {
            $alias = 'p.';
        }
        $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity,
				pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`name`,
			 image_shop.`id_image` id_image, il.`legend`, m.`name` manufacturer_name ' . $sqlScore . ',
				DATEDIFF(
					p.`date_add`,
					DATE_SUB(
						"' . date('Y-m-d') . ' 00:00:00",
						INTERVAL ' . (Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20) . ' DAY
					)
				) > 0 new' . (Combination::isFeatureActive() ? ', product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, IFNULL(product_attribute_shop.`id_product_attribute`,0) id_product_attribute' : '') . '
				FROM ' . _DB_PREFIX_ . 'product p
				' . Shop::addSqlAssociation('product', 'p') . '
				INNER JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (
					p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = ' . (int) $id_lang . Shop::addSqlRestrictionOnLang('pl') . '
				)
				' . (Combination::isFeatureActive() ? 'LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` product_attribute_shop FORCE INDEX (id_product)
				    ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop=' . (int) $context->shop->id . ')' : '') . '
				' . Product::sqlStock('p', 0) . '
				LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m FORCE INDEX (PRIMARY)
				    ON m.`id_manufacturer` = p.`id_manufacturer`
				LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop FORCE INDEX (id_product)
					ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop=' . (int) $context->shop->id . ')
				LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int) $id_lang . ')
				WHERE p.`id_product` ' . $product_pool . '
				GROUP BY product_shop.id_product';

        if ($order_by !== 'price') {
            $sql .= ($order_by ? ' ORDER BY  ' . $alias . $order_by : '') . ($order_way ? ' ' . $order_way : '') . '
				LIMIT ' . (int) (($page_number - 1) * $page_size) . ',' . (int) $page_size;
        }

        $result = $db->executeS($sql, true, false);

        if ($order_by === 'price') {
            Tools::orderbyPrice($result, $order_way);
            $result = array_slice($result, (int) (($page_number - 1) * $page_size), (int) $page_size);
        }

        $sql = 'SELECT COUNT(*)
				FROM ' . _DB_PREFIX_ . 'product p
				' . Shop::addSqlAssociation('product', 'p') . '
				INNER JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (
					p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = ' . (int) $id_lang . Shop::addSqlRestrictionOnLang('pl') . '
				)
				LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
				WHERE p.`id_product` ' . $product_pool;
        $total = $db->getValue($sql, false);

        if (!$result) {
            $result_properties = false;
        } else {
            $result_properties = Product::getProductsProperties((int) $id_lang, $result);
        }

        $result_properties = Search::exclude_category($result_properties );

        return ['total' => $total, 'result' => $result_properties];
    }

    /**
     * 
     * exclude category drogeria/drugstore products on search results
     */
    public static function exclude_category($products) {
        
        $result = [];

        if(!empty($products)) {

            foreach($products as $product) {
                
                // Production category id 140 = Drogeria/Drugstore
                if( $product['id_category_default'] == '140') {
                    continue;
                }
                $result[] = $product;
            }
        }   

        return $result;
    }


}
