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

use Invertus\SmartUpsellAdvanced\Controller\AdminSmartUpsellAbstractController;

class AdminSmartUpsellAdvancedSettingsController extends AdminSmartUpsellAbstractController
{
    /**
     * AdminSmartUpsellAdvancedSettingsController constructor.
     */
    public function __construct()
    {
        $this->bootstrap = true;

        parent::__construct();
        $this->content = $this->module->getFeedbackMessage();
        $this->renderOptionFields();
    }

    /**
     * Set fields for rendering
     */
    private function renderOptionFields()
    {
        $this->fields_options = [
            'product_page' => [
                'title' => $this->l('Product Page'),
                'fields' => [
                    'SUA_SORT_BY_FIRST_KEY' => [
                        'title' => $this->l('Automatic sorting (by first key)'),
                        'cast' => 'pSQL',
                        'type' => 'select',
                        'list' => [
                            [
                                'name' => $this->l('Highest price'),
                                'id' => 'highest_price',
                            ],
                            [
                                'name' => $this->l('Lowest price'),
                                'id' => 'lowest_price',],
                            [
                                'name' => $this->l('Highest discount'),
                                'id' => 'highest_discount',
                            ],
                            [
                                'name' => $this->l('Lowest discount'),
                                'id' => 'lowest_discount',
                            ],
                            [
                                'name' => $this->l('Name A-Z'),
                                'id' => 'name_a_z',
                            ],
                            [
                                'name' => $this->l('Name Z-A'),
                                'id' => 'name_z_a',
                            ],
                            [
                                'name' => $this->l('Newest'),
                                'id' => 'newest',
                            ],
                            [
                                'name' => $this->l('Oldest'),
                                'id' => 'oldest',
                            ],
                            [
                                'name' => $this->l('Highest quantity'),
                                'id' => 'highest_quantity',
                            ],
                            [
                                'name' => $this->l('Lowest quantity'),
                                'id' => 'lowest_quantity',
                            ],
                        ],
                        'identifier' => 'id',
                    ],
                    'SUA_SORT_BY_SECOND_KEY' => [
                        'title' => $this->l('Automatic sorting (by second key)'),
                        'cast' => 'pSQL',
                        'type' => 'select',
                        'list' => [
                            [
                                'name' => $this->l('Highest price'),
                                'id' => 'highest_price',
                            ],
                            [
                                'name' => $this->l('Lowest price'),
                                'id' => 'lowest_price',],
                            [
                                'name' => $this->l('Highest discount'),
                                'id' => 'highest_discount',
                            ],
                            [
                                'name' => $this->l('Lowest discount'),
                                'id' => 'lowest_discount',
                            ],
                            [
                                'name' => $this->l('Name A-Z'),
                                'id' => 'name_a_z',
                            ],
                            [
                                'name' => $this->l('Name Z-A'),
                                'id' => 'name_z_a',
                            ],
                            [
                                'name' => $this->l('Newest'),
                                'id' => 'newest',
                            ],
                            [
                                'name' => $this->l('Oldest'),
                                'id' => 'oldest',
                            ],
                            [
                                'name' => $this->l('Highest quantity'),
                                'id' => 'highest_quantity',
                            ],
                            [
                                'name' => $this->l('Lowest quantity'),
                                'id' => 'lowest_quantity',
                            ],
                        ],
                        'identifier' => 'id',
                    ],
                    'SUA_MAX_UPSELL_PRODUCTS' => [
                        'title' => $this->l('Max upsell products to display'),
                        'type' => 'text',
                        'label' => 'simple input text',
                        'name' => 'type_text',
                        'cast' => 'intval',
                    ],
                    'SUA_OUT_OF_STOCK_POPUP' => [
                        'title' => $this->l('Show pop up if out of stock'),
                        'cast' => 'intval',
                        'type' => 'bool',
                    ],
                ],
                'submit' => ['title' => $this->l('Save')],
            ],
            'cart' => [
                'title' => $this->l('Cart'),
                'fields' => [
                    'SUA_SPEC_OFFER_LIMIT' => [
                        'title' => $this->l('Limit special offers'),
                        'type' => 'text',
                        'name' => 'type_text',
                        'cast' => 'intval',
                    ],
                    'SUA_SAME_OFFER_TO_SAME_CLIENT' => [
                        'title' => $this->l('Same product to the same clients'),
                        'type' => 'radio',
                        'label' => 'radios',
                        'name' => 'type_radio',
                        'choices' => [
                            'show' => $this->l('Show'),
                            'dont_show' => $this->l('Don\'t show'),
                            'dont_show_month' => $this->l('Don\'t show for a month'),
                            'dont_show_year' => $this->l('Don\'t show for a year'),
                        ],
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];
        $this->validateMaxUpsellProducts();
        $this->validateLimitSpecialOffers();
    }

    /**
     * Validate input field if value doesn't exceeds maximum and is integer
     */
    private function validateMaxUpsellProducts()
    {
        $maxUpsellProducts = Tools::getValue('SUA_MAX_UPSELL_PRODUCTS');
        if ($maxUpsellProducts < 0) {
            $this->errors[] = $this->l('Special offer limit must be positive');
        }
        if (!ctype_digit($maxUpsellProducts) && $maxUpsellProducts != false) {
            $this->errors[] = $this->l('Special offer limit must be integer');
        }
    }

    /**
     * Validate input field if value is positive integer
     */
    private function validateLimitSpecialOffers()
    {
        $specialOfferLimit = Tools::getValue('SUA_SPEC_OFFER_LIMIT');
        if ($specialOfferLimit < 0) {
            $this->errors[] = $this->l('Special offer limit must be positive');
        }
        if (!ctype_digit($specialOfferLimit) && $specialOfferLimit != false) {
            $this->errors[] = $this->l('Special offer limit must be integer');
        }
    }
}
