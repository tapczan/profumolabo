<?php
/**
 * 2015-2021 Bonpresta
 *
 * Bonpresta Instagram Carousel Social Feed Photos
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/GPL-2.0
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the module to newer
 * versions in the future.
 *
 *  @author    Bonpresta
 *  @copyright 2015-2021 Bonpresta
 *  @license   http://opensource.org/licenses/GPL-2.0 General Public License (GPL 2.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Boninstagramcarousel extends Module
{
    public function __construct()
    {
        $this->name = 'boninstagramcarousel';
        $this->tab = 'front_office_features';
        $this->version = '5.2.3';
        $this->bootstrap = true;
        $this->author = 'Bonpresta';
        $this->module_key = 'f64b0da8f61d880055728934d4c92909';
        parent::__construct();
        $this->displayName = $this->l('Instagram Carousel Social Feed Photos');
        $this->description = $this->l('Display instagram carousel feed photos');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->controllers = array(
            'instagram'
        );
    }

    protected function getModuleSettings()
    {
        $res = array(
            'BONINSTAGRAMCAROUSEL_DISPLAY' => true,
            'BONINSTAGRAMCAROUSEL_USERID' => 'prestashop',
            'BONINSTAGRAMCAROUSEL_TYPE' => 'user',
            'BONINSTAGRAMCAROUSEL_TAG' => '',
            'BONINSTAGRAMCAROUSEL_LIMIT' => 8,
            'BONINSTAGRAMCAROUSEL_DISPLAY_CAROUSEL' => false,
            'BONINSTAGRAMCAROUSEL_NB' => 4,
            'BONINSTAGRAMCAROUSEL_SPEED' => 5000,
            'BONINSTAGRAMCAROUSEL_MARGIN' => 20,
            'BONINSTAGRAMCAROUSEL_LOOP' => true,
            'BONINSTAGRAMCAROUSEL_NAV' => true,
            'BONINSTAGRAMCAROUSEL_DOTS' => false,
        );

        return $res;
    }

    public function install()
    {
        $settings = $this->getModuleSettings();

        foreach ($settings as $name => $value) {
            Configuration::updateValue($name, $value);
        }

        return parent::install() &&
        $this->registerHook('displayHeader') &&
        $this->registerHook('displayBackOfficeHeader') &&
        $this->registerHook('moduleRoutes') &&
            $this->registerHook('displayInstagram') &&
        $this->registerHook('displayHome');
    }

    public function hookModuleRoutes()
    {
        return array(
            'module-boninstagramcarousel-instagram' => array(
                'controller' => 'instagram',
                'rule'       => 'instagram',
                'keywords'   => array(),
                'params'     => array(
                    'fc'     => 'module',
                    'module' => 'boninstagramcarousel',
                ),
            ),
        );
    }

    public function uninstall()
    {
        $settings = $this->getModuleSettings();

        foreach (array_keys($settings) as $name) {
            Configuration::deleteByName($name);
        }

        return parent::uninstall();
    }

    public function getContent()
    {
        $output = '';

        if ((bool)Tools::isSubmit('submitSettings')) {
            if (!$errors = $this->checkItemFields()) {
                $this->postProcess();
                $output .= $this->displayConfirmation($this->l('Save all settings.'));
            } else {
                $output .= $errors;
            }
        }

        return $this->display(__FILE__, 'views/templates/admin/upload.tpl') . $output.$this->renderForm();
    }

    protected function postProcess()
    {
        $form_values = $this->getConfigFieldsValues();
        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    protected function checkItemFields()
    {
        $errors = array();

        if (Tools::isEmpty(Tools::getValue('BONINSTAGRAMCAROUSEL_LIMIT'))) {
            $errors[] = $this->l('Limit is required.');
        } else {
            if (!Validate::isUnsignedInt(Tools::getValue('BONINSTAGRAMCAROUSEL_LIMIT'))) {
                $errors[] = $this->l('Bad limit format');
            }
        }

        if (Tools::isEmpty(Tools::getValue('BONINSTAGRAMCAROUSEL_NB'))) {
            $errors[] = $this->l('Item is required.');
        } else {
            if (!Validate::isUnsignedInt(Tools::getValue('BONINSTAGRAMCAROUSEL_NB'))) {
                $errors[] = $this->l('Bad item format');
            }
        }

        if (Tools::isEmpty(Tools::getValue('BONINSTAGRAMCAROUSEL_MARGIN'))) {
            $errors[] = $this->l('Autoplay Speed is required.');
        } else {
            if (!Validate::isUnsignedInt(Tools::getValue('BONINSTAGRAMCAROUSEL_MARGIN'))) {
                $errors[] = $this->l('Bad autoplay speed format');
            }
        }

        if (count($errors)) {
            return $this->displayError(implode('<br />', $errors));
        }

        return false;
    }

    protected function getConfigInstagram()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings Instagram'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable Instagram Feed'),
                        'name' => 'BONINSTAGRAMCAROUSEL_DISPLAY',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'enable',
                                'value' => 1,
                                'label' => $this->l('Yes')),
                            array(
                                'id' => 'disable',
                                'value' => 0,
                                'label' => $this->l('No')),
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Display item'),
                        'name' => 'BONINSTAGRAMCAROUSEL_LIMIT',
                        'col' => 2,
                        'required' => true,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Get Feeds by'),
                        'name' => 'BONINSTAGRAMCAROUSEL_TYPE',
                        'options' => array(
                            'query' => array(
                                array(
                                    'id' => 'tagged',
                                    'name' => $this->l('tagged')),
                                array(
                                    'id' => 'user',
                                    'name' => $this->l('user')),
                            ),
                            'id' => 'id',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Instagram Tag'),
                        'name' => 'BONINSTAGRAMCAROUSEL_TAG',
                        'col' => 2,
                        'required' => false,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Instagram User'),
                        'name' => 'BONINSTAGRAMCAROUSEL_USERID',
                        'col' => 2,
                        'required' => false,
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Carousel:'),
                        'name' => 'BONINSTAGRAMCAROUSEL_DISPLAY_CAROUSEL',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'form_group_class' => 'display',
                        'type' => 'text',
                        'required' => true,
                        'label' => $this->l('Number of items in the carousel:'),
                        'name' => 'BONINSTAGRAMCAROUSEL_NB',
                        'col' => 2,
                        'desc' => $this->l('The number of items you want to see on the screen.'),
                    ),
                    array(
                        'form_group_class' => 'display',
                        'type' => 'text',
                        'required' => true,
                        'label' => $this->l('Autoplay Speed:'),
                        'name' => 'BONINSTAGRAMCAROUSEL_SPEED',
                        'col' => 2,
                        'suffix' => 'milliseconds',
                    ),
                    array(
                        'form_group_class' => 'display',
                        'type' => 'text',
                        'label' => $this->l('Indent between pictures:'),
                        'name' => 'BONINSTAGRAMCAROUSEL_MARGIN',
                        'suffix' => 'pixels',
                        'col' => 2,
                    ),
                    array(
                        'form_group_class' => 'display',
                        'type' => 'switch',
                        'label' => $this->l('Infinite:'),
                        'name' => 'BONINSTAGRAMCAROUSEL_LOOP',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'form_group_class' => 'display',
                        'type' => 'switch',
                        'label' => $this->l('Navigation:'),
                        'name' => 'BONINSTAGRAMCAROUSEL_NAV',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'form_group_class' => 'display',
                        'type' => 'switch',
                        'label' => $this->l('Pagination:'),
                        'name' => 'BONINSTAGRAMCAROUSEL_DOTS',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    public function renderForm()
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitSettings';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).
            '&configure='.$this->name.
            '&tab_module='.$this->tab.
            '&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($this->getConfigInstagram()));
    }


    protected function getConfigFieldsValues()
    {
        $filled_settings = array();
        $settings = $this->getModuleSettings();

        foreach (array_keys($settings) as $name) {
            $filled_settings[$name] = Configuration::get($name);
        }

        return $filled_settings;
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') != $this->name) {
            return;
        }
        $this->context->controller->addJquery();
        $this->context->controller->addJS($this->_path.'views/js/boninstagramcarousel_admin.js');
        $this->context->controller->addCSS($this->_path.'views/css/boninstagram-back.css');
        Media::addJsDefL('base_dir', $this->_path);
        Media::addJsDefL('user_id', Configuration::get('BONINSTAGRAMCAROUSEL_USERID'));
        Media::addJsDefL('BONINSTAGRAMCAROUSEL_TYPE', Configuration::get('BONINSTAGRAMCAROUSEL_TYPE'));
        Media::addJsDefL('BONINSTAGRAMCAROUSEL_LIMIT', Configuration::get('BONINSTAGRAMCAROUSEL_LIMIT'));
        Media::addJsDefL('user_tag', Configuration::get('BONINSTAGRAMCAROUSEL_TAG'));
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS($this->_path.'views/css/boninstagramcarousel.css', 'all');

        if (Configuration::get('BONINSTAGRAMCAROUSEL_DISPLAY_CAROUSEL')) {
            $this->context->controller->addJS($this->_path.'views/js/owl.carousel.js');
            $this->context->controller->addJS($this->_path.'views/js/owl.carousel-front.js');
            $this->context->controller->addCSS($this->_path.'views/css/owl.carousel.css', 'all');
            $this->context->controller->addCSS($this->_path.'views/css/owl.theme.default.css', 'all');
            $this->context->controller->addCSS($this->_path.'views/css/boninstagramcarousel.css', 'all');
            $var = 'BONINSTAGRAMCAROUSEL_DISPLAY_CAROUSEL';
            Media::addJsDefL('BONINSTAGRAMCAROUSEL_DISPLAY_CAROUSEL', Configuration::get($var));
            Media::addJsDefL('BONINSTAGRAMCAROUSEL_DISPLAY', Configuration::get('BONINSTAGRAMCAROUSEL_DISPLAY'));
            Media::addJsDefL('BONINSTAGRAMCAROUSEL_NB', Configuration::get('BONINSTAGRAMCAROUSEL_NB'));
            Media::addJsDefL('BONINSTAGRAMCAROUSEL_SPEED', Configuration::get('BONINSTAGRAMCAROUSEL_SPEED'));
            Media::addJsDefL('BONINSTAGRAMCAROUSEL_MARGIN', Configuration::get('BONINSTAGRAMCAROUSEL_MARGIN'));
            Media::addJsDefL('BONINSTAGRAMCAROUSEL_LOOP', Configuration::get('BONINSTAGRAMCAROUSEL_LOOP'));
            Media::addJsDefL('BONINSTAGRAMCAROUSEL_NAV', Configuration::get('BONINSTAGRAMCAROUSEL_NAV'));
            Media::addJsDefL('BONINSTAGRAMCAROUSEL_DOTS', Configuration::get('BONINSTAGRAMCAROUSEL_DOTS'));
        }
    }


    protected function getStringValueType($data)
    {
        if (Validate::isInt($data)) {
            return 'int';
        } elseif (Validate::isFloat($data)) {
            return 'float';
        } elseif (Validate::isBool($data)) {
            return 'bool';
        } else {
            return 'string';
        }
    }

    protected function getBlankSettings()
    {
        $settings = $this->getModuleSettings();
        $get_settings = array();

        foreach (array_keys($settings) as $name) {
            $data = Configuration::get($name);
            $get_settings[$name] = array('value' => $data, 'type' => $this->getStringValueType($data));
        }

        return $get_settings;
    }

   
    public function hookDisplayHome()
    {
        if (Configuration::get('BONINSTAGRAMCAROUSEL_DISPLAY')) {
            $this->context->smarty->assign('instagram_type', Configuration::get('BONINSTAGRAMCAROUSEL_TYPE'));
            $this->context->smarty->assign('limit', Configuration::get('BONINSTAGRAMCAROUSEL_LIMIT'));
            $this->context->smarty->assign('user_id', Configuration::get('BONINSTAGRAMCAROUSEL_USERID'));
            $this->context->smarty->assign('user_tag', Configuration::get('BONINSTAGRAMCAROUSEL_TAG'));
            $this->context->smarty->assign('baseurl', $this->_path.'views/');

            $this->context->smarty->assign(
                array(
                    'display_caroucel' => Configuration::get('BONINSTAGRAMCAROUSEL_DISPLAY_CAROUSEL')
                )
            );
            return $this->display(__FILE__, 'boninstagramcarousel.tpl');
        }
    }

    public function hookdisplayInstagram()
    {
        // return $this->hookDisplayHome();

        $this->context->smarty->assign('limit', Configuration::get('BONINSTAGRAMCAROUSEL_LIMIT'));
        $this->context->smarty->assign('instagram_type', Configuration::get('BONINSTAGRAMCAROUSEL_TYPE'));
        $this->context->smarty->assign('display_caroucel', Configuration::get('BONINSTAGRAMCAROUSEL_DISPLAY_CAROUSEL'));
        $this->context->smarty->assign('user_tag', Configuration::get('BONINSTAGRAMCAROUSEL_TAG'));
        $this->context->smarty->assign('user_id', Configuration::get('BONINSTAGRAMCAROUSEL_USERID'));
        
        return $this->display(__FILE__, '../../themes/profumo-labo/modules/boninstagramcarousel/views/templates/hooks/ps_instagram.tpl');
    }

    public function hookdisplayFooterBefore()
    {
        return $this->hookDisplayHome();
    }
}
