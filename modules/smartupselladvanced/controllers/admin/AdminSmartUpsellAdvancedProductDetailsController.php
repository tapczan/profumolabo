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

use Symfony\Component\HttpFoundation\Request;
use Invertus\SmartUpsellAdvanced\Repository\ProductRepository;
use Invertus\SmartUpsellAdvanced\Helper\ListsHelper;
use Invertus\SmartUpsellAdvanced\AdminBusinessLogicProvider\ProductBySearchProvider;
use Invertus\SmartUpsellAdvanced\Controller\AdminSmartUpsellAbstractController;

class AdminSmartUpsellAdvancedProductDetailsController extends AdminSmartUpsellAbstractController
{
    /** @var Product currently selected product */
    private $currentProduct;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var ListsHelper
     */
    private $listsHelper;

    /**
     * AdminSmartUpsellAdvancedProductDetailsController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->content = $this->module->getFeedbackMessage();

        $this->bootstrap = true;
        $this->table = 'product';
        $this->className = 'Product';
        $this->list_id = 'AllProducts';
        $this->identifier = 'id_product';
        $this->request = Request::createFromGlobals();
        $this->setCurrentProduct();
        $this->listsHelper = new ListsHelper($this->module, $this->currentProduct->id);
    }

    /**
     * Process bulk buttons
     *
     * @return bool|ObjectModel
     */
    public function postProcess()
    {

        $idselectedProduct = $this->currentProduct->id;
        $idUpsellProduct = Tools::getValue('upsell_product_id');
        $buttonClass = Tools::getValue('button_class');
        $isBulkSetUpellPressed = $this->request->query->has('submitBulkunselectavailable_products');
        $isBulkUnsetUpellPressed = $this->request->query->has('submitBulkunselectupsell_products');

        // Submits upsell relation to the database
        if ($buttonClass === 'js-set-upsell-btn') {
            if (ProductRepository::insertUpsellRelation($idselectedProduct, $idUpsellProduct)) {
                $this->confirmations[] = $this->l('The upsell has been successfully added');
            } else {
                $this->errors[] = $this->l('Something went wrong');
            }
        }
        if ($buttonClass === 'js-unset-upsell-btn') {
            if (ProductRepository::deleteUpsellRelation($idselectedProduct, $idUpsellProduct)) {
                $this->confirmations[] = $this->l('The upsell has been successfully removed');
            } else {
                $this->errors[] = $this->l('Something went wrong');
            }
        }

        // Submits bulk upsells
        if ($isBulkSetUpellPressed) {
            $this->processSetUpsellBulk($idselectedProduct);
        }
        if ($isBulkUnsetUpellPressed) {
            $this->processUnsetUpsellBulk($idselectedProduct);
        }

        // Returns products in database by query
        $query = Tools::getValue('query');
        if ($query != false) {
            $this->processAJAXProductSearch($query);
        }
        return parent::postProcess();
    }

    /**
     * Initialise templates
     */
    public function initContent()
    {
        $this->content .= $this->renderSelectedProduct();
        $this->content .= $this->listsHelper->renderAvailableProductList();
        $this->content .= $this->context->smarty->fetch(
            $this->module->getLocalPath() . '/views/templates/admin/related_lists_middle.tpl'
        );
        $this->content .= $this->listsHelper->renderUpsellProductList();
        $this->content .= $this->context->smarty->fetch(
            $this->module->getLocalPath() . '/views/templates/admin/product_details_end.tpl'
        );
        parent::initContent();
    }

