<?php

declare(strict_types=1);

use PrestaShop\Module\CreateitRelatedProducts\Install\Installer;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Presenter\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;

if (!defined('_PS_VERSION_')) {
    exit;
}

class createit_related_products extends Module
{
    public function __construct()
    {
        $this->name = 'createit_related_products';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'createIT';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('createIT Related Products', array(), 'Modules.CreateitRelatedProducts.Admin');
        $this->description = $this->trans('createIT\'s related products module to display other products with the feature.', array(), 'Modules.CreateitRelatedProducts.Admin');

        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', array(), 'Modules.CreateitRelatedProducts.Admin');
    }
    
    public function hookDisplayRelatedProducts($params)
    {
        $product = $params['product'];

        $limit = Configuration::get('CREATEIT_RELATED_PRODUCT_LIMIT');

        $res = Db::getInstance()->executeS('SELECT DISTINCT
                    p.*
                FROM
                    `' . _DB_PREFIX_ . 'feature_product` AS fp
                        LEFT JOIN
                    `' . _DB_PREFIX_ . 'product` p ON p.id_product = fp.id_product
                WHERE
                    `id_feature_value` IN (SELECT 
                            `id_feature_value`
                        FROM
                            `' . _DB_PREFIX_ . 'feature_product`
                        WHERE
                            `id_product` = '.(int) $product["id_product"].')
                LIMIT '.(int) $limit.'
            ');

        $assembler = new ProductAssembler($this->context);

        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new ProductListingPresenter(
            new ImageRetriever(
                $this->context->link
            ),
            $this->context->link,
            new PriceFormatter(),
            new ProductColorsRetriever(),
            $this->context->getTranslator()
        );


        $products_for_template = [];

        foreach ($res as $rawProduct) {
            $products_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }

        $this->context->smarty->assign([
            'products' => $products_for_template,
            'limit' => $limit
        ]);

        return $this->display(__FILE__, 'views/templates/front/related_products.tpl');
    }

    /**
     * @return bool
     */
    public function install($keep = true)
    {
        if (!parent::install()) {
            return false;
        }

        $installer = new Installer();

        return $installer->install($this);
    }

    /**
     * @param bool $keep
     * @return bool
     */
    public function uninstall($keep = true)
    {
        return parent::uninstall();
    }

    /**
     * @return bool
     */
    public function reset()
    {
        if (!$this->uninstall(false)) {
            return false;
        }
        if (!$this->install(false)) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        $output = '';

        // this part is executed only when the form is submitted
        if (Tools::isSubmit('submit' . $this->name)) {
            // retrieve the value set by the user
            $configValue = Tools::getValue('CREATEIT_RELATED_PRODUCT_LIMIT');

            // check that the value is valid
            if (empty($configValue) || !Validate::isInt($configValue)) {
                // invalid value, show an error
                $output = $this->displayError($this->trans('Invalid Configuration value', array(), 'Modules.CreateitRelatedProducts.Admin'));
            } else {
                // value is ok, update it and display a confirmation message
                Configuration::updateValue('CREATEIT_RELATED_PRODUCT_LIMIT', $configValue);
                $output = $this->displayConfirmation($this->trans('Settings updated', array(), 'Modules.CreateitRelatedProducts.Admin'));
            }
        }

        // display any message, then the form
        return $output . $this->displayForm();
    }

    /**
     * @return string
     */
    public function displayForm()
    {
        // Init Fields form array
        $form = [
            'form' => [
                'legend' => [
                    'title' => $this->trans('Settings', array(), 'Modules.CreateitRelatedProducts.Admin'),
                ],
                'input' => [
                    [
                        'type' => 'text',
                        'label' => $this->trans('Related Products Limit', array(), 'Modules.CreateitRelatedProducts.Admin'),
                        'name' => 'CREATEIT_RELATED_PRODUCT_LIMIT',
                        'size' => 20,
                        'required' => true,
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right',
                ],
            ],
        ];

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->table = $this->table;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&' . http_build_query(['configure' => $this->name]);
        $helper->submit_action = 'submit' . $this->name;

        // Default language
        $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');

        // Load current value into the form
        $helper->fields_value['CREATEIT_RELATED_PRODUCT_LIMIT'] = Tools::getValue('CREATEIT_RELATED_PRODUCT_LIMIT', Configuration::get('CREATEIT_RELATED_PRODUCT_LIMIT'));

        return $helper->generateForm([$form]);
    }



}