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

    /**
     *
     * Override method to re-index createit product field 2
     *
     * @param $total_languages
     * @param false $id_product
     * @param int $limit
     * @param array $weight_array
     * @return array|bool|mysqli_result|PDOStatement|resource|null
     * @throws PrestaShopDatabaseException
     */
    protected static function getProductsToIndex($total_languages, $id_product = false, $limit = 50, $weight_array = [])
    {
        $ids = null;
        if (!$id_product) {
            // Limit products for each step but be sure that each attribute is taken into account
            $sql = 'SELECT p.id_product FROM ' . _DB_PREFIX_ . 'product p
				' . Shop::addSqlAssociation('product', 'p', true, null, true) . '
				WHERE product_shop.`indexed` = 0
				AND product_shop.`visibility` IN ("both", "search")
				AND product_shop.`active` = 1
				ORDER BY product_shop.`id_product` ASC
				LIMIT ' . (int) $limit;

            $res = Db::getInstance()->executeS($sql, false);
            while ($row = Db::getInstance()->nextRow($res)) {
                $ids[] = $row['id_product'];
            }
        }

        // Now get every attribute in every language
        $sql = 'SELECT p.id_product, pl.id_lang, pl.id_shop, l.iso_code, cp2select.linked_product';

        if (is_array($weight_array)) {
            foreach ($weight_array as $key => $weight) {
                if ((int) $weight) {
                    switch ($key) {
                        case 'pname':
                            $sql .= ', pl.name pname';

                            break;
                        case 'reference':
                            $sql .= ', p.reference';

                            break;
                        case 'supplier_reference':
                            $sql .= ', p.supplier_reference';

                            break;
                        case 'ean13':
                            $sql .= ', p.ean13';

                            break;
                        case 'isbn':
                            $sql .= ', p.isbn';

                            break;
                        case 'upc':
                            $sql .= ', p.upc';

                            break;
                        case 'mpn':
                            $sql .= ', p.mpn';

                            break;
                        case 'description_short':
                            $sql .= ', pl.description_short';

                            break;
                        case 'description':
                            $sql .= ', pl.description';

                            break;
                        case 'cname':
                            $sql .= ', cl.name cname';

                            break;
                        case 'mname':
                            $sql .= ', m.name mname';

                            break;
                    }
                }
            }
        }

        $sql .= ' FROM ' . _DB_PREFIX_ . 'product p
			LEFT JOIN ' . _DB_PREFIX_ . 'product_lang pl
				ON p.id_product = pl.id_product
			' . Shop::addSqlAssociation('product', 'p', true, null, true) . '
			LEFT JOIN ' . _DB_PREFIX_ . 'category_lang cl
				ON (cl.id_category = product_shop.id_category_default AND pl.id_lang = cl.id_lang AND cl.id_shop = product_shop.id_shop)
			LEFT JOIN ' . _DB_PREFIX_ . 'manufacturer m
				ON m.id_manufacturer = p.id_manufacturer
            LEFT JOIN ' . _DB_PREFIX_ . 'createit_productfield2 cp2
				ON p.id_product = cp2.id_product_linked
			LEFT JOIN ' . _DB_PREFIX_ . 'lang l
				ON l.id_lang = pl.id_lang
            LEFT JOIN (
                SELECT 
                    cp2.id_product,
                    pl2.name AS linked_product
                FROM
                    ' . _DB_PREFIX_ . 'createit_productfield2 AS cp2
                LEFT JOIN
                    ' . _DB_PREFIX_ . 'product p2 ON (cp2.`id_product_linked` = p2.`id_product`)
                LEFT JOIN
                    ' . _DB_PREFIX_ . 'product_lang pl2 ON (cp2.`id_product_linked` = pl2.`id_product`)
            )  as cp2select ON  (cp2select.`id_product` =  p.id_product)
			WHERE product_shop.indexed = 0
			AND product_shop.visibility IN ("both", "search")
			' . ($id_product ? 'AND p.id_product = ' . (int) $id_product : '') . '
			' . ($ids ? 'AND p.id_product IN (' . implode(',', array_map('intval', $ids)) . ')' : '') . '
			AND product_shop.`active` = 1
			AND pl.`id_shop` = product_shop.`id_shop`';

        return Db::getInstance()->executeS($sql, true, false);
    }

    public static function indexation($full = false, $id_product = false)
    {
        $db = Db::getInstance();

        if ($id_product) {
            $full = false;
        }

        if ($full && Context::getContext()->shop->getContext() == Shop::CONTEXT_SHOP) {
            $db->execute('DELETE si, sw FROM `' . _DB_PREFIX_ . 'search_index` si
				INNER JOIN `' . _DB_PREFIX_ . 'product` p ON (p.id_product = si.id_product)
				' . Shop::addSqlAssociation('product', 'p') . '
				INNER JOIN `' . _DB_PREFIX_ . 'search_word` sw ON (sw.id_word = si.id_word AND product_shop.id_shop = sw.id_shop)
				WHERE product_shop.`visibility` IN ("both", "search")
				AND product_shop.`active` = 1');
            $db->execute('UPDATE `' . _DB_PREFIX_ . 'product` p
				' . Shop::addSqlAssociation('product', 'p') . '
				SET p.`indexed` = 0, product_shop.`indexed` = 0
				WHERE product_shop.`visibility` IN ("both", "search")
				AND product_shop.`active` = 1
				');
        } elseif ($full) {
            $db->execute('TRUNCATE ' . _DB_PREFIX_ . 'search_index');
            $db->execute('TRUNCATE ' . _DB_PREFIX_ . 'search_word');
            ObjectModel::updateMultishopTable('Product', ['indexed' => 0]);
        } else {
            $db->execute('DELETE si FROM `' . _DB_PREFIX_ . 'search_index` si
				INNER JOIN `' . _DB_PREFIX_ . 'product` p ON (p.id_product = si.id_product)
				' . Shop::addSqlAssociation('product', 'p') . '
				WHERE product_shop.`visibility` IN ("both", "search")
				AND product_shop.`active` = 1
				AND ' . ($id_product ? 'p.`id_product` = ' . (int) $id_product : 'product_shop.`indexed` = 0'));

            $db->execute('UPDATE `' . _DB_PREFIX_ . 'product` p
				' . Shop::addSqlAssociation('product', 'p') . '
				SET p.`indexed` = 0, product_shop.`indexed` = 0
				WHERE product_shop.`visibility` IN ("both", "search")
				AND product_shop.`active` = 1
				AND ' . ($id_product ? 'p.`id_product` = ' . (int) $id_product : 'product_shop.`indexed` = 0'));
        }

        // Every fields are weighted according to the configuration in the backend
        $weight_array = [
            'pname' => Configuration::get('PS_SEARCH_WEIGHT_PNAME'),
            'reference' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
            'pa_reference' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
            'supplier_reference' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
            'pa_supplier_reference' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
            'ean13' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
            'pa_ean13' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
            'isbn' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
            'pa_isbn' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
            'upc' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
            'pa_upc' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
            'mpn' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
            'pa_mpn' => Configuration::get('PS_SEARCH_WEIGHT_REF'),
            'description_short' => Configuration::get('PS_SEARCH_WEIGHT_SHORTDESC'),
            'description' => Configuration::get('PS_SEARCH_WEIGHT_DESC'),
            'cname' => Configuration::get('PS_SEARCH_WEIGHT_CNAME'),
            'mname' => Configuration::get('PS_SEARCH_WEIGHT_MNAME'),
            'tags' => Configuration::get('PS_SEARCH_WEIGHT_TAG'),
            'attributes' => Configuration::get('PS_SEARCH_WEIGHT_ATTRIBUTE'),
            'features' => Configuration::get('PS_SEARCH_WEIGHT_FEATURE'),
            'linked_product' => 6, // added linked product to index create it products
        ];

        // Those are kind of global variables required to save the processed data in the database every X occurrences, in order to avoid overloading MySQL
        $count_words = 0;
        $query_array3 = [];

        // Retrieve the number of languages
        $total_languages = count(Language::getIDs(false));

        $sql_attribute = Search::getSQLProductAttributeFields($weight_array);
        // Products are processed 50 by 50 in order to avoid overloading MySQL
        while (($products = Search::getProductsToIndex($total_languages, $id_product, 50, $weight_array)) && (count($products) > 0)) {

            $products_array = [];
            // Now each non-indexed product is processed one by one, langage by langage
            foreach ($products as $product) {
                if ((int) $weight_array['tags']) {
                    $product['tags'] = Search::getTags($db, (int) $product['id_product'], (int) $product['id_lang']);
                }
                if ((int) $weight_array['attributes']) {
                    $product['attributes'] = Search::getAttributes($db, (int) $product['id_product'], (int) $product['id_lang']);
                }
                if ((int) $weight_array['features']) {
                    $product['features'] = Search::getFeatures($db, (int) $product['id_product'], (int) $product['id_lang']);
                }
                if ($sql_attribute) {
                    $attribute_fields = Search::getAttributesFields($db, (int) $product['id_product'], $sql_attribute);
                    if ($attribute_fields) {
                        $product['attributes_fields'] = $attribute_fields;
                    }
                }

                // Data must be cleaned of html, bad characters, spaces and anything, then if the resulting words are long enough, they're added to the array
                $product_array = [];
                foreach ($product as $key => $value) {
                    if ($key == 'attributes_fields') {
                        foreach ($value as $pa_array) {
                            foreach ($pa_array as $pa_key => $pa_value) {
                                Search::fillProductArray($product_array, $weight_array, $pa_key, $pa_value, $product['id_lang'], $product['iso_code']);
                            }
                        }
                    } else {
                        Search::fillProductArray($product_array, $weight_array, $key, $value, $product['id_lang'], $product['iso_code']);
                    }
                }

                // If we find words that need to be indexed, they're added to the word table in the database
                if (is_array($product_array) && !empty($product_array)) {
                    $query_array = $query_array2 = [];
                    foreach ($product_array as $word => $weight) {
                        if ($weight) {
                            $query_array[$word] = '(' . (int) $product['id_lang'] . ', ' . (int) $product['id_shop'] . ', \'' . pSQL($word) . '\')';
                            $query_array2[] = '\'' . pSQL($word) . '\'';
                        }
                    }

                    if (is_array($query_array) && !empty($query_array)) {
                        // The words are inserted...
                        $db->execute('
						INSERT IGNORE INTO ' . _DB_PREFIX_ . 'search_word (id_lang, id_shop, word)
						VALUES ' . implode(',', $query_array), false);
                    }


                    $word_ids_by_word = [];
                    if (is_array($query_array2) && !empty($query_array2)) {
                        // ...then their IDs are retrieved
                        $added_words = $db->executeS('
						SELECT sw.id_word, sw.word
						FROM ' . _DB_PREFIX_ . 'search_word sw
						WHERE sw.word IN (' . implode(',', $query_array2) . ')
						AND sw.id_lang = ' . (int) $product['id_lang'] . '
						AND sw.id_shop = ' . (int) $product['id_shop'], true, false);
                        foreach ($added_words as $word_id) {
                            $word_ids_by_word['_' . $word_id['word']] = (int) $word_id['id_word'];
                        }
                    }
                }

                foreach ($product_array as $word => $weight) {
                    if (!$weight) {
                        continue;
                    }
                    if (!isset($word_ids_by_word['_' . $word])) {
                        continue;
                    }
                    $id_word = $word_ids_by_word['_' . $word];
                    if (!$id_word) {
                        continue;
                    }
                    $query_array3[] = '(' . (int) $product['id_product'] . ',' .
                        (int) $id_word . ',' . (int) $weight . ')';
                    // Force save every 200 words in order to avoid overloading MySQL
                    if (++$count_words % 200 == 0) {
                        Search::saveIndex($query_array3);
                    }
                }

                $products_array[] = (int) $product['id_product'];
            }

            $products_array = array_unique($products_array);
            Search::setProductsAsIndexed($products_array);

            // One last save is done at the end in order to save what's left
            Search::saveIndex($query_array3);
        }

        return true;
    }


}
