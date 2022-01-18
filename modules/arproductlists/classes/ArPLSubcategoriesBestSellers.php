<?php
/**
* 2012-2019 Areama
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@areama.net so we can send you a copy immediately.
*
*  @author    Areama <support@areama.net>
*  @copyright 2019 Areama
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of Areama
*/

include_once dirname(__FILE__).'/ArPLChildCategories.php';

class ArPLSubcategoriesBestSellers extends ArPLChildCategories
{
    public $current_category;
    public $current_category_only;
    public $full_tree;
    public $limit;
    public $loop;
    public $more_link;
    public $more_url;
    
    public $orderBy;
    public $orderWay;
    
    public function getProductList()
    {
        $id_lang = Context::getContext()->language->id;
        $id_shop = Context::getContext()->shop->id;
        
        if ($sortOrder = $this->getSortOrder()) {
            $this->orderBy = $sortOrder[0];
            $this->orderWay = $sortOrder[1];
        }
        
        $controller = Context::getContext()->controller;
        $category = null;
        if (isset($controller->php_self) && $controller->php_self == 'category') {
            $category = $controller->getCategory();
        } elseif (Tools::getValue('category_id') && ($controller instanceof ArProductListsAjaxModuleFrontController)) {
            $category = new Category(Tools::getValue('category_id'), $id_lang);
        }
        
        if (!isset($category) || empty($category)) {
            return array();
        }
        $defaultCat = $category->id;
        
                
        $childCategories = array();
        if (($this->current_category && !$this->current_category_only) || !$this->current_category) {
            if ($this->full_tree) {
                $childCategories = $this->getChildrenCategoriesRecoursive($defaultCat, $id_lang);
            } else {
                $categories = $this->getChildrenCategories($defaultCat, $id_lang);
                foreach ($categories as $category) {
                    $childCategories[] = $category['id_category'];
                }
            }
        }
        
        if ($this->current_category) {
            $childCategories[] = $defaultCat;
        }
        
        if ($this->current_category && $this->current_category_only) {
            $childCategories = array($defaultCat);
        }
        
        if (empty($childCategories)) {
            return array();
        }
        
        $join = array();
        if ($this->current_category && $this->current_category_only) {
            $conditions = array('p.id_category_default IN (' . implode(',', $childCategories) . ')');
        } else {
            $join = array('LEFT JOIN ' . _DB_PREFIX_ . 'category_product cp ON cp.id_product = p.id_product');
            $conditions = array('cp.id_category IN (' . implode(',', $childCategories) . ')');
        }
        
        $products = $this->getBestSales($id_lang, $id_shop, $this->limit, $conditions, $join, $this->orderBy == 'rand'? null : $this->orderBy, $this->orderBy == 'rand'? null : $this->orderWay);
        
        if ($products && $this->orderBy == 'rand') {
            shuffle($products);
        }
        
        return $products;
    }
    
    public function getOrderOptions()
    {
        return array(
            'orderBy' => array(
                'rand' => 'Random',
                'price' => 'Price',
                'date_add' => 'Date add',
                'sales' => 'Sales count'
            ),
            'orderWay' => array(
                'asc' => 'ASC',
                'desc' => 'DESC',
            )
        );
    }
    
    public function getDefaultSortOrder()
    {
        return $this->orderBy . ':' . $this->orderWay;
    }
    
    public function getFrontendOrderOptions()
    {
        return array(
            'price:asc' => 'Price ASC',
            'price:desc' => 'Price DESC',
            'date_add:asc' => 'Date add ASC',
            'date_add:desc' => 'Date add DESC',
            'name:asc' => 'Product name ASC',
            'name:desc' => 'Product name DESC',
            'manufacturer_name:asc' => 'Manufacturer name ASC',
            'manufacturer_name:desc' => 'Manufacturer name DESC',
            'sales:asc' => 'Sales count ASC',
            'sales:desc' => 'Sales count DESC',
        );
    }
    
    public function getMoreLink()
    {
        if ($this->more_url) {
            return strtr($this->more_url, array(
                '{lang}' => Context::getContext()->language->iso_code
            ));
        }
        return Context::getContext()->link->getPageLink('best-sales');
    }
    
    public function rules()
    {
        return array(
            'required' => array(
                'limit'
            )
        );
    }
    
    public function isProductList()
    {
        return true;
    }
    
    public function isCategoryList()
    {
        return false;
    }
    
    public static function getTypeTitle()
    {
        return 'Best sellers from subcategories';
    }
}