    /**
     * Add custom JavaScript files
     *
     * @param bool $isNewTheme
     */
    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        $this->addJS(_MODULE_DIR_ . 'smartupselladvanced/views/js/bo_product_search.js');
        $this->addJS(_MODULE_DIR_ . 'smartupselladvanced/views/js/bo_submit_upsell_form.js');
    }

    /**
     * Overriden Prestashop function to create Set upsell button
     *
     */
    public function displaySetLink($token, $productId)
    {
        //unset($token);
        $buttonName = $this->l('Set upsell');
        $buttonClass = 'set-upsell';
        return $this->context->smarty->fetch(
            $this->module->getLocalPath() . '/views/templates/admin/set_upsell_button.tpl',
            [
                'selected_product_id' => $this->currentProduct->id,
                'upsell_product_id' => $productId,
                'button_name' => $buttonName,
                'button_class' => $buttonClass,
            ]
        );
    }


    /**
     * Overriden Prestashop function to create Unset upsell button
     */
    public function displayUnsetLink($token, $productId)
    {
        //unset($token);
        $buttonName = $this->l('Unset upsell');
        $buttonClass = 'unset-upsell';
        return $this->context->smarty->fetch(
            $this->module->getLocalPath() . '/views/templates/admin/unset_upsell_button.tpl',
            [
                'selected_product_id' => $this->currentProduct->id,
                'upsell_product_id' => $productId,
                'button_name' => $buttonName,
                'button_class' => $buttonClass,
            ]
        );
    }

    /**
     * Returns String containing selected product HTML
     *
     * @return String
     */
    private function renderSelectedProduct()
    {
        $product = (array)$this->currentProduct;
        $image = Product::getCover($this->currentProduct->id);

        if ($image) {
            $formatedName = ImageType::getFormattedName('small');
            $imageLink = $this->context->link->getImageLink(
                $this->currentProduct->link_rewrite,
                $this->currentProduct->id . '-' . $image['id_image'],
                $formatedName
            );

            $product['image_url'] = $imageLink;
        }

        $params = [
            'current_product' => $product,
            'price' => number_format((float)$this->currentProduct->price, 2, '.', ''),
            'link_next' => $this->getProductLink(),
            'link_previous' => $this->getProductLink(false),
        ];

        return $this->context->smarty->fetch(
            $this->module->getLocalPath() . '/views/templates/admin/product_details.tpl',
            $params
        );
    }

    /**
     * Set current product or redirect if product is invalid
     *
     * @return void
     */
    private function setCurrentProduct()
    {
        $productId = (int)$this->request->query->get('id_product');
        $product = new Product(
            $productId,
            false,
            $this->context->language->id
        );

        if (!Validate::isLoadedObject($product)) {
            Tools::redirectAdmin('AdminSmartUpsellAdvancedProductPage');
        }

        $this->currentProduct = $product;
    }

    /**
     * Gets data for next/previus products link
     *
     * @param bool $next
     * @return array
     */

    private function getProductLink($next = true)
    {
        $result = ProductRepository::getNextOrPreviousProductId($this->currentProduct->id, $next);

        if (empty($result)) {
            return [];
        }

        $result = $result[0];

        return [
            'name' => '',
            'link' => $this->context->link->getAdminLink('AdminSmartUpsellAdvancedProductDetails') .
                '&id_product=' . (int)$result['id_product']
        ];
    }

    /**
     * Creates products in the product search field
     */
    private function processAJAXProductSearch($query)
    {
        // If not Ajax request, quit.
        if (!$this->isXmlHttpRequest()) {
            return;
        }
        // Returns products in database by query
        $productSearch = new ProductBySearchProvider();
        $responseFields = $productSearch->searchProduct($query, $this->context, true);

        $response = $this->context->smarty->fetch(
            $this->module->getLocalPath() . '/views/templates/admin/search_result.tpl',
            $responseFields
        );

        die($response);
    }

    /**
     * Display product status icon
     *
     * @param $status
     * @return string
     */
    public function displayStatusField($status)
    {
        return $this->context->smarty->fetch(
            $this->module->getLocalPath() . '/views/templates/admin/status_field.tpl',
            [
                'status' => (bool)$status
            ]
        );
    }

    /**
     * Process set relation for multiple products
     *
     * @return void
     */
    private function processSetUpsellBulk($idselectedProduct)
    {
        $upsellRelationsSuccessfullyAdded = true;
        $productBox = Tools::getValue('available_productsBox');

        if ($productBox != false) {
            foreach ($productBox as $upsell) {
                $upsellRelationsSuccessfullyAdded = $upsellRelationsSuccessfullyAdded &&
                    ProductRepository::insertUpsellRelation($idselectedProduct, $upsell);
            }
        }

        if ($upsellRelationsSuccessfullyAdded) {
            $this->confirmations[] = $this->l('All upsells has been successfully added');
        } else {
            $this->errors[] = $this->l('Something went wrong');
        }
    }


    /**
     * Process unset relation for multiple products
     *
     * @return void
     */
    private function processUnsetUpsellBulk($idselectedProduct)
    {
        $upsellRelationsSuccessfullyRemoved = true;
        $productBox = Tools::getValue('upsell_productsBox');

        if ($productBox != false) {
            foreach ($productBox as $upsell) {
                $upsellRelationsSuccessfullyRemoved = $upsellRelationsSuccessfullyRemoved &&
                    ProductRepository::deleteUpsellRelation($idselectedProduct, $upsell);
            }
        }

        if ($upsellRelationsSuccessfullyRemoved) {
            $this->confirmations[] = $this->l('All upsells has been successfully removed');
        } else {
            $this->errors[] = $this->l('Something went wrong');
        }
    }
}
