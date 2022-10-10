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
    /**
     * Static male category array.
     */
    CONST MALE_ARR = [4,11,179,184];

    /**
     * Static female category array.
     */
    CONST FEMALE_ARR = [5,7,10,177,178];

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

        $this->displayName = $this->trans('createIT Related Products', array(), 'Modules.Createitrelatedproducts.Admin');
        $this->description = $this->trans('createIT\'s related products module to display other products with the feature.', array(), 'Modules.Createitrelatedproducts.Admin');

        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', array(), 'Modules.Createitrelatedproducts.Admin');
    }
    
    public function hookDisplayRelatedProducts($params)
    {
        $product = $params['product'];

        $limit = Configuration::get('CREATEIT_RELATED_PRODUCT_LIMIT');

        $limitQuery = is_numeric($limit) ? 'LIMIT '.(int) $limit : '';

        /**
         * Get value for CREATEIT_RELATED_PRODUCT_INCLUDED_FEATURES
         */
        $featureListVal = $this->getFeaturedList();

        /**
         * Get opposite gender products
         */
        $oppositeGenderProducts = $this->getExcludedOppositeGenderProducts((int) $product["id_product"]);

        $excludedCategory = $this->getSavedExcludedProductsCategoryList();

        $excludedCategory[] = (int) $product["id_product"];

        $excludedCategoryWithOppositeGender = array_merge($excludedCategory, $oppositeGenderProducts);

        $sqlExcludedProducts = empty($excludedCategoryWithOppositeGender) ? '' : 'AND p.id_product NOT IN ('. implode(',', $excludedCategoryWithOppositeGender ).')';
        $sqlFeature = empty($featureListVal) ? '' : 'AND  fp.id_feature IN ('.implode(",", $featureListVal).')' ;

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
                '. $sqlExcludedProducts .'
                '. $sqlFeature .'
                '. $limitQuery .'
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

            $filteredCategories = array_filter(Tools::getAllValues(), function ($key) {
                return strpos($key, 'CREATEIT_RELATED_PRODUCT_INCLUDED_CATEGORIES_') === 0;
            }, ARRAY_FILTER_USE_KEY);

            $filteredFeatureKeys = array_keys($filteredFeatures);
            $filteredFeatureKeysClean = str_replace('CREATEIT_RELATED_PRODUCT_INCLUDED_FEATURES_', '', $filteredFeatureKeys);

            $filteredCategoriesKeys = array_keys($filteredCategories);
            $filteredCategoriesKeysClean = str_replace('CREATEIT_RELATED_PRODUCT_INCLUDED_CATEGORIES_', '', $filteredCategoriesKeys);

            // check that the value is valid
            if (empty($configValue) || !Validate::isInt($configValue)) {
                // invalid value, show an error
                $output = $this->displayError($this->trans('Invalid Configuration value', array(), 'Modules.Createitrelatedproducts.Admin'));
            } else {
                // value is ok, update it and display a confirmation message
                Configuration::updateValue('CREATEIT_RELATED_PRODUCT_LIMIT', $configValue);
                Configuration::updateValue('CREATEIT_RELATED_PRODUCT_INCLUDED_FEATURES', serialize($filteredFeatureKeysClean));
                Configuration::updateValue('CREATEIT_RELATED_PRODUCT_INCLUDED_CATEGORIES', serialize($filteredCategoriesKeysClean));

                $output = $this->displayConfirmation($this->trans('Settings updated', array(), 'Modules.Createitrelatedproducts.Admin'));
            }
        }

        // display any message, then the form
        return $output . $this->displayForm();
    }

    private function getSavedExcludedProductsCategoryList()
    {
        $category_arr = $this->getCategoryList();
        $products = [];

        if(!empty($category_arr))
        {
            if($productsRes = Db::getInstance()->executeS('SELECT id_product FROM `' . _DB_PREFIX_ . 'category_product` where id_category in ('.implode(',',$category_arr).') group by id_product'))
            {
                foreach ($productsRes as $id)
                {
                    $products[] = (int) $id['id_product'];
                }
            }
        }

        return $products;
    }

    /**
     * @return string
     * @throws PrestaShopDatabaseException
     */
    public function displayForm()
    {
        $language_id = $this->context->language->id;

        $feature_list_arr = $this->getSavedFeaturedLIst($language_id);

        $category_list_arr = $this->getSavedCategoryList($this->context->language->id);

        $form = [
            'form' => [
                'legend' => [
                    'title' => $this->trans('Settings', array(), 'Modules.Createitrelatedproducts.Admin'),
                ],
                'input' => [
                    [
                        'type' => 'text',
                        'label' => $this->trans('Related Products Limit', array(), 'Modules.Createitrelatedproducts.Admin'),
                        'name' => 'CREATEIT_RELATED_PRODUCT_LIMIT',
                        'size' => 20,
                        'required' => true,
                    ],
                    [
                        'type' => 'checkbox',
                        'label' => $this->trans('Features to be included', array(), 'Modules.Createitrelatedproducts.Admin'),
                        'name' => 'CREATEIT_RELATED_PRODUCT_INCLUDED_FEATURES',
                        'values' => [
                            'query' => $feature_list_arr,
                            'id' => 'id_feature',
                            'name' => 'name',
                        ],
                    ],
                    [
                        'type' => 'checkbox',
                        'label' => $this->trans('Categories to be excluded', array(), 'Modules.Createitrelatedproducts.Admin'),
                        'name' => 'CREATEIT_RELATED_PRODUCT_INCLUDED_CATEGORIES',
                        'values' => [
                            'query' => $category_list_arr,
                            'id' => 'id_category',
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

        $categoryListVal = $this->getCategoryList();

        if(count($featureListVal)){
            foreach($featureListVal as $feature){
                $currentFeature = 'CREATEIT_RELATED_PRODUCT_INCLUDED_FEATURES_' . $feature;
                $helper->fields_value[$currentFeature] = 'on';
            }
        }

        if(count($categoryListVal)){
            foreach($categoryListVal as $category){
                $currentCategory = 'CREATEIT_RELATED_PRODUCT_INCLUDED_CATEGORIES_' . $category;
                $helper->fields_value[$currentCategory] = 'on';
            }
        }

        return $helper->generateForm([$form]);
    }

    /**
     * @return array
     * @throws PrestaShopDatabaseException
     */
    private function getCategoryList() : array
    {
        if(
        $dbCategoryList = Db::getInstance()->executeS('
            SELECT value FROM `' . _DB_PREFIX_ . 'configuration` WHERE name like "%CREATEIT_RELATED_PRODUCT_INCLUDED_CATEGORIES%"
        ')){
            $dbCategoryListRes = reset($dbCategoryList);
            return unserialize($dbCategoryListRes['value']);
        }else{
            return [];
        }
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


    /**
     * @param int $language_id
     * @return array
     * @throws PrestaShopDatabaseException
     */
    private function getSavedCategoryList(int $language_id): array
    {
        $list = [];

        if($category_list = Db::getInstance()->executeS('
        SELECT
            c.id_category,
            cl.name
        FROM 
            `' . _DB_PREFIX_ . 'category` as c
        LEFT JOIN
            `' . _DB_PREFIX_ . 'category_lang` cl ON (cl.id_category = c.id_category)
        WHERE
            cl.id_lang = '.$language_id.'
        ORDER BY c.id_category ASC
        ')){
            foreach($category_list as $category)
            {
                $list[] = [
                    'id_category' => $category['id_category'],
                    'name' => $category['name']
                ];
            }
        }

        return $list;
    }

    private function getProductCategories(int $id_product)
    {
        $category = [];

        if($productsRes = Db::getInstance()->executeS('SELECT id_category FROM `' . _DB_PREFIX_ . 'category_product` WHERE id_product = '.$id_product.''))
        {
            foreach ($productsRes as $id)
            {
                $category[] = (int) $id['id_category'];
            }
        }

        return $category;
    }

    private function getProductGender(int $id_product): string
    {
        $category_id = [];
        $gender = '';

        if(!empty($category_id = $this->getProductCategories($id_product))) {
            if (array_intersect($category_id, self::MALE_ARR)){
                $gender = 'male';
                if(array_intersect($category_id, self::FEMALE_ARR)){
                    $gender = 'unisex';
                }
            }else{
                $gender = 'female';
                if(array_intersect($category_id, self::MALE_ARR)){
                    $gender = 'unisex';
                }
            }
        }

        return $gender;
    }

    private function getExcludedOppositeGenderProducts(int $id_product): array
    {
        $result = [];

        switch ($this->getProductGender($id_product))
        {
            case 'male':
                if($query = Db::getInstance()->executeS('SELECT id_product FROM `' . _DB_PREFIX_ . 'category_product` WHERE id_category in ('.implode(",", self::FEMALE_ARR).')')){
                    foreach ($query as $id)
                    {
                        $result[] = (int) $id['id_product'];
                    }
                }
                break;
            case 'female':
                if($query = Db::getInstance()->executeS('SELECT id_product FROM `' . _DB_PREFIX_ . 'category_product` WHERE id_category in ('.implode(",", self::MALE_ARR).')')){
                    foreach ($query as $id)
                    {
                        $result[] = (int) $id['id_product'];
                    }
                }
                break;
            default:
                $result = [];

        }

        return $result;
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }


}