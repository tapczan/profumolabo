<?php
/**
 * NOTICE OF LICENSE
 *
 * @author    INVERTUS, UAB www.invertus.eu <support@invertus.eu>
 * @copyright Copyright (c) permanent, INVERTUS, UAB
 * @license   Addons PrestaShop license limitation
 * @see       /LICENSE
 *
 * International Registered Trademark & Property of INVERTUS, UAB
 */

use Invertus\SmartUpsellAdvanced\Repository\CategoryRepository;
use Invertus\SmartUpsellAdvanced\Controller\AdminSmartUpsellAbstractController;

class AdminSmartUpsellAdvancedProductPageController extends AdminSmartUpsellAbstractController
{
    /**
     * AdminSmartUpsellAdvancedProductPageController constructor.
     */
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'product';
        $this->className = 'Product';
        $this->identifier = 'id_product';

        parent::__construct();

        $this->content = $this->module->getFeedbackMessage();
        $this->initList();
    }

    /**
     * Display product status in related products list
     *
     * @param $status
     * @return string
     */
    public function displayStatusField($status)
    {
        return $this->context->smarty->fetch($this->module->getLocalPath().'/views/templates/admin/status_field.tpl', [
            'status' => (bool) $status
        ]);
    }

    /**
     * Remove "Add new" from toolbar
     *
     * @return void
     */
    public function initToolbar()
    {
        parent::initToolbar();
        unset($this->toolbar_btn['new']);
    }

    /**
     * Initialise list
     */
    private function initList()
    {

        $this->list_no_link = true;
        $this->addRowAction('select');

        $this->fields_list['id_product'] = [
            'title' => $this->l('ID'),
            'width' => 20,
            'type' => 'text',
            'align' => 'center',
        ];

        $this->fields_list['id_image'] = [
            'title' => $this->l('Image'),
            'align' => 'center',
            'image' => 'p',
            'orderby' => false,
            'filter' => false,
            'search' => false
        ];

        $this->fields_list['product_name'] = [
            'title' => $this->l('Name'),
            'width' => 'auto',
            'type' => 'text',
            'havingFilter' => true,
        ];

        $this->fields_list['category_name'] = [
            'title' => $this->l('Category'),
            'type' => 'select',
            'list' => CategoryRepository::getAllCategoriesForList(),
            'filter_key' => 'category_name',
            'havingFilter' => true,
        ];

        $this->fields_list['product_quantity'] = [
            'title' => $this->l('Quantity'),
            'type' => 'text',
            'align' => 'center',
            'class' => 'fixed-width-xs',
            'havingFilter' => true,
        ];


        $this->fields_list['price'] = [
            'title' => $this->l('Price'),
            'type' => 'text',
            'align' => 'center',
            'class' => 'fixed-width-xs',
            'havingFilter' => true,
            'prefix' => $this->context->currency->sign
        ];

        $this->fields_list['product_active'] = [
            'title' => $this->l('Active'),
            'align' => 'text-center',
            'callback' => 'displayStatusField',
            'type' => 'select',
            'list' => [
                1 => $this->l('Yes'),
                0 => $this->l('No'),
            ],
            'filter_key' => 'a!active',
        ];

        $this->_select = 'stock_available.`quantity` as `product_quantity`, pl.`name` as `product_name`, ';
        $this->_select .= 'cl.`name` as `category_name`, ims.`id_image`, a.`active` as `product_active`, ';
        $this->_select .= ' ROUND (a.`price`, 2) as `price`, a.`id_product`';


        $this->_join = '
            LEFT JOIN `'._DB_PREFIX_.'stock_available` stock_available
                ON (stock_available.`id_product` = a.`id_product`
                    AND stock_available.`id_product_attribute` = 0
                    AND stock_available.`id_shop` = '.(int)$this->context->shop->id.')';

        $this->_join .= '
            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
                ON (pl.`id_product` = a.`id_product`
                    AND pl.`id_lang` = '.(int)$this->context->language->id.'
                    AND pl.`id_shop` = '.(int)$this->context->shop->id.')
        ';

        $this->_join .= '
             LEFT JOIN `'._DB_PREFIX_.'category_lang` cl
                ON (cl.`id_category` = a.`id_category_default`
                    AND cl.`id_lang` = '.(int)$this->context->shop->id.'
                    AND cl.`id_shop` = '.(int)$this->context->shop->id.')
        ';

        $this->_join .= '
            LEFT JOIN `'._DB_PREFIX_.'image` i
                ON (i.`id_product` = a.`id_product`)
            INNER JOIN `'._DB_PREFIX_.'image_shop` ims
                ON (ims.`id_image` = i.`id_image`
                    AND ims.`id_shop` = '.(int)$this->context->shop->id.'
                    AND ims.`cover` = 1)
        ';

        $this->_group = 'GROUP BY a.`id_product`';
    }

    /**
     * Add custom css
     *
     * @param bool $isNewTheme
     */
    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        $this->addCSS(_MODULE_DIR_.'smartupselladvanced/views/css/bo-cart.css');
    }

    /**
     * Set smarty template to display select button
     *
     * @param $token
     * @param $productId
     * @return string
     */
    public function displaySelectLink($token, $productId)
    {
        unset($token);
        $relatedProductUrl = $this->context->link->getAdminLink('AdminSmartUpsellAdvancedProductDetails');
        $relatedProductUrl .= '&id_product='.(int)$productId;

        return $this->context->smarty->fetch($this->module->getLocalPath().'/views/templates/admin/select_button.tpl', [
            'related_product_url' => $relatedProductUrl
        ]);
    }
}
