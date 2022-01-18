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

include_once dirname(__FILE__).'/ArPLListAbstract.php';

class ArPLRelatedCategories extends ArPLListAbstract
{
    public $limit;
    public $grid;
    public $grid_md;
    public $grid_sm;
    public $thumb_size;
    public $cat_title;
    public $cat_desc;
    
    public function getCategoriesList()
    {
        $id_lang = Context::getContext()->language->id;
        $id_shop = Context::getContext()->shop->id;
        
        $defaultCat = $this->product->id_category_default;
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
    
    public function rules()
    {
        return array(
            'required' => array(
                'thumb_size'
            )
        );
    }
    
    public function getProductList()
    {
        return array();
    }
    
    public function isProductList()
    {
        return false;
    }
    
    public function isCategoryList()
    {
        return true;
    }
    
    public function isBrandList()
    {
        return false;
    }
    
    public static function getTypeTitle()
    {
        return 'Related categories';
    }
}
