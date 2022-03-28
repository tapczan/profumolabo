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

if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require_once __DIR__.'/vendor/autoload.php';
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

        /**
         * Get value for CREATEIT_RELATED_PRODUCT_INCLUDED_FEATURES
         */
        $featureListVal = $this->getFeaturedList();

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
                AND p.id_product NOT IN ('.(int) $product["id_product"].')
                AND  fp.id_feature IN ('.implode(",", $featureListVal).')
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

            $filteredFeatures = array_filter(Tools::getAllValues(), function ($key) {
                return strpos($key, 'CREATEIT_RELATED_PRODUCT_INCLUDED_FEATURES_') === 0;
            }, ARRAY_FILTER_USE_KEY);

            $filteredFeatureKeys = array_keys($filteredFeatures);
            $filteredFeatureKeysClean = str_replace('CREATEIT_RELATED_PRODUCT_INCLUDED_FEATURES_', '',$filteredFeatureKeys);

            // check that the value is valid
            if (empty($configValue) || !Validate::isInt($configValue)) {
                // invalid value, show an error
                $output = $this->displayError($this->trans('Invalid Configuration value', array(), 'Modules.CreateitRelatedProducts.Admin'));
            } else {
                // value is ok, update it and display a confirmation message
                Configuration::updateValue('CREATEIT_RELATED_PRODUCT_LIMIT', $configValue);
                Configuration::updateValue('CREATEIT_RELATED_PRODUCT_INCLUDED_FEATURES', serialize($filteredFeatureKeysClean));

                $output = $this->displayConfirmation($this->trans('Settings updated', array(), 'Modules.CreateitRelatedProducts.Admin'));
            }
        }

        // display any message, then the form
        return $output . $this->displayForm();
    }

    /**
     * @return string
     * @throws PrestaShopDatabaseException
     */
    public function displayForm()
    {
        $language_id = $this->context->language->id;

        $feature_list_arr = $this->getSavedFeaturedLIst($language_id);

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
                    [
                        'type' => 'checkbox',
                        'label' => $this->trans('Features to be included', array(), 'Modules.CreateitRelatedProducts.Admin'),
                        'name' => 'CREATEIT_RELATED_PRODUCT_INCLUDED_FEATURES',
                        'values' => [
                            'query' => $feature_list_arr,
                            'id' => 'id_feature',
                            'name' => 'name',
                        ],
                    ]
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

        /**
         * Populate default value for CREATEIT_RELATED_PRODUCT_INCLUDED_FEATURES
         */
        $featureListVal = $this->getFeaturedList();

        if(count($featureListVal)){
            foreach($featureListVal as $feature){
                $currentFeature = 'CREATEIT_RELATED_PRODUCT_INCLUDED_FEATURES_' . $feature;
                $helper->fields_value[$currentFeature] = 'on';
            }
        }

        return $helper->generateForm([$form]);
    }

    /**
     * @return array
     * @throws PrestaShopDatabaseException
     */
    private function getFeaturedList() : array
    {
        if(
        $dbFeatureList = Db::getInstance()->executeS('
            SELECT value FROM `' . _DB_PREFIX_ . 'configuration` WHERE name like "%CREATEIT_RELATED_PRODUCT_INCLUDED_FEATURES%"
        ')){
            $dbFeatureListRes = reset($dbFeatureList);
            return unserialize($dbFeatureListRes['value']);
        }else{
            return [];
        }
    }

    /**
     * @param int $language_id
     * @return array
     * @throws PrestaShopDatabaseException
     */
    private function getSavedFeaturedLIst(int $language_id): array
    {
        $feature_list_arr = [];

        if($feature_list = Db::getInstance()->executeS('
            SELECT id_feature,name FROM `' . _DB_PREFIX_ . 'feature_lang` WHERE `id_lang` = '.$language_id.'
        ')){
            foreach($feature_list as $feature)
            {
                $feature_list_arr[] = [
                    'id_feature' => $feature['id_feature'],
                    'name' => $feature['name']
                ];
            }
        }

        return $feature_list_arr;
    }

}