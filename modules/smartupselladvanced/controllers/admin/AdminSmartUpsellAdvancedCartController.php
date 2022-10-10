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

use Invertus\SmartUpsellAdvanced\Entity\SpecialOffer;
use Invertus\SmartUpsellAdvanced\Helper\PriceHelper;
use Symfony\Component\HttpFoundation\Request;
use Invertus\SmartUpsellAdvanced\AdminBusinessLogicProvider\ProductBySearchProvider;
use Invertus\SmartUpsellAdvanced\Repository\ProductRepository;
use Invertus\SmartUpsellAdvanced\Controller\AdminSmartUpsellAbstractController;

/**
 * @property SmartUpsellAdvanced $module
 */
class AdminSmartUpsellAdvancedCartController extends AdminSmartUpsellAbstractController
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * AdminSmartUpsellAdvancedCartController constructor.
     */
    public function __construct()
    {
        $this->bootstrap = true;
        $this->className = SpecialOffer::class;
        $this->table = 'special_offer';
        $this->request = Request::createFromGlobals();

        parent::__construct();

        $this->content = $this->module->getFeedbackMessage();
        $this->renderSpecialOfferList();
    }

    /**
     * Process AJAX requests
     *
     * @return bool|ObjectModel
     */
    public function postProcess()
    {
        $action = Tools::getValue('action');

        if ('search_products' === $action) {
            $this->processAJAXProductSearch();
        }

        if ('change_discount_prefix' === $action) {
            $this->processAJAXChangeDiscountPrefix();
        }

        return parent::postProcess();
    }

    /**
     * Initialise fields for the form
     *
     * @return string|void
     */
    public function renderForm()
    {
        /** @var SpecialOffer $obj */
        if (!($obj = $this->loadObject(true))) {
            return;
        }
        $groups = Group::getGroups($this->default_form_language, true);

        $this->fields_form = [
            'legend' => [
                'title' => $this->l('Create/edit special offer'),
            ],
            'description' => sprintf($this->l('Current server date: %s'), date('Y-m-d H:i:s')),            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Name'),
                    'name' => 'name',
                    'lang' => true,
                    'required' => true,
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Active'),
                    'name' => 'is_active',
                    'values' => [
                        [
                            'id' => 'is_active_on',
                            'value' => 1,
                        ],
                        [
                            'id' => 'is_active_off',
                            'value' => 0,
                        ],
                    ],
                ],
                [
                    'type' => 'free',
                    'label' => $this->l('Main product'),
                    'name' => 'main_product_search_input',
                    'required' => true,
                ],
                [
                    'type' => 'radio',
                    'label' => $this->l('Type'),
                    'name' => 'is_type',
                    'values' => [
                        [
                            'id' => 'upsell',
                            'value' => 0,
                            'label' => $this->l('Upsell'),
                        ],
                        [
                            'id' => 'crosssell',
                            'value' => 1,
                            'label' => $this->l('Crosssell'),
                        ],
                    ],
                ],
                [
                    'type' => 'radio',
                    'label' => $this->l('Time limit'),
                    'name' => 'is_limited_time',
                    'values' => [
                        [
                            'id' => 'unlimited',
                            'value' => 0,
                            'label' => $this->l('Unlimited'),
                        ],
                        [
                            'id' => 'limited',
                            'value' => 1,
                            'label' => $this->l('Limited'),
                        ],
                    ],
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Seconds'),
                    'name' => 'time_limit',
                ],
                [
                    'type' => 'free',
                    'label' => $this->l('Special product'),
                    'name' => 'special_product_search_input',
                    'required' => true,
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Valid only in specific interval'),
                    'name' => 'is_valid_in_specific_interval',
                    'values' => [
                        [
                            'id' => 'type_switch_on',
                            'value' => 1,
                        ],
                        [
                            'id' => 'type_switch_off',
                            'value' => 0,
                        ],
                    ],
                ],
                [
                    'type' => 'datetime',
                    'label' => $this->l('Valid from'),
                    'name' => 'valid_from',
                    'hint' => $this->l('Server date'),

                ],
                [
                    'type' => 'datetime',
                    'label' => $this->l('Valid to'),
                    'name' => 'valid_to',
                    'hint' => $this->l('Server date'),
                ],
                [
                    'type' => 'hidden',
                    'label' => $this->l('Id Shop'),
                    'name' => 'id_shop',
                ],
                [
                    'type' => 'group',
                    'label' => $this->l('Valid only for specific groups'),
                    'name' => '$special_offer_groups',
                    'values' => $groups,
                    'required' => true,
                    'col' => '6',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Discount'),
                    'name' => 'discount',
                    'prefix' => '%',
                ],
                [
                    'type' => 'select',
                    'name' => 'discount_type',
                    'options' => [
                        'query' => [
                            ['id' => 'percent', 'name' => $this->l('Percentage')],
                            ['id' => 'amount', 'name' => $this->l('Amount')],
                        ],
                        'id' => 'id',
                        'name' => 'name',
                    ],
                ],
            ],
            'submit' => [
                'title' => 'Save',
            ],
        ];

        $dateTimeNow = date('Y-m-d H:i:s');

        // Gets saved main and special object names
        $main_product_name = '';
        $special_product_value = '';
        $main_product_id = '';
        $special_product_name = '';
        if ($this->object->id_main_product != null) {
            $main_product_name = Product::getProductName($this->object->id_main_product);
            $main_product_id = $this->object->id_main_product;
        }
        if ($this->object->id_special_product != null) {
            $special_product_value = Product::getProductName($this->object->id_special_product);
            $special_product_name = $this->object->id_special_product;
        }

        // Render custom smarty inputs
        $this->fields_value = [
            'main_product_search_input' => $this->context->smarty->fetch(
                $this->module->getLocalPath().'/views/templates/admin/search_input.tpl',
                [
                    'input_name' => 'main_product',
                    'input_value' => $main_product_name,
                    'hidden_value' => $main_product_id,
                ]
            ),
            'special_product_search_input' => $this->context->smarty->fetch(
                $this->module->getLocalPath().'/views/templates/admin/search_input.tpl',
                [
                    'input_name' => 'special_product',
                    'input_value' => $special_product_value,
                    'hidden_value' => $special_product_name,
                ]
            ),
        ];

        // If object SpecialOffer object is empty initiate values
        if ($this->object->id_main_product == null) {
            $this->fields_value = [
                'time_limit' => '-',
                'is_active' => 1,
                'discount' => 0,
                'valid_from' => $dateTimeNow,
                'valid_to' => $dateTimeNow,
                'main_product_search_input' => $this->context->smarty->fetch(
                    $this->module->getLocalPath().'/views/templates/admin/search_input.tpl',
                    ['input_name' => 'main_product', 'input_value' => $main_product_name, 'hidden_value' => '']
                ),
                'special_product_search_input' => $this->context->smarty->fetch(
                    $this->module->getLocalPath().'/views/templates/admin/search_input.tpl',
                    ['input_name' => 'special_product', 'input_value' => $special_product_value, 'hidden_value' => '']
                ),
                'id_shop' => $this->context->shop->id,
            ];
        }

        // Added values of object Group
        if (!Validate::isUnsignedId($obj->id)) {
            $customer_groups = [];
        } else {
            $customer_groups = $obj->getGroups();
        }
        $customer_groups_ids = [];
        if (is_array($customer_groups)) {
            foreach ($customer_groups as $customer_group) {
                $customer_groups_ids[] = $customer_group;
            }
        }

        // if empty $carrier_groups_ids : object creation : we set the default groups
        if (empty($customer_groups_ids)) {
            $preselected = [
                Configuration::get('PS_UNIDENTIFIED_GROUP'),
                Configuration::get('PS_GUEST_GROUP'),
                Configuration::get('PS_CUSTOMER_GROUP')
            ];
            $customer_groups_ids = array_merge($customer_groups_ids, $preselected);
        }

        foreach ($groups as $group) {
            $this->fields_value['groupBox_' . $group['id_group']] =
                Tools::getValue('groupBox_' . $group['id_group'], in_array($group['id_group'], $customer_groups_ids));
        }

        return parent::renderForm();
    }

    /**
     * Display product status icon
     *
     * @param $status
     * @return string
     */
    public function displayStatusField($status)
    {
        return $this->context->smarty->fetch($this->module->getLocalPath().'/views/templates/admin/status_field.tpl', [
            'status' => (bool) $status,
        ]);
    }

    /**
     * Initialise fields for the list
     */
    private function renderSpecialOfferList()
    {
        $this->list_no_link = true;
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->fields_list = [
            'sol_name' => [
                'title' => $this->l('Name'),
                'type' => 'text',
                'align' => 'text-center',
                'width' => 30,
                'filter_key' => 'sol!name',
            ],
            'id_main_product' => [
                'title' => $this->l('Product ID'),
                'type' => 'text',
                'align' => 'text-center',
                'width' => 30,
            ],
            'id_image' => [
                'title' => $this->l('Image'),
                'align' => 'center',
                'image' => 'p',
                'orderby' => false,
                'filter' => false,
                'search' => false
            ],
            'product_name' => [
                'title' => $this->l('Product'),
                'type' => 'text',
                'align' => 'text-center',
                'filter_key' => 'pl!name',
            ],
            'time_limit' => [
                'title' => $this->l('Time'),
                'type' => 'text',
                'align' => 'text-center',
            ],
            'times_used' => [
                'title' => $this->l('Used'),
                'type' => 'text',
                'align' => 'text-center',
            ],
            'is_active' => [
                'title' => $this->l('Status'),
                'align' => 'text-center',
                'callback' => 'displayStatusField',
                'type' => 'select',
                'list' => [
                    1 => $this->l('Yes'),
                    0 => $this->l('No'),
                ],
                'filter_key' => 'a!active',
            ],
        ];

        $this->_select = 'pl.name AS product_name, ';
        $this->_select .= 'ims.`id_image`, ';
        $this->_select .= 'sol.name AS sol_name';

        $this->_join .= '
            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
                ON (pl.`id_product` = a.`id_main_product`
                    AND pl.`id_lang` = '.(int)$this->context->language->id.'
                    AND pl.`id_shop` = '.(int)$this->context->shop->id.')
        ';
        $this->_join .= '
            LEFT JOIN `'._DB_PREFIX_.'image` i
                ON (i.`id_product` = a.`id_main_product`)
            INNER JOIN `'._DB_PREFIX_.'image_shop` ims
                ON (ims.`id_image` = i.`id_image`
                    AND ims.`id_shop` = '.(int)$this->context->shop->id.'
                    AND ims.`cover` = 1)
        ';
        $this->_join .= '
            LEFT JOIN `'._DB_PREFIX_.'special_offer_lang` sol
                ON (sol.`id_special_offer` = a.`id_special_offer`
                    AND sol.`id_lang` = '.(int)$this->context->language->id.')'
        ;
        $this->_group = 'GROUP BY a.`id_main_product`';
    }

    /**
     * Set custom CSS and JS
     *
     * @param bool $isNewTheme
     */
    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);
        $this->addCSS(_MODULE_DIR_.'smartupselladvanced/views/css/bo-cart.css');
        $this->addJS(_MODULE_DIR_.'smartupselladvanced/views/js/bo_product_search.js');
        $this->addJS(_MODULE_DIR_.'smartupselladvanced/views/js/bo_change_secret_input_value.js');
        $this->addJS(_MODULE_DIR_.'smartupselladvanced/views/js/bo_hide_input.js');
        $this->addJS(_MODULE_DIR_.'smartupselladvanced/views/js/bo_change_discount_prefix.js');
    }

    /**
     * Process product search ajax request
     */
    private function processAJAXProductSearch()
    {
        // If not Ajax request, quit.
        if (!$this->request->isXmlHttpRequest()) {
            return;
        }

        // Returns products in database by query
        $query = Tools::getValue('query');

        $productSearch = new ProductBySearchProvider();
        $responseFields = $productSearch->searchProduct($query, $this->context);

        if (null ==$responseFields['products']) {
            die('no_products');
        }
        $response = $this->context->smarty->fetch(
            $this->module->getLocalPath().'/views/templates/admin/search_result.tpl',
            $responseFields
        );

        die($response);
    }

    /**
     * Initialise fields with custom data
     *
     * @return bool
     */
    protected function afterAdd($object)
    {
        $groups_selected = Tools::getValue('groupBox');
        if (Tools::getValue('is_valid_in_specific_interval')) {
            $second = strtotime(Tools::getValue('valid_to')) - strtotime(Tools::getValue('valid_from'));
        } elseif (!empty(Tools::getValue('id_special_offer'))) {
            $second = '-';
        }
        $object->addGroups($groups_selected);
        $object->addTime($second);
        $object->addIdShop($this->context->shop->id);
        return parent::afterAdd($object);
    }

    /**
     * Initialise fields with custom data
     *
     * @return bool
     */
    protected function afterUpdate($object)
    {
        $groups_selected = Tools::getValue('groupBox');
        $specialProductId = Tools::getValue('id_special_offer');
        $object->addGroups($groups_selected, $specialProductId);
        $object->addIdShop($this->context->shop->id);
        return parent::afterUpdate($object); // TODO: Change the autogenerated stub
    }

    /**
     * Validate fields
     */
    protected function _childValidation()
    {
        $this->assertIsSameProduct();
        $this->validateDate();
        $this->validateDiscount();
        $this->validateCustomerGroups();
        $this->assertDoesProductExists();
        $this->assertDoesSpecialProductExists();
    }

    /**
     * Validate customer group input data
     */
    private function validateCustomerGroups()
    {
        if (Tools::getValue('groupBox') == false) {
            $this->errors[] = $this->l('You must choose at least one customer group');
        }
    }

    /**
     * Checks if main product not equals to special product
     */
    private function assertIsSameProduct()
    {
        if (!Tools::isSubmit('submitAddspecial_offer')) {
            return;
        }

        // Checks if main product not equals to special product
        $mainProductId = Tools::getValue('id_main_product');
        $specialProductId = Tools::getValue('id_special_product');
        if ($specialProductId == $mainProductId) {
            $this->errors[] = $this->l('Main product and special product must be different');
        }
    }

    /**
     * Checks if main product already is in database
     */
    private function assertDoesProductExists()
    {
        $mainProductId = Tools::getValue('id_main_product');
        $isMainProductInDb = ProductRepository::getSpecialOffer($mainProductId);
        $specialProductId = Tools::getValue('id_special_offer');

        foreach ($isMainProductInDb as $product) {
            if ((int)$product['id_special_offer'] === (int)$specialProductId) {
                return;
            } elseif (true === (bool)$isMainProductInDb) {
                $this->errors[] =  $this->l('Special offer with chosen main product already exists');
            }
        }
    }

    private function assertDoesSpecialProductExists()
    {
        $specialProductId = Tools::getValue('id_special_product');
        $isMainProductInDb = ProductRepository::getSpecialOfferBySpecialProduct($specialProductId);
        $specialProductId = Tools::getValue('id_special_offer');

        foreach ($isMainProductInDb as $product) {
            if ((int)$product['id_special_offer'] === (int)$specialProductId) {
                return;
            } elseif (true === (bool)$isMainProductInDb) {
                $this->errors[] = $this->l('Special offer with chosen special product already exists');
            }
        }
    }

    /**
     * Checks if valid_from is earlier than valid_to
     *
     */
    private function validateDate()
    {

        $validFrom = strtotime(Tools::getValue('valid_from'));
        $validTo = strtotime(Tools::getValue('valid_to'));

        if ($validFrom > $validTo) {
            $this->errors[] = $this->l('"Date to" needs to be later than "data from"');
        }
    }

    /**
     * Validate discount not to exceed product price
     */
    private function validateDiscount()
    {
        /** @var PriceHelper $priceHelper */
        $priceHelper = $this->module->getService('smartupselladvanced.helper.price');
        $discount = Tools::getValue('discount');
        $discountType = Tools::getValue('discount_type');
        $specialOfferId = Tools::getValue('id_special_product');


        $productPrice = $priceHelper->getProductPrice($specialOfferId);
        if ('percent' === $discountType) {
            if ($discount > 100) {
                $this->errors[] = $this->l('The discount can\'t be higher than 100%');
            }
        }

        if ('amount' === $discountType) {
            if ($discount > $productPrice) {
                $this->errors[] = $this->l('The discount amount can\'t be higher than product price');
            }
        }
    }

    /**
     * Process ajax discount prefix call
     */
    private function processAJAXChangeDiscountPrefix()
    {
        $discountType = Tools::getValue('js_discount_type');

        if ($discountType === 'percent') {
            $discountPrefix = "%";
        } else {
            $discountPrefix = $this->context->currency->sign;
        }

        $result = [
            'success' => true,
            'discount_prefix' => $discountPrefix,
        ];

        die(json_encode($result));
    }
}
