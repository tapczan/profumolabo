<?php

/**
 * PrestaShop module created by VEKIA, a guy from official PrestaShop community ;-)
 *
 * @author    VEKIA https://www.prestashop.com/forums/user/132608-vekia/
 * @copyright 2010-2021 VEKIA
 * @license   This program is not free software and you can't resell and redistribute it
 *
 * CONTACT WITH DEVELOPER http://mypresta.eu
 * support@mypresta.eu
 */
class fbpixel extends Module
{
    public function __construct()
    {
        $this->name = 'fbpixel';
        $this->tab = 'front_office_features';
        $this->author = 'MyPresta.eu';
        $this->version = '2.1.3';
        $this->mypresta_link = 'https://mypresta.eu/modules/social-networks/fb-conversion-tracking-pixel.html';
        $this->module_key = 'd16dfcb44d033d05e3bab40156ee80a1';
        $this->secure_key = Tools::encrypt($this->name);
        parent::__construct();
        $this->bootstrap = true;
        $this->displayName = $this->l('Facebook Conversion Pixel');
        $this->description = $this->l('Module adds facebook conversion pixel to order confirmation page.');
        $this->checkforupdates(0, 0);
    }

    public function checkforupdates($display_msg = 0, $form = 0)
    {
        // ---------- //
        // ---------- //
        // VERSION 16 //
        // ---------- //
        // ---------- //
        $this->mkey = "nlc";
        if (@file_exists('../modules/' . $this->name . '/key.php')) {
            @require_once('../modules/' . $this->name . '/key.php');
        } else {
            if (@file_exists(dirname(__FILE__) . $this->name . '/key.php')) {
                @require_once(dirname(__FILE__) . $this->name . '/key.php');
            } else {
                if (@file_exists('modules/' . $this->name . '/key.php')) {
                    @require_once('modules/' . $this->name . '/key.php');
                }
            }
        }
        if ($form == 1) {
            return '
            <div class="panel" id="fieldset_myprestaupdates" style="margin-top:20px;">
            ' . ($this->psversion() == 6 || $this->psversion() == 7 ? '<div class="panel-heading"><i class="icon-wrench"></i> ' . $this->l('MyPresta updates') . '</div>' : '') . '
			<div class="form-wrapper" style="padding:0px!important;">
            <div id="module_block_settings">
                    <fieldset id="fieldset_module_block_settings">
                         ' . ($this->psversion() == 5 ? '<legend style="">' . $this->l('MyPresta updates') . '</legend>' : '') . '
                        <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
                            <label>' . $this->l('Check updates') . '</label>
                            <div class="margin-form">' . (Tools::isSubmit('submit_settings_updates_now') ? ($this->inconsistency(0) ? '' : '') . $this->checkforupdates(1) : '') . '
                                <button style="margin: 0px; top: -3px; position: relative;" type="submit" name="submit_settings_updates_now" class="button btn btn-default" />
                                <i class="process-icon-update"></i>
                                ' . $this->l('Check now') . '
                                </button>
                            </div>
                            <label>' . $this->l('Updates notifications') . '</label>
                            <div class="margin-form">
                                <select name="mypresta_updates">
                                    <option value="-">' . $this->l('-- select --') . '</option>
                                    <option value="1" ' . ((int)(Configuration::get('mypresta_updates') == 1) ? 'selected="selected"' : '') . '>' . $this->l('Enable') . '</option>
                                    <option value="0" ' . ((int)(Configuration::get('mypresta_updates') == 0) ? 'selected="selected"' : '') . '>' . $this->l('Disable') . '</option>
                                </select>
                                <p class="clear">' . $this->l('Turn this option on if you want to check MyPresta.eu for module updates automatically. This option will display notification about new versions of this addon.') . '</p>
                            </div>
                            <label>' . $this->l('Module page') . '</label>
                            <div class="margin-form">
                                <a style="font-size:14px;" href="' . $this->mypresta_link . '" target="_blank">' . $this->displayName . '</a>
                                <p class="clear">' . $this->l('This is direct link to official addon page, where you can read about changes in the module (changelog)') . '</p>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" name="submit_settings_updates"class="button btn btn-default pull-right" />
                                <i class="process-icon-save"></i>
                                ' . $this->l('Save') . '
                                </button>
                            </div>
                        </form>
                    </fieldset>
                    <style>
                    #fieldset_myprestaupdates {
                        display:block;clear:both;
                        float:inherit!important;
                    }
                    </style>
                </div>
            </div>
            </div>';
        } else {
            if (defined('_PS_ADMIN_DIR_')) {
                if (Tools::isSubmit('submit_settings_updates')) {
                    Configuration::updateValue('mypresta_updates', Tools::getValue('mypresta_updates'));
                }
                if (Configuration::get('mypresta_updates') != 0 || (bool)Configuration::get('mypresta_updates') != false) {
                    if (Configuration::get('update_' . $this->name) < (date("U") - 259200)) {
                        $actual_version = fbpixelUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version);
                    }
                    if (fbpixelUpdate::version($this->version) < fbpixelUpdate::version(Configuration::get('updatev_' . $this->name)) && Tools::getValue('ajax', 'false') == 'false') {
                        $this->context->controller->warnings[] = '<strong>' . $this->displayName . '</strong>: ' . $this->l('New version available, check http://MyPresta.eu for more informations') . ' <a href="' . $this->mypresta_link . '">' . $this->l('More details in changelog') . '</a>';
                        $this->warning = $this->context->controller->warnings[0];
                    }
                } else {
                    if (Configuration::get('update_' . $this->name) < (date("U") - 259200)) {
                        $actual_version = fbpixelUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version);
                    }
                }
                if ($display_msg == 1) {
                    if (fbpixelUpdate::version($this->version) < fbpixelUpdate::version(fbpixelUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version))) {
                        return "<span style='color:red; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('New version available!') . "</span>";
                    } else {
                        return "<span style='color:green; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('Module is up to date!') . "</span>";
                    }
                }
            }
        }
    }


    public function inconsistency($ret)
    {
        return true;
    }

    public function install()
    {
        if (parent::install() == false or
            $this->registerHook('displayOrderConfirmation') == false or
            $this->registerHook('displayProductAdditionalInfo') == false or
            $this->registerHook('header') == false or
            !Configuration::updateValue('FBPIXEL_ATC_B', '.add-to-cart') or
            !Configuration::updateValue('FBPIXEL_ATC_PPP', '.current-price span') or
            !Configuration::updateValue('FBPIXEL_SEPSIGN', '-')) {
            return false;
        }
        return true;
    }

    public function getContent()
    {
        return $this->_postProcess() . $this->displayForm() . $this->checkforupdates(0, 1);
    }

    public function psversion()
    {
        $version = _PS_VERSION_;
        $exp = explode(".", $version);
        return $exp[1];
    }

    private function _postProcess()
    {
        if (Tools::isSubmit('btnSubmit')) {
            Configuration::updateValue('FBPIXEL_PREPRICE', Tools::getValue('FBPIXEL_PREPRICE'));
            Configuration::updateValue('FBPIXEL_PURCHASE', Tools::getValue('FBPIXEL_PURCHASE'));
            Configuration::updateValue('FBPIXEL_PAGEVIEW', Tools::getValue('FBPIXEL_PAGEVIEW'));
            Configuration::updateValue('FBPIXEL_ID', Tools::getValue('FBPIXEL_ID'));
            Configuration::updateValue('FBPIXEL_LEAD', Tools::getValue('FBPIXEL_LEAD'));
            Configuration::updateValue('FBPIXEL_LEAD_N', Tools::getValue('FBPIXEL_LEAD_N'));
            Configuration::updateValue('FBPIXEL_INITIATE', Tools::getValue('FBPIXEL_INITIATE'));
            Configuration::updateValue('FBPIXEL_INITIATE_D', Tools::getValue('FBPIXEL_INITIATE_D'));
            Configuration::updateValue('FBPIXEL_SEARCH', Tools::getValue('FBPIXEL_SEARCH'));
            Configuration::updateValue('FBPIXEL_ADDTOCART', Tools::getValue('FBPIXEL_ADDTOCART'));
            Configuration::updateValue('FBPIXEL_WISHLIST', Tools::getValue('FBPIXEL_WISHLIST'));
            Configuration::updateValue('FBPIXEL_DPA', Tools::getValue('FBPIXEL_DPA'));
            Configuration::updateValue('FBPIXEL_VCONTENT', Tools::getValue('FBPIXEL_VCONTENT'));
            Configuration::updateValue('FBPIXEL_ATTRID', Tools::getValue('FBPIXEL_ATTRID'));
            Configuration::updateValue('FBPIXEL_REG', Tools::getValue('FBPIXEL_REG'));
            Configuration::updateValue('FBPIXEL_SEPSIGN', Tools::getValue('FBPIXEL_SEPSIGN'));
            Configuration::updateValue('FBPIXEL_EXFREE', Tools::getValue('FBPIXEL_EXFREE'));
            Configuration::updateValue('FBPIXEL_ATC_B', Tools::getValue('FBPIXEL_ATC_B', '.add-to-cart'));
            Configuration::updateValue('FBPIXEL_ATC_PC', Tools::getValue('FBPIXEL_ATC_PC', '.product-container'));
            Configuration::updateValue('FBPIXEL_ATC_PP', Tools::getValue('FBPIXEL_ATC_PP', '.product-price'));
            Configuration::updateValue('FBPIXEL_ATC_PPP', Tools::getValue('FBPIXEL_ATC_PPP', '.current-price span'));
            Configuration::updateValue('FBPIXEL_CURRSELECT', Tools::getValue('FBPIXEL_CURRSELECT'));
            Configuration::updateValue('FBPIXEL_CONCURR', Tools::getValue('FBPIXEL_CONCURR'));

            $prefix = array();
            $sufix = array();
            Foreach (language::getLanguages(false) AS $lang) {
                $prefix[$lang['id_lang']] = Tools::getValue('FBPIXEL_PREFIX_' . $lang['id_lang']);
                $sufix[$lang['id_lang']] = Tools::getValue('FBPIXEL_SUFIX_' . $lang['id_lang']);
            }
            Configuration::updateValue('FBPIXEL_PREFIX', $prefix);
            Configuration::updateValue('FBPIXEL_SUFIX', $sufix);
        }
        return $this->displayConfirmation($this->l('Settings updated'));
    }

    public function displayForm()
    {

        $options = array(
            array(
                'id_option' => 0,
                'name' => 'No'
            ),
            array(
                'id_option' => 1,
                'name' => 'Yes'
            ),
        );

        $options_identification = array(
            array(
                'id_option' => 0,
                'name' => 'id_product'
            ),
            array(
                'id_option' => 1,
                'name' => 'id_attribute'
            ),
            array(
                'id_option' => 2,
                'name' => 'id_product-id_attribute'
            ),
        );

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-wrench'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Your Pixel ID'),
                        'name' => 'FBPIXEL_ID',
                        'desc' => $this->l('Enter here your unique ID of pixel') . ' <a href="https://mypresta.eu/basic-tutorials/new-facebook-pixel-id.html" target="_blank">' . $this->l('Check how to get facebook pixel ID') . '</a>.' . $this->l('You can also add several pixel ID numbers, just separate them by commas like XXXXXXXXXXXXXX, YYYYYYYYYYYYYYY, ZZZZZZZZZZZZZZ')
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Track PageView'),
                        'name' => 'FBPIXEL_PAGEVIEW',
                        'cast' => 'intval',
                        'options' => array(
                            'query' => $options,
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                        'identifier' => 'value',
                        'desc' => $this->l('Select YES if you want to track page views') . ''
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Track account creation'),
                        'name' => 'FBPIXEL_REG',
                        'cast' => 'intval',
                        'options' => array(
                            'query' => $options,
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                        'identifier' => 'value',
                        'desc' => $this->l('Select YES if you want to track page usr register') . ''
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Track ViewContent (products)'),
                        'name' => 'FBPIXEL_VCONTENT',
                        'cast' => 'intval',
                        'options' => array(
                            'query' => $options,
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                        'identifier' => 'value',
                        'desc' => $this->l('Select YES if you want to track ViewContent (product pages)') . ''
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Track Initiate Checkout'),
                        'name' => 'FBPIXEL_INITIATE',
                        'cast' => 'intval',
                        'options' => array(
                            'query' => $options,
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                        'identifier' => 'value',
                        'desc' => $this->l('Select YES if you want to track when people enter the checkout flow (go to cart page)') . ''
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Include details to Initiate Checkout'),
                        'name' => 'FBPIXEL_INITIATE_D',
                        'cast' => 'intval',
                        'options' => array(
                            'query' => $options,
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                        'identifier' => 'value',
                        'desc' => $this->l('Select YES if you want to include details about cart to initiateCheckout event') . ''
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Track Purchase'),
                        'name' => 'FBPIXEL_PURCHASE',
                        'cast' => 'intval',
                        'options' => array(
                            'query' => $options,
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                        'identifier' => 'value',
                        'desc' => $this->l('Select YES if you want to track purchases (order confirmation)') . ''
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Exclude "free" products from purchase event'),
                        'name' => 'FBPIXEL_EXFREE',
                        'cast' => 'intval',
                        'options' => array(
                            'query' => $options,
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                        'identifier' => 'value',
                        'desc' => $this->l('When you allow to order free products - module will not include them to purchase events') . ''
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Track Lead (page view)'),
                        'name' => 'FBPIXEL_LEAD',
                        'cast' => 'intval',
                        'options' => array(
                            'query' => $options,
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                        'identifier' => 'value',
                        'desc' => $this->l('Select YES if you want to track when a user expresses interest in your offering (product page view)') . ''
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Track Lead (newsletter subscription)'),
                        'name' => 'FBPIXEL_LEAD_N',
                        'cast' => 'intval',
                        'options' => array(
                            'query' => $options,
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                        'identifier' => 'value',
                        'desc' => $this->l('Applicable for default newsletter subscription feature. Event is tracked when someone subscribe to newsletter.') . ''
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Track Search'),
                        'name' => 'FBPIXEL_SEARCH',
                        'cast' => 'intval',
                        'options' => array(
                            'query' => $options,
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                        'identifier' => 'value',
                        'desc' => $this->l('Select YES if you want to track searches on your website') . ''
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Add To Cart'),
                        'name' => 'FBPIXEL_ADDTOCART',
                        'cast' => 'intval',
                        'options' => array(
                            'query' => $options,
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                        'identifier' => 'value',
                        'desc' => $this->l('Select YES if you want to track when items are added to a shopping cart. Add to cart button must have class="ajax_add_to_cart_button" or id="add_to_cart" (available by default in PrestaShop)') . ''
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Product page "add to cart" button CSS selector'),
                        'name' => 'FBPIXEL_ATC_B',
                        'desc' => $this->l('CSS selector of add to cart button on product page. Default value commonly used by many themes: .add-to-cart'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('CSS selector of product page product price'),
                        'name' => 'FBPIXEL_ATC_PPP',
                        'desc' => $this->l('Default value of this field is: #our_price_display'),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Product identification'),
                        'name' => 'FBPIXEL_ATTRID',
                        'cast' => 'intval',
                        'options' => array(
                            'query' => $options_identification,
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                        'identifier' => 'value',
                        'desc' => $this->l('Select how you want to identify the products - module will send selected identification variable with tracked events') . ''
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Separate sign'),
                        'name' => 'FBPIXEL_SEPSIGN',
                        'lang' => false,
                        'desc' => $this->l('Define the sign (or string) that will separate id_product from id_attribute (by default dash symbol)')
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Product identification sufix'),
                        'name' => 'FBPIXEL_SUFIX',
                        'lang' => true,
                        'desc' => $this->l('Define sufix - it will be added to product id in tracked events. Leave field empty if you dont want to use it') . ''
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Product identification prefix'),
                        'name' => 'FBPIXEL_PREFIX',
                        'lang' => true,
                        'desc' => $this->l('Define prefix - it will be added to product id in tracked events. Leave field empty if you dont want to use it') . ''
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Add To Wishlist'),
                        'name' => 'FBPIXEL_WISHLIST',
                        'cast' => 'intval',
                        'options' => array(
                            'query' => $options,
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                        'identifier' => 'value',
                        'desc' => $this->l('Select YES if you want to track when items are added to a wishlist. Add to wishlist button must have id="wishlist_button_nopop" (available by default in PrestaShop)') . ''
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Dynamic Pixel Events'),
                        'name' => 'FBPIXEL_DPA',
                        'cast' => 'intval',
                        'options' => array(
                            'query' => $options,
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                        'identifier' => 'value',
                        'desc' => $this->l('Select YES if you want to enable Dynamic Product Ads (DPA). DPA allow advertisers to create single-product or carousel ads that are rendered and targeted based on a set of products.') . '<a href="https://developers.facebook.com/docs/ads-for-websites/pixel-troubleshooting#catalog-pair" target="_blank">' . $this->l('For DPA your pixel must be paired with a product catalog') . '</a>'
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Force currency type'),
                        'name' => 'FBPIXEL_CONCURR',
                        'cast' => 'intval',
                        'options' => array(
                            'query' => $options,
                            'id' => 'id_option',
                            'name' => 'name'
                        ),
                        'identifier' => 'value',
                        'desc' => $this->l('If your shop uses currencies not accepted by Facebook you can convert prices and other details delivered with events to selected currency. To do so - activate this option and select currency') . '</a>'
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Select currency'),
                        'name' => 'FBPIXEL_CURRSELECT',
                        'cast' => 'intval',
                        'options' => array(
                            'query' => Currency::getCurrencies(false, false),
                            'id' => 'id_currency',
                            'name' => 'name'
                        ),
                        'identifier' => 'value',
                        'desc' => $this->l('If your shop uses currencies not accepted by Facebook you can convert prices and other details delivered with events to selected currency') . '</a>'
                    ),
                    array(
                       'type' => 'select',
                       'label' => $this->l('Reformat currency'),
                       'name' => 'FBPIXEL_PREPRICE',
                       'cast' => 'intval',
                       'options' => array(
                           'query' => $options,
                           'id' => 'id_option',
                           'name' => 'name'
                       ),
                       'identifier' => 'value',
                       'desc' => $this->l('IF you use currency format like €XXX,YYY.ZZZ - turn this option to to change it to format accepted by facebook €XXXYYY.ZZZ') . ''
                   ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->id = (int)Tools::getValue('id_carrier');
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'btnSubmit';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));

    }

    public function getConfigFieldsValues()
    {
        $prefix = array();
        $sufix = array();
        Foreach (language::getLanguages(false) AS $lang) {
            $prefix[$lang['id_lang']] = Configuration::get('FBPIXEL_PREFIX', $lang['id_lang']);
            $sufix[$lang['id_lang']] = Configuration::get('FBPIXEL_SUFIX', $lang['id_lang']);
        }

        return array(
            'FBPIXEL_PREPRICE' => Tools::getValue('FBPIXEL_PREPRICE', Configuration::get('FBPIXEL_PREPRICE')),
            'FBPIXEL_ID' => Tools::getValue('FBPIXEL_ID', Configuration::get('FBPIXEL_ID')),
            'FBPIXEL_PAGEVIEW' => Tools::getValue('FBPIXEL_PAGEVIEW', Configuration::get('FBPIXEL_PAGEVIEW')),
            'FBPIXEL_PURCHASE' => Tools::getValue('FBPIXEL_PURCHASE', Configuration::get('FBPIXEL_PURCHASE')),
            'FBPIXEL_LEAD' => Tools::getValue('FBPIXEL_LEAD', Configuration::get('FBPIXEL_LEAD')),
            'FBPIXEL_LEAD_N' => Tools::getValue('FBPIXEL_LEAD_N', Configuration::get('FBPIXEL_LEAD_N')),
            'FBPIXEL_INITIATE' => Tools::getValue('FBPIXEL_INITIATE', Configuration::get('FBPIXEL_INITIATE')),
            'FBPIXEL_INITIATE_D' => Tools::getValue('FBPIXEL_INITIATE_D', Configuration::get('FBPIXEL_INITIATE_D')),
            'FBPIXEL_SEARCH' => Tools::getValue('FBPIXEL_INITIATE', Configuration::get('FBPIXEL_SEARCH')),
            'FBPIXEL_ADDTOCART' => Tools::getValue('FBPIXEL_ADDTOCART', Configuration::get('FBPIXEL_ADDTOCART')),
            'FBPIXEL_ATC_B' => Tools::getValue('FBPIXEL_ATC_B', Configuration::get('FBPIXEL_ATC_B')),
            'FBPIXEL_WISHLIST' => Tools::getValue('FBPIXEL_WISHLIST', Configuration::get('FBPIXEL_WISHLIST')),
            'FBPIXEL_DPA' => Tools::getValue('FBPIXEL_DPA', Configuration::get('FBPIXEL_DPA')),
            'FBPIXEL_VCONTENT' => Tools::getValue('FBPIXEL_VCONTENT', Configuration::get('FBPIXEL_VCONTENT')),
            'FBPIXEL_ATTRID' => Tools::getValue('FBPIXEL_ATTRID', Configuration::get('FBPIXEL_ATTRID')),
            'FBPIXEL_REG' => Tools::getValue('FBPIXEL_REG', Configuration::get('FBPIXEL_REG')),
            'FBPIXEL_SEPSIGN' => Tools::getValue('FBPIXEL_SEPSIGN', Configuration::get('FBPIXEL_SEPSIGN')),
            'FBPIXEL_EXFREE' => Tools::getValue('FBPIXEL_EXFREE', Configuration::get('FBPIXEL_EXFREE')),
            'FBPIXEL_PREFIX' => $prefix,
            'FBPIXEL_SUFIX' => $sufix,
            'FBPIXEL_ATC_PPP' => Tools::getValue('FBPIXEL_ATC_PPP', Configuration::get('FBPIXEL_ATC_PPP')),
            'FBPIXEL_CONCURR' => Tools::getValue('FBPIXEL_CONCURR', Configuration::get('FBPIXEL_CONCURR')),
            'FBPIXEL_CURRSELECT' => Tools::getValue('FBPIXEL_CURRSELECT', Configuration::get('FBPIXEL_CURRSELECT')),
        );
    }

    public function hookFooter($params)
    {
        return $this->hookHeader($params);
    }

    private function getIdProductAttributeByGroup($idp)
    {
        $groups = Tools::getValue('group');
        if (empty($groups)) {
            return null;
        }

        return (int)Product::getIdProductAttributeByIdAttributes(
            $idp,
            $groups,
            true
        );
    }

    private function getIdProductAttributeByGroupOrRequestOrDefault($idp)
    {
        $idProductAttribute = $this->getIdProductAttributeByGroup($idp);
        if (null === $idProductAttribute) {
            $idProductAttribute = (int)Tools::getValue('id_product_attribute');
        }

        if (0 === $idProductAttribute) {
            $idProductAttribute = (int)Product::getDefaultAttribute($idp);
        }

        return $this->tryToGetAvailableIdProductAttribute($idProductAttribute, $idp);
    }

    private function tryToGetAvailableIdProductAttribute($checkedIdProductAttribute, $idp)
    {
        $product = new Product($idp, true, $this->context->language->id);
        if (!Configuration::get('PS_DISP_UNAVAILABLE_ATTR')) {
            $availableProductAttributes = $product->getAttributeCombinations();
            if (!Product::isAvailableWhenOutOfStock($product->out_of_stock)) {
                $availableProductAttributes = array_filter(
                    $availableProductAttributes,
                    function ($elem) {
                        return $elem['quantity'] > 0;
                    }
                );
            }
            $availableProductAttribute = array_filter(
                $availableProductAttributes,
                function ($elem) use ($checkedIdProductAttribute) {
                    return $elem['id_product_attribute'] == $checkedIdProductAttribute;
                }
            );

            if (empty($availableProductAttribute) && count($availableProductAttributes)) {
                return (int)array_shift($availableProductAttributes)['id_product_attribute'];
            }
        }

        return $checkedIdProductAttribute;
    }

    public function assignDetailsBlock()
    {
        $pdminqc = array();
        if (Tools::getValue('id_product') && Tools::getValue('controller') == 'product') {
            $product = new Product(Tools::getValue('id_product'), true, $this->context->language->id);
            $pdminqc['id_product_attribute'] = $this->getIdProductAttributeByGroupOrRequestOrDefault((int)$product->id);
            $pdminqc['id_product'] = $product->id;
            $pdminqc['id'] = $product->id;
            $pdminqc['out_of_stock'] = 0;
            $properties = Product::getProductProperties($this->context->language->id, $pdminqc, $this->context);
            $pdminqc['quantity'] = $properties['quantity'];
            $this->smarty->assign('productFbpixelEmbedded', $pdminqc);
            return $this->display(__file__, 'views/templates/hook/product-details.tpl');
        }
    }

    public function hookHeader($params)
    {
        if (Tools::getValue('fbpixel_recalculate_currency') == 1) {
            $currency_to = new Currency(Tools::getValue('fbpixel_currency_to'));
            $currency_from = new Currency(Tools::getValue('fbpixel_currency_from'));
            echo Tools::convertPriceFull(Tools::getValue('price'), $currency_from, $currency_to);
            return;
        }

        if (Tools::getValue('action') == 'add-to-cart') {
            return;
        }

        $this->context->smarty->assign('FBPIXEL_CONCURR', false);

        if (Tools::getValue('controller') == 'product') {
            if (Tools::getValue('id_product', 'false') != 'false') {
                $product = new Product(Tools::getValue('id_product'), true, $this->context->language->id);
                $this->smarty->assign('fbpixel_product', $product);
            }
        }
        if (isset($this->context->cookie->account_created)) {
            $this->context->smarty->assign('account_created', 1);
        }

        if (Tools::isSubmit('submitNewsletter')) {
            $track_newsletter = $this->newsletterRegistration();
        } else {
            $track_newsletter = false;
        }
        $this->context->smarty->assign('track_newsletter', $track_newsletter);
        $this->context->smarty->assign('prefix', Configuration::get('FBPIXEL_PREFIX', $this->context->language->id));
        $this->context->smarty->assign('sufix', Configuration::get('FBPIXEL_SUFIX', $this->context->language->id));

        if ((Tools::getValue('controller') == 'order' || Tools::getValue('controller') == 'orderopc') && Configuration::get('FBPIXEL_INITIATE_D') == 1) {

            $currency = new Currency($this->context->currency->id);
            $content_ids = '';

            foreach ($this->context->cart->getProducts() AS $key => $value) {

                if (Configuration::get('FBPIXEL_EXFREE') == 1) {
                    if ($value['price_wt'] <= 0) {
                        continue;
                    }
                }

                if (Configuration::get('FBPIXEL_ATTRID') == 1) {
                    for ($x = 0; $x < $value['quantity']; $x++) {
                        $content_ids .= "'" . Configuration::get('FBPIXEL_PREFIX', $this->context->language->id) . "{$value['id_product_attribute']}" . Configuration::get('FBPIXEL_SUFIX', $this->context->language->id) . "',";
                    }
                } else {
                    if (Configuration::get('FBPIXEL_ATTRID') == 2) {
                        for ($x = 0; $x < $value['quantity']; $x++) {
                            $content_ids .= "'" . Configuration::get('FBPIXEL_PREFIX', $this->context->language->id) . "{$value['id_product']}" . ($value['id_product_attribute'] > 0 ? (Configuration::get('FBPIXEL_SEPSIGN', '') . "{$value['id_product_attribute']}") : '') . Configuration::get('FBPIXEL_SUFIX', $this->context->language->id) . "',";
                        }
                    } else {
                        for ($x = 0; $x < $value['quantity']; $x++) {
                            $content_ids .= "'" . Configuration::get('FBPIXEL_PREFIX', $this->context->language->id) . "{$value['id_product']}" . Configuration::get('FBPIXEL_SUFIX', $this->context->language->id) . "',";
                        }
                    }
                }
            }
            $this->context->smarty->assign('content_ids', rtrim($content_ids, ','));
            $this->context->smarty->assign('order_currency_iso_code', $currency->iso_code);
            $this->context->smarty->assign('order_total_paid', number_format($this->context->cart->getOrderTotal(true, Cart::BOTH), 2, ".", ""));
            $this->context->smarty->assign('order_total_products_tax_included', number_format($this->context->cart->getOrderTotal(true, Cart::BOTH), 2, ".", ""));
            $this->context->smarty->assign('order_total_products_tax_excluded', number_format($this->context->cart->getOrderTotal(false, Cart::BOTH), 2, ".", ""));
        }


        $pixel_db = trim(Configuration::get('FBPIXEL_ID'));
        $pixel_explode = explode(',', $pixel_db);
        $pixel_array = array();
        if (is_array($pixel_explode)) {
            if (count($pixel_explode) > 0) {
                foreach ($pixel_explode AS $pixel) {
                    $pixel_array[] = trim($pixel);
                }
            }
        }

        if (count($pixel_array) == 0) {
            $pixel_array[] = $pixel_db;
        }
        $this->context->smarty->assign('fbpixel_id_array', $pixel_array);
        $this->context->controller->addJs(($this->_path) . 'views/js/fbpixel.js', 'all');

        if (Configuration::get('FBPIXEL_CONCURR') == 1) {
            $currency_to = new Currency(Configuration::get('FBPIXEL_CURRSELECT'));
            $currency_from = new Currency($this->context->currency->id);
            $this->context->smarty->assign('currency_to', $currency_to);
            $this->context->smarty->assign('currency_from', $currency_from);
            $this->context->smarty->assign('FBPIXEL_CONCURR', true);
            $this->context->smarty->assign('fbpixel_currency', $currency_to->iso_code);
        } else {
            $this->context->smarty->assign('fbpixel_currency', $this->context->currency->iso_code);
        }

        return $this->display(__FILE__, 'header.tpl');
    }

    public function hookdisplayProductAdditionalInfo()
    {
        return $this->assignDetailsBlock();
    }

    public function newsletterRegistration()
    {
        if (Tools::getValue('email', 'false') == 'false' || !Validate::isEmail(Tools::getValue('email'))) {
            return false;
        }

        if (Tools::getValue('action', 'false') == '0') {
            $register_status = $this->isNewsletterRegistered(Tools::getValue('email'));
            if ($register_status == true) {
                return false;
            } else {
                return true;
            }
        }
    }


    public function isNewsletterRegistered($customer_email)
    {
        $sql = 'SELECT `email`
				FROM ' . _DB_PREFIX_ . 'emailsubscription
				WHERE `email` = \'' . pSQL($customer_email) . '\'
				AND id_shop = ' . $this->context->shop->id;

        if (Db::getInstance()->getRow($sql)) {
            return true;
        }

        $sql = 'SELECT `newsletter`
				FROM ' . _DB_PREFIX_ . 'customer
				WHERE `email` = \'' . pSQL($customer_email) . '\'
				AND id_shop = ' . $this->context->shop->id;

        if ($registered = Db::getInstance()->getRow($sql)) {
            if ($registered['newsletter'] == '1') {
                return true;
            }
        }
        return false;
    }

    public function hookdisplayOrderConfirmation($params)
    {
        if (isset($params['objOrder']) || Tools::getValue('id_order', 'false') != 'false' || Tools::getValue('id_cart', 'false') != false) {
            if (Tools::getValue('id_order', 'false') != false && Tools::getValue('id_order') != false) {
                $id_order = Tools::getValue('id_order');
            } elseif (Tools::getValue('id_cart', 'false') != false && Tools::getValue('id_cart') != false) {
                $id_order = Order::getOrderByCartId(Tools::getValue('id_cart'));
            } elseif (isset($params['objOrder']->id)) {
                $id_order = $params['objOrder']->id;
            } else {
                $id_order = false;
            }

            if ($id_order != false) {
                $order = new Order($id_order);
                $currency = new Currency($order->id_currency);
                $content_ids = '';

                foreach ($order->getProducts() AS $key => $value) {
                    if (Configuration::get('FBPIXEL_EXFREE') == 1) {
                        if ($value['total_price_tax_incl'] <= 0) {
                            continue;
                        }
                    }

                    if (Configuration::get('FBPIXEL_ATTRID') == 1) {
                        for ($x = 0; $x < $value['product_quantity']; $x++) {
                            $content_ids .= "'" . Configuration::get('FBPIXEL_PREFIX', $this->context->language->id) . "{$value['product_attribute_id']}" . Configuration::get('FBPIXEL_SUFIX', $this->context->language->id) . "',";
                        }
                    } else {
                        if (Configuration::get('FBPIXEL_ATTRID') == 2) {
                            for ($x = 0; $x < $value['product_quantity']; $x++) {
                                $content_ids .= "'" . Configuration::get('FBPIXEL_PREFIX', $this->context->language->id) . "{$value['id_product']}" . ($value['id_product_attribute'] > 0 ? (Configuration::get('FBPIXEL_SEPSIGN', '') . "{$value['id_product_attribute']}") : '') . Configuration::get('FBPIXEL_SUFIX', $this->context->language->id) . "',";
                            }
                        } else {
                            for ($x = 0; $x < $value['product_quantity']; $x++) {
                                $content_ids .= "'" . Configuration::get('FBPIXEL_PREFIX', $this->context->language->id) . "{$value['product_id']}" . Configuration::get('FBPIXEL_SUFIX', $this->context->language->id) . "',";
                            }
                        }
                    }
                }

                $this->context->smarty->assign('FBPIXEL_CONCURR', false);
                if (Configuration::get('FBPIXEL_CONCURR') == 1) {
                    $currency_to = new Currency(Configuration::get('FBPIXEL_CURRSELECT'));
                    $currency_from = new Currency($this->context->currency->id);
                    $this->context->smarty->assign('currency_to', $currency_to);
                    $this->context->smarty->assign('currency_from', $currency_from);
                    $this->context->smarty->assign('FBPIXEL_CONCURR', true);
                }

                $this->context->smarty->assign('content_ids', rtrim($content_ids, ','));
                $this->context->smarty->assign('order_currency_iso_code', $currency->iso_code);
                $this->context->smarty->assign('order_total_paid', number_format($order->total_paid, 2, ".", ""));
                $this->context->smarty->assign('order_total_products_tax_included', number_format($order->total_products_wt, 2, ".", ""));
                $this->context->smarty->assign('order_total_products_tax_excluded', number_format($order->total_products, 2, ".", ""));
            }
            $this->context->smarty->assign('order_id', Tools::getValue('id_order'));
        }

        return $this->display(__FILE__, 'displayOrderConfirmation.tpl');
    }
}

class fbpixelUpdate extends fbpixel
{
    public static function version($version)
    {
        $version = (int)str_replace(".", "", $version);
        if (strlen($version) == 3) {
            $version = (int)$version . "0";
        }
        if (strlen($version) == 2) {
            $version = (int)$version . "00";
        }
        if (strlen($version) == 1) {
            $version = (int)$version . "000";
        }
        if (strlen($version) == 0) {
            $version = (int)$version . "0000";
        }
        return (int)$version;
    }

    public static function encrypt($string)
    {
        return base64_encode($string);
    }

    public static function verify($module, $key, $version)
    {
        if (ini_get("allow_url_fopen")) {
            if (function_exists("file_get_contents")) {
                $actual_version = @file_get_contents('http://dev.mypresta.eu/update/get.php?module=' . $module . "&version=" . self::encrypt($version) . "&lic=$key&u=" . self::encrypt(_PS_BASE_URL_ . __PS_BASE_URI__));
            }
        }
        Configuration::updateValue("update_" . $module, date("U"));
        Configuration::updateValue("updatev_" . $module, $actual_version);
        return $actual_version;
    }
}

?>
