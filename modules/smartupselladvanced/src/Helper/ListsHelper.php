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

namespace Invertus\SmartUpsellAdvanced\Helper;

use HelperList;
use Module;
use Tools;
use AdminController;
use Invertus\SmartUpsellAdvanced\Repository\ProductRepository;
use Invertus\SmartUpsellAdvanced\Repository\CategoryRepository;

class ListsHelper
{
    const DEFAULT_PAGINATION = 50;
    const DEFAULT_ORDER_BY = 'id_product';
    const DEFAULT_ORDER_WAY = 'ASC';

    /** @var Int */
    private $currentProductID;
    /** @var \Module */
    private $module;

    /**
     * ListsHelper constructor.
     * @param Module $module
     * @param $productID
     */
    public function __construct(Module $module, $productID)
    {
        $this->currentProductID = $productID;
        $this->module = $module;
    }

    /**
     * @return string
     */
    public function renderAvailableProductList()
    {
        $helper = new HelperList();

        $table = 'available_products';

        $bulkActions = [
            'unselect' => [
                'text' => $this->module->l('Set upsell', 'ListsHelper'),
                'icon' => 'icon-chain'
            ],
        ];

        // Order and pagination implementation
        $orderBy = Tools::getValue($table.'Orderby', self::DEFAULT_ORDER_BY);
        $orderWay = Tools::strtoupper(Tools::getValue($table.'Orderway', self::DEFAULT_ORDER_WAY));

        $page = (int)Tools::getValue('submitFilter'.$table);
        if ($page <= 1) {
            $page = 1;
        }

        $pagination = (int)Tools::getValue($table.'_pagination', self::DEFAULT_PAGINATION);
        $start = $pagination * $page - $pagination;
        if ($start <= 0) {
            $start = 0;
        }

        $helper->page = $page;
        $helper->_default_pagination = (int)$pagination;
        $helper->orderBy = $orderBy;
        $helper->orderWay = $orderWay;

        $helper->bulk_actions = $bulkActions;
        $helper->no_link = true;
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->actions = ['set'];
        $helper->show_toolbar = true;
        $helper->imageType = 'jpg';
        $helper->identifier = 'id_product';
        $helper->title = $this->module->l('Available products');
        $helper->table = $table;
        $helper->token = Tools::getAdminTokenLite('AdminSmartUpsellAdvancedProductDetails');
        $helper->currentIndex = AdminController::$currentIndex;

        $fields_list = $this->getAvailableProductsListFields();
        $content = ProductRepository::getAvailableProductsListContent(
            $this->currentProductID,
            $orderBy,
            $orderWay,
            $pagination,
            $start
        );

        $helper->listTotal = count($content);

        return $helper->generateList($content, $fields_list);
    }

    /**
     * @return string
     */
    public function renderUpsellProductList()
    {
        $helper = new HelperList();

        $table = 'upsell_products';



        $bulkActions = [
            'unselect' => [
                'text' => $this->module->l('Unset upsell', 'ListsHelper'),
                'icon' => 'icon-chain-broken'
            ],
        ];

        // Order and pagination implementation
        $orderBy = Tools::getValue($table.'Orderby', self::DEFAULT_ORDER_BY);
        $orderWay = Tools::strtoupper(Tools::getValue($table.'Orderway', self::DEFAULT_ORDER_WAY));

        $page = (int)Tools::getValue('submitFilter'.$table);
        if ($page <= 1) {
            $page = 1;
        }

        $pagination = (int)Tools::getValue($table.'_pagination', self::DEFAULT_PAGINATION);
        $start = $pagination * $page - $pagination;
        if ($start <= 0) {
            $start = 0;
        }

        $helper->page = $page;
        $helper->_default_pagination = (int)$pagination;
        $helper->orderBy = $orderBy;
        $helper->orderWay = $orderWay;
        // End of order and pagination implementation

        $helper->bulk_actions = $bulkActions;
        $helper->no_link = true;
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->actions = ['unset'];
        $helper->show_toolbar = true;
        $helper->imageType = 'jpg';
        $helper->identifier = 'id_product';
        $helper->title = $this->module->l('Upsell products');
        $helper->table = $table;
        $helper->token = Tools::getAdminTokenLite('AdminSmartUpsellAdvancedProductDetails');
        $helper->currentIndex = AdminController::$currentIndex;

        $fields_list = $this->getUpsellProductsListFields();
        $content = ProductRepository::getUpsellProductsListContent(
            $this->currentProductID,
            $orderBy,
            $orderWay,
            $pagination,
            $start
        );

        $helper->listTotal = count($content);

        return $helper->generateList($content, $fields_list);
    }

    /**
     * @return array
     */
    private function getAvailableProductsListFields()
    {
        $fields = [];

        $fields['id_product'] = [
            'title' => $this->module->l('ID', 'ListsHelper'),
            'width' => 20,
            'type' => 'text',
            'align' => 'center',
        ];

        $fields['id_image'] = [
            'title' => $this->module->l('Image', 'ListsHelper'),
            'align' => 'center',
            'image' => 'p',
            'orderby' => false,
            'filter' => false,
            'search' => false
        ];

        $fields['product_name'] = [
            'title' => $this->module->l('Name', 'ListsHelper'),
            'width' => 'auto',
            'type' => 'text',
            'havingFilter' => true,
        ];

        $fields['category_name'] = [
            'title' => $this->module->l('Category', 'ListsHelper'),
            'type' => 'select',
            'list' => CategoryRepository::getAllCategoriesForList(),
            'filter_key' => 'category_name',
            'havingFilter' => true,
        ];

        $fields['product_quantity'] = [
            'title' => $this->module->l('Quantity', 'ListsHelper'),
            'type' => 'text',
            'align' => 'center',
            'class' => 'fixed-width-xs',
            'havingFilter' => true,
        ];

        $fields['price'] = [
            'title' => $this->module->l('Price', 'ListsHelper'),
            'type' => 'text',
            'align' => 'center',
            'class' => 'fixed-width-xs',
            'havingFilter' => true,
        ];

        return $fields;
    }

    /**
     * @return array
     */
    private function getUpsellProductsListFields()
    {
        $fields = [];

        $fields['id_product'] = [
            'title' => $this->module->l('ID', 'ListsHelper'),
            'width' => 20,
            'type' => 'text',
            'align' => 'center',
        ];

        $fields['id_image'] = [
            'title' => $this->module->l('Image', 'ListsHelper'),
            'align' => 'center',
            'image' => 'p',
            'orderby' => false,
            'filter' => false,
            'search' => false
        ];

        $fields['product_name'] = [
            'title' => $this->module->l('Name', 'ListsHelper'),
            'width' => 'auto',
            'type' => 'text',
            'havingFilter' => true,
        ];

        $fields['product_quantity'] = [
            'title' => $this->module->l('Quantity', 'ListsHelper'),
            'type' => 'text',
            'align' => 'center',
            'class' => 'fixed-width-xs',
            'havingFilter' => true,
        ];

        $fields['product_active'] = [
            'title' => $this->module->l('Status', 'ListsHelper'),
            'align' => 'text-center',
            'callback' => 'displayStatusField',
            'type' => 'select',
            'list' => [
                1 => $this->module->l('Yes', 'ListsHelper'),
                0 => $this->module->l('No', 'ListsHelper'),
            ],
            'filter_key' => 'a!active',
        ];

        $fields['price'] = [
            'title' => $this->module->l('Product price', 'ListsHelper'),
            'type' => 'text',
            'align' => 'center',
            'class' => 'fixed-width-xs',
            'havingFilter' => true,
        ];

        return $fields;
    }
}
