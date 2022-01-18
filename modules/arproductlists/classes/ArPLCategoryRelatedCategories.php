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

include_once dirname(__FILE__).'/ArPLRelatedCategories.php';

class ArPLCategoryRelatedCategories extends ArPLRelatedCategories
{
    public function getCategoriesList()
    {
        $id_lang = Context::getContext()->language->id;
        $id_shop = Context::getContext()->shop->id;
        
        $controller = Context::getContext()->controller;
        
        if (isset($controller->php_self) && $controller->php_self == 'category') {
            $category = $controller->getCategory();
        } else {
            return array();
        }
        $defaultCat = $category->id;
        
        $relatedCats = ArProductListRelCat::getRelatedCategoriesStatic($defaultCat);
        if (empty($relatedCats)) {
            return array();
        }
        
        $res = array();
        foreach ($relatedCats as $id) {
            $res[] = new Category($id, $id_lang, $id_shop);
        }
        return $res;
    }
}
