<?php
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;

class Product extends ProductCore
{
    /**
     * @param int $id_lang Language identifier
     * @param array $row
     * @param Context|null $context
     *
     * @return array|false
     */
    public static function getProductProperties($id_lang, $row, Context $context = null)
    {
        Hook::exec('actionGetProductPropertiesBefore', [
            'id_lang' => $id_lang,
            'product' => &$row,
            'context' => $context,
        ]);

        if (!$row['id_product']) {
            return false;
        }

        if ($context == null) {
            $context = Context::getContext();
        }

        $id_product_attribute = $row['id_product_attribute'] = (!empty($row['id_product_attribute']) ? (int) $row['id_product_attribute'] : null);

        // Product::getDefaultAttribute is only called if id_product_attribute is missing from the SQL query at the origin of it:
        // consider adding it in order to avoid unnecessary queries
        $row['allow_oosp'] = Product::isAvailableWhenOutOfStock($row['out_of_stock']);
        if (Combination::isFeatureActive() && $id_product_attribute === null
            && ((isset($row['cache_default_attribute']) && ($ipa_default = $row['cache_default_attribute']) !== null)
                || ($ipa_default = Product::getDefaultAttribute($row['id_product'], !$row['allow_oosp'])))) {
            $id_product_attribute = $row['id_product_attribute'] = $ipa_default;
        }
        if (!Combination::isFeatureActive() || !isset($row['id_product_attribute'])) {
            $id_product_attribute = $row['id_product_attribute'] = 0;
        }

        // Tax
        $usetax = !Tax::excludeTaxeOption();

        $cache_key = $row['id_product'] . '-' . $id_product_attribute . '-' . $id_lang . '-' . (int) $usetax;
        if (isset($row['id_product_pack'])) {
            $cache_key .= '-pack' . $row['id_product_pack'];
        }

        if (!isset($row['cover_image_id'])) {
            $cover = static::getCover($row['id_product']);
            if (isset($cover['id_image'])) {
                $row['cover_image_id'] = $cover['id_image'];
            }
        }

        if (isset($row['cover_image_id'])) {
            $cache_key .= '-cover' . (int) $row['cover_image_id'];
        }

        if (isset(self::$productPropertiesCache[$cache_key])) {
            return array_merge($row, self::$productPropertiesCache[$cache_key]);
        }

        // Datas
        $row['category'] = Category::getLinkRewrite((int) $row['id_category_default'], (int) $id_lang);
        $row['category_name'] = Db::getInstance()->getValue('SELECT name FROM ' . _DB_PREFIX_ . 'category_lang WHERE id_shop = ' . (int) $context->shop->id . ' AND id_lang = ' . (int) $id_lang . ' AND id_category = ' . (int) $row['id_category_default']);
        $row['link'] = $context->link->getProductLink((int) $row['id_product'], $row['link_rewrite'], $row['category'], $row['ean13']);

        $row['attribute_price'] = 0;
        if ($id_product_attribute) {
            $row['attribute_price'] = (float) Combination::getPrice($id_product_attribute);
        }

        if (isset($row['quantity_wanted'])) {
            // 'quantity_wanted' may very well be zero even if set
            $quantity = max((int) $row['minimal_quantity'], (int) $row['quantity_wanted']);
        } elseif (isset($row['cart_quantity'])) {
            $quantity = max((int) $row['minimal_quantity'], (int) $row['cart_quantity']);
        } else {
            $quantity = (int) $row['minimal_quantity'];
        }

        $row['price_tax_exc'] = Product::getPriceStatic(
            (int) $row['id_product'],
            false,
            $id_product_attribute,
            (self::$_taxCalculationMethod == PS_TAX_EXC ? Context::getContext()->getComputingPrecision() : 6),
            null,
            false,
            true,
            $quantity
        );

        if (self::$_taxCalculationMethod == PS_TAX_EXC) {
            $row['price_tax_exc'] = Tools::ps_round($row['price_tax_exc'], Context::getContext()->getComputingPrecision());
            $row['price'] = Product::getPriceStatic(
                (int) $row['id_product'],
                true,
                $id_product_attribute,
                6,
                null,
                false,
                true,
                $quantity
            );
            $row['price_without_reduction'] =
            $row['price_without_reduction_without_tax'] = Product::getPriceStatic(
                (int) $row['id_product'],
                false,
                $id_product_attribute,
                2,
                null,
                false,
                false,
                $quantity
            );
        } else {
            $row['price'] = Tools::ps_round(
                Product::getPriceStatic(
                    (int) $row['id_product'],
                    true,
                    $id_product_attribute,
                    6,
                    null,
                    false,
                    true,
                    $quantity
                ),
                Context::getContext()->getComputingPrecision()
            );
            $row['price_without_reduction'] = Product::getPriceStatic(
                (int) $row['id_product'],
                true,
                $id_product_attribute,
                6,
                null,
                false,
                false,
                $quantity
            );
            $row['price_without_reduction_without_tax'] = Product::getPriceStatic(
                (int) $row['id_product'],
                false,
                $id_product_attribute,
                6,
                null,
                false,
                false,
                $quantity
            );
        }

        $row['reduction'] = Product::getPriceStatic(
            (int) $row['id_product'],
            (bool) $usetax,
            $id_product_attribute,
            6,
            null,
            true,
            true,
            $quantity,
            true,
            null,
            null,
            null,
            $specific_prices
        );

        $row['reduction_without_tax'] = Product::getPriceStatic(
            (int) $row['id_product'],
            false,
            $id_product_attribute,
            6,
            null,
            true,
            true,
            $quantity,
            true,
            null,
            null,
            null,
            $specific_prices
        );

        $row['specific_prices'] = $specific_prices;

        $row['quantity'] = Product::getQuantity(
            (int) $row['id_product'],
            0,
            isset($row['cache_is_pack']) ? $row['cache_is_pack'] : null,
            $context->cart
        );

        $row['quantity_all_versions'] = $row['quantity'];

        if ($row['id_product_attribute']) {
            $row['quantity'] = Product::getQuantity(
                (int) $row['id_product'],
                $id_product_attribute,
                isset($row['cache_is_pack']) ? $row['cache_is_pack'] : null,
                $context->cart
            );

            $row['available_date'] = Product::getAvailableDate(
                (int) $row['id_product'],
                $id_product_attribute
            );
        }

        $row['id_image'] = Product::defineProductImage($row, $id_lang);
        $row['features'] = Product::getFrontFeaturesStatic((int) $id_lang, $row['id_product']);

        $row['attachments'] = [];
        if (!isset($row['cache_has_attachments']) || $row['cache_has_attachments']) {
            $row['attachments'] = Product::getAttachmentsStatic((int) $id_lang, $row['id_product']);
        }

        $row['virtual'] = ((!isset($row['is_virtual']) || $row['is_virtual']) ? 1 : 0);

        // Pack management
        $row['pack'] = (!isset($row['cache_is_pack']) ? Pack::isPack($row['id_product']) : (int) $row['cache_is_pack']);
        $row['packItems'] = $row['pack'] ? Pack::getItemTable($row['id_product'], $id_lang) : [];
        $row['nopackprice'] = $row['pack'] ? Pack::noPackPrice($row['id_product']) : 0;

        if ($row['pack'] && !Pack::isInStock($row['id_product'], $quantity, $context->cart)) {
            $row['quantity'] = 0;
        }

        $row['customization_required'] = false;
        if (isset($row['customizable']) && $row['customizable'] && Customization::isFeatureActive()) {
            if (count(Product::getRequiredCustomizableFieldsStatic((int) $row['id_product']))) {
                $row['customization_required'] = true;
            }
        }

        if (!isset($row['attributes'])) {
            $attributes = Product::getAttributesParams($row['id_product'], $row['id_product_attribute']);

            $group = null;

            foreach ($attributes as $attribute) {
                $group = $attribute['id_attribute_group'];
                $row['attributes'][$attribute['id_attribute_group']] = $attribute;
            }

            if (!isset($row['attribute_combinations'])) {
                $attributesCombinations = Product::getProductAttributeCombinationByGroup($id_lang, $row['id_product'], $group);

                $priceFormatter = new PriceFormatter();
                foreach ($attributesCombinations as $combination) {
                    $discount = intval($row['reduction']) ? true : false;

                    if($discount) {
                        $combination['discount_price'] = $priceFormatter->format(
                            Product::getPriceStatic(
                                (int) $row['id_product'],
                                (bool) $usetax,
                                $combination['id_product_attribute'],
                                6,
                                null,
                                false,
                                true)
                        );
                    }

                    $combination['price'] = $priceFormatter->format(
                        Product::getPriceStatic(
                            (int) $row['id_product'],
                            (bool) $usetax ,
                            $combination['id_product_attribute'],
                            6,
                            null,
                            false,
                            !$discount)
                    );
                    $row['attribute_combinations'][] = $combination;
                }
            }
        }

        $row = Product::getTaxesInformations($row, $context);

        $row['ecotax_rate'] = (float) Tax::getProductEcotaxRate($context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')});

        Hook::exec('actionGetProductPropertiesAfter', [
            'id_lang' => $id_lang,
            'product' => &$row,
            'context' => $context,
        ]);

        $combination = new Combination($id_product_attribute);

        if (0 != $combination->unit_price_impact && 0 != $row['unit_price_ratio']) {
            $unitPrice = ($row['price_tax_exc'] / $row['unit_price_ratio']) + $combination->unit_price_impact;
            $row['unit_price_ratio'] = $row['price_tax_exc'] / $unitPrice;
        }

        if (isset($row['unit_price_ratio'])) {
            $row['unit_price'] = ($row['unit_price_ratio'] != 0 ? $row['price'] / $row['unit_price_ratio'] : 0);
        } else {
            $row['unit_price'] = 0.0;
        }

        Hook::exec('actionGetProductPropertiesAfterUnitPrice', [
            'id_lang' => $id_lang,
            'product' => &$row,
            'context' => $context,
        ]);

        self::$productPropertiesCache[$cache_key] = $row;

        return self::$productPropertiesCache[$cache_key];
    }

    /**
     * @param int $id_lang
     * @param int $page_number
     * @param int $nb_products
     * @param false $count
     * @param null $order_by
     * @param null $order_way
     * @param false $beginning
     * @param false $ending
     * @param Context|null $context
     * @return array|false|string|void
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public static function getPricesDrop(
        $id_lang,
        $page_number = 0,
        $nb_products = 10,
        $count = false,
        $order_by = null,
        $order_way = null,
        $beginning = false,
        $ending = false,
        Context $context = null
    ) {
        if (!Validate::isBool($count)) {
            die(Tools::displayError());
        }

        if (!$context) {
            $context = Context::getContext();
        }
        if ($page_number < 1) {
            $page_number = 1;
        }
        if ($nb_products < 1) {
            $nb_products = 10;
        }
        if (empty($order_by) || $order_by == 'position') {
            $order_by = 'price';
        }
        if (empty($order_way)) {
            $order_way = 'DESC';
        }
        if ($order_by == 'id_product' || $order_by == 'price' || $order_by == 'date_add' || $order_by == 'date_upd') {
            $order_by_prefix = 'product_shop';
        } elseif ($order_by == 'name') {
            $order_by_prefix = 'pl';
        }
        if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way)) {
            die(Tools::displayError());
        }
        $current_date = date('Y-m-d H:i:00');
        $ids_product = Product::_getProductIdByDate((!$beginning ? $current_date : $beginning), (!$ending ? $current_date : $ending), $context);

        $tab_id_product = [];
        foreach ($ids_product as $product) {
            if (is_array($product)) {
                $tab_id_product[] = (int) $product['id_product'];
            } else {
                $tab_id_product[] = (int) $product;
            }
        }

        $front = true;
        if (!in_array($context->controller->controller_type, ['front', 'modulefront'])) {
            $front = false;
        }

        $sql_groups = '';
        if (Group::isFeatureActive()) {
            $groups = FrontController::getCurrentCustomerGroups();
            $sql_groups = ' AND EXISTS(SELECT 1 FROM `' . _DB_PREFIX_ . 'category_product` cp
            JOIN `' . _DB_PREFIX_ . 'category_group` cg ON (cp.id_category = cg.id_category AND cg.`id_group` ' . (count($groups) ? 'IN (' . implode(',', $groups) . ')' : '=' . (int) Group::getCurrent()->id) . ')
            WHERE cp.`id_product` = p.`id_product`)';
        }


        $excludedProductsFromCatQuery = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT id_product FROM ' . _DB_PREFIX_ . 'category_product where id_category = 140;
        ');

        $excludedProductsId = [];

        if($excludedProductsFromCatQuery) {
            foreach ($excludedProductsFromCatQuery as $category) {
                $excludedProductsId[] = $category['id_product'];
            }
        }

        $excludedProductsString = implode(',', $excludedProductsId);
        $excludedProductsQuery = '';

        if(!empty($excludedProductsString)){
            $excludedProductsQuery = 'AND p.`id_product` NOT IN ('.$excludedProductsString.')';
        }

        if ($count) {
            return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
            SELECT COUNT(DISTINCT p.`id_product`)
            FROM `' . _DB_PREFIX_ . 'product` p
            ' . Shop::addSqlAssociation('product', 'p') . '
            WHERE product_shop.`active` = 1
            AND product_shop.`show_price` = 1
            ' . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '') . '
            ' . ((!$beginning && !$ending) ? 'AND p.`id_product` IN(' . ((is_array($tab_id_product) && count($tab_id_product)) ? implode(', ', $tab_id_product) : 0) . ')' : '') . '
            ' . $sql_groups);
        }

        if (strpos($order_by, '.') > 0) {
            $order_by = explode('.', $order_by);
            $order_by = pSQL($order_by[0]) . '.`' . pSQL($order_by[1]) . '`';
        }

        $sql = '
        SELECT
            p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`description`, pl.`description_short`, pl.`available_now`, pl.`available_later`,
            IFNULL(product_attribute_shop.id_product_attribute, 0) id_product_attribute,
            pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`,
            pl.`name`, image_shop.`id_image` id_image, il.`legend`, m.`name` AS manufacturer_name,
            DATEDIFF(
                p.`date_add`,
                DATE_SUB(
                    "' . date('Y-m-d') . ' 00:00:00",
                    INTERVAL ' . (Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20) . ' DAY
                )
            ) > 0 AS new
        FROM `' . _DB_PREFIX_ . 'product` p
        ' . Shop::addSqlAssociation('product', 'p') . '
        LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` product_attribute_shop
            ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop=' . (int) $context->shop->id . ')
        ' . Product::sqlStock('p', 0, false, $context->shop) . '
        LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (
            p.`id_product` = pl.`id_product`
            AND pl.`id_lang` = ' . (int) $id_lang . Shop::addSqlRestrictionOnLang('pl') . '
        )
        LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop
            ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop=' . (int) $context->shop->id . ')
        LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int) $id_lang . ')
        LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
        WHERE product_shop.`active` = 1
        AND product_shop.`show_price` = 1
        ' . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '') . '
        ' . ((!$beginning && !$ending) ? ' AND p.`id_product` IN (' . ((is_array($tab_id_product) && count($tab_id_product)) ? implode(', ', $tab_id_product) : 0) . ')' : '') . '
        ' . $sql_groups . '
        ' . $excludedProductsQuery;

        if ($order_by != 'price') {
            $sql .= '
				ORDER BY ' . (isset($order_by_prefix) ? pSQL($order_by_prefix) . '.' : '') . pSQL($order_by) . ' ' . pSQL($order_way) . '
				LIMIT ' . (int) (($page_number - 1) * $nb_products) . ', ' . (int) $nb_products;
        }

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);


        if (!$result) {
            return false;
        }

        if ($order_by === 'price') {
            Tools::orderbyPrice($result, $order_way);
            $result = array_slice($result, (int) (($page_number - 1) * $nb_products), (int) $nb_products);
        }

        return Product::getProductsProperties($id_lang, $result);
    }

    public function getProductAttributeCombinationByGroup($id_lang, $id_product, $attr_group)
    {
        if (!Combination::isFeatureActive()) {
            return [];
        }
        $sql = 'SELECT ag.`id_attribute_group`, ag.`is_color_group`, agl.`name` AS group_name, agl.`public_name` AS public_group_name,
                    a.`id_attribute`, al.`name` AS attribute_name, a.`color` AS attribute_color, product_attribute_shop.`id_product_attribute`,
                    IFNULL(stock.quantity, 0) as quantity, product_attribute_shop.`price`, product_attribute_shop.`ecotax`, product_attribute_shop.`weight`,
                    product_attribute_shop.`default_on`, pa.`reference`, pa.`ean13`, pa.`mpn`, pa.`upc`, pa.`isbn`, product_attribute_shop.`unit_price_impact`,
                    product_attribute_shop.`minimal_quantity`, product_attribute_shop.`available_date`, ag.`group_type`
                FROM `' . _DB_PREFIX_ . 'product_attribute` pa
                ' . Shop::addSqlAssociation('product_attribute', 'pa') . '
                ' . Product::sqlStock('pa', 'pa') . '
                LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_combination` pac ON (pac.`id_product_attribute` = pa.`id_product_attribute`)
                LEFT JOIN `' . _DB_PREFIX_ . 'attribute` a ON (a.`id_attribute` = pac.`id_attribute`)
                LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group` ag ON (ag.`id_attribute_group` = a.`id_attribute_group`)
                LEFT JOIN `' . _DB_PREFIX_ . 'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute`)
                LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group`)
                ' . Shop::addSqlAssociation('attribute', 'a') . '
                WHERE pa.`id_product` = ' . (int) $id_product . '
                    AND a.`id_attribute_group` = '. (int) $attr_group.'
                    AND al.`id_lang` = ' . (int) $id_lang . '
                    AND agl.`id_lang` = ' . (int) $id_lang . '
                ';

        $sql .= 'GROUP BY id_attribute_group, id_product_attribute
                ORDER BY pa.`default_on` DESC, ag.`position` ASC, a.`position` ASC, agl.`name` ASC';

        return Db::getInstance()->executeS($sql);

    }

    /*
     * for smartupsell
     */
    public static function getSpecificProductByID($id_product) {

        $query = 'SELECT *
            FROM `' . _DB_PREFIX_ . 'product`
            WHERE `id_product` = ' . (int) $id_product;  
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
    }

    /*
     * for smartupsell
     */
    public static function getProductBrandByID($id_manufacturer) {

        $query = 'SELECT *
            FROM `' . _DB_PREFIX_ . 'manufacturer`
            WHERE `id_manufacturer` = ' . (int) $id_manufacturer;  
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
    }

    /*
     *  for smartupsell
     */
    public static function getProductRatingByID($id_product) {
        $total = 0;
        $result = 0;
        
        $products = Db::getInstance()->executeS('SELECT `grade`
            FROM `' . _DB_PREFIX_ . 'product_comment`
            WHERE `id_product` = ' . (int) $id_product);

        if(!empty($products)) {

            foreach($products as $product) {
                $total += (int) $product['grade'];
            }
            
            $result = round($total / count($products));
    
        }
        
        return $result;
    }
   
    /*
     * for smartupsell
     */
    public static function getProductCombinationByID($id_product) {
        global $cookie;
        $id_lang = $cookie->id_lang;

        $base_price = 0;
        $result = [];

        $productAttributes = Db::getInstance()->executeS('SELECT *
        FROM `' . _DB_PREFIX_ . 'product_attribute`
        WHERE `id_product` = ' . $id_product);
        
        $productPrice = Db::getInstance()->executeS('SELECT `price`
            FROM `' . _DB_PREFIX_ . 'product`
            WHERE `id_product` = ' . $id_product);
 
        if(!empty($productPrice)) {
            $base_price = $productPrice[0]['price'];
        }
        
        if(!empty($productAttributes)) {
            foreach($productAttributes as $product_attribute) {
                $packaging = self::getProductAttributeCombination($product_attribute['id_product_attribute'], $id_lang);
                $result[] = $base_price + $product_attribute['price'] .' / '.$packaging;
            }
        }
        return array_reverse($result, true);
    }

     /*
     * for smartupsell
     */
    public static function getProductAttributeCombination($id_product_attribute, $id_lang) {

        $productAttributeCombinations = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT *
        FROM `' . _DB_PREFIX_ . 'product_attribute_combination`
        WHERE `id_product_attribute` = ' . (int) $id_product_attribute);

        $result = [];

        if(!empty($productAttributeCombinations)) {
            foreach($productAttributeCombinations as $product_attribute_combination) {
          
                $result = Db::getInstance()->executeS('SELECT `name`
                    FROM `' . _DB_PREFIX_ . 'attribute_lang`
                    WHERE `id_attribute` = ' . $product_attribute_combination['id_attribute'] .'
                    AND `id_lang` =  ' . $id_lang );
            }
        }
        
        return $result[0]['name'];
    }
}