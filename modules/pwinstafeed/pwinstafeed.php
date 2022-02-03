<?php

/*=============================================================================*/
/*  PW Instagram feed
/*  ---
/*  PRESTAWORKS AB (www.prestaworks.se)
/*=============================================================================*/

if (!defined('_CAN_LOAD_FILES_'))
    exit;

class pwinstafeed extends Module
{

    public function __construct()
    {
        $this->name = 'pwinstafeed';
        $this->tab = 'front_office_features';
        $this->version = '3.5';
        $this->author = 'Prestaworks AB';

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('PW Instagram feed');
        $this->description = $this->l('An Instagram feed for your shop including a custom page, carousel, independent hook settings and more.');
        $this->ps_versions_compliancy = array('min' => '1.7.2.4', 'max' => _PS_VERSION_);

        $path = dirname(__FILE__);
        include_once $path.'/pwinstafeedClass.php';
    }

    private $_html;
    public $fields_form;

    public static $customTabs = array(
        array('id'  => '0', 'name' => 'Instagram setup', 'icon' => 'icon-instagram'),
        array('id'  => '1', 'name' => 'Feed settings', 'icon' => 'icon-cog'),
        array('id'  => '2', 'name' => 'Custom page', 'icon' => 'icon-file-o'),
    );

    public function setMeta($name)
    {
        $metas = array();
        $sql = "SELECT id_meta FROM `"._DB_PREFIX_."meta` WHERE page='$name'";
        $id_meta = Db::getInstance()->getValue($sql);
        if ((int)$id_meta==0) {
            $meta = new Meta();
            $meta->page = $name;
            $meta->configurable = 1;
            $meta->add();

            $metas['id_meta'] = (int)$meta->id;
            $metas['left'] = 0;
            $metas['right'] = 0;
        } else {
            $metas['id_meta'] = (int)$id_meta;
            $metas['left'] = 0;
            $metas['right'] = 0;
        }
        return $metas;
    }

    public function install()
    {
        $pwdatabase = Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'pwinstafeed` (
            `id_pwinstafeed` int(10) unsigned NOT NULL auto_increment,
            `id_shop` int(10) unsigned NOT NULL ,
            `pwinstafeed_pagelimit` int(10) NOT NULL,
            `pwinstafeed_pagegrid_xs` int(10) NOT NULL,
            `pwinstafeed_pagegrid_sm` int(10) NOT NULL,
            `pwinstafeed_pagegrid_md` int(10) NOT NULL,
            `pwinstafeed_pagegrid_lg` int(10) NOT NULL,
            `pwinstafeed_pagegrid_xl` int(10) NOT NULL,
            `pwinstafeed_grid_xs` int(10) NOT NULL,
            `pwinstafeed_grid_sm` int(10) NOT NULL,
            `pwinstafeed_grid_md` int(10) NOT NULL,
            `pwinstafeed_grid_lg` int(10) NOT NULL,
            `pwinstafeed_grid_xl` int(10) NOT NULL,
            `pwinstafeed_pagemodal` tinyint(4) NOT NULL,
            `pwinstafeed_pagelikes` tinyint(4) NOT NULL,
            `pwinstafeed_pagecomments` tinyint(4) NOT NULL,
            `pwinstafeed_pagestyle` text NOT NULL,
            `pwinstafeed_bgcolor` text NOT NULL,
            `pwinstafeed_fgcolor` text NOT NULL,
            `pwinstafeed_pagebgcolor` text NOT NULL,
            `pwinstafeed_pagefgcolor` text NOT NULL,
            `pwinstafeed_pagebtnbgcolor` text NOT NULL,
            `pwinstafeed_pagebtnfgcolor` text NOT NULL,
            `pwinstafeed_pagespacing` text NOT NULL,
            PRIMARY KEY (`id_pwinstafeed`))
            ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8'
        );
        if ($pwdatabase == false) {
            return false;
        }
        $pwdatabase = Db::getInstance()->execute(
            'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'pwinstafeed_lang` (
            `id_pwinstafeed` int(10) unsigned NOT NULL,
            `id_lang` int(10) unsigned NOT NULL,
            `pwinstafeed_pagetitle` varchar(255) NOT NULL,
            `pwinstafeed_pagebreadcrumb` varchar(255) NOT NULL,
            `pwinstafeed_pagecontent` text NOT NULL,
            PRIMARY KEY (`id_pwinstafeed`, `id_lang`))
            ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8'
        );
        if ($pwdatabase == false) {
            return false;
        }

        return (parent::install() &&
            Configuration::updateValue('pwinstafeed_accesstoken', '') &&
            Configuration::updateValue('pwinstafeed_limit', '16') &&
            Configuration::updateValue('pwinstafeed_items1', '4') &&
            Configuration::updateValue('pwinstafeed_items2', '5') &&
            Configuration::updateValue('pwinstafeed_items3', '6') &&
            Configuration::updateValue('pwinstafeed_items4', '7') &&
            Configuration::updateValue('pwinstafeed_items5', '8') &&
            Configuration::updateValue('pwinstafeed_hook', '1') &&
            Configuration::updateValue('pwinstafeed_style', 'rounded') &&
            Configuration::updateValue('pwinstafeed_carousel', '1') &&
            Configuration::updateValue('pwinstafeed_infinite', '1') &&
            Configuration::updateValue('pwinstafeed_modal', '1') &&
            Configuration::updateValue('pwinstafeed_likes', '1') &&
            Configuration::updateValue('pwinstafeed_comments', '1') &&
            Configuration::updateValue('pwinstafeed_pagelimit', '12') &&
            Configuration::updateValue('pwinstafeed_pagegrid_xs', '12') &&
            Configuration::updateValue('pwinstafeed_pagegrid_sm', '6') &&
            Configuration::updateValue('pwinstafeed_pagegrid_md', '4') &&
            Configuration::updateValue('pwinstafeed_pagegrid_lg', '3') &&
            Configuration::updateValue('pwinstafeed_pagegrid_xl', '2') &&
            Configuration::updateValue('pwinstafeed_grid_xs', '12') &&
            Configuration::updateValue('pwinstafeed_grid_sm', '6') &&
            Configuration::updateValue('pwinstafeed_grid_md', '4') &&
            Configuration::updateValue('pwinstafeed_grid_lg', '3') &&
            Configuration::updateValue('pwinstafeed_grid_xl', '2') &&
            Configuration::updateValue('pwinstafeed_pagespacing', '30') &&
            Configuration::updateValue('pwinstafeed_spacing', '30') &&
            Configuration::updateValue('pwinstafeed_pagemodal', true) &&
            Configuration::updateValue('pwinstafeed_pagelikes', true) &&
            Configuration::updateValue('pwinstafeed_pagecomments', true) &&
            Configuration::updateValue('pwinstafeed_pagestyle', 'rounded') &&
            Configuration::updateValue('pwinstafeed_pagebgcolor', 'rgba(0, 0, 0, 0.5)') &&
            Configuration::updateValue('pwinstafeed_pagefgcolor', '#ffffff') &&
            Configuration::updateValue('pwinstafeed_bgcolor', 'rgba(0, 0, 0, 0.5)') &&
            Configuration::updateValue('pwinstafeed_fgcolor', '#ffffff') &&
            Configuration::updateValue('pwinstafeed_btnbgcolor', '#333333') &&
            Configuration::updateValue('pwinstafeed_btnfgcolor', '#ffffff') &&
            Configuration::updateValue('pwinstafeed_pagebtnbgcolor', '#1aafd0') &&
            Configuration::updateValue('pwinstafeed_pagebtnfgcolor', '#ffffff') &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('displayFooter') &&
            $this->registerHook('displayLeftColumn') &&
            $this->registerHook('displayRightColumn') &&
            $this->registerHook('displayHome')
        );
    }

    public function uninstall()
    {
        $pwdatabase = Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'pwinstafeed`');
        if ($pwdatabase == false) {return false;}
        $pwdatabase = Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'pwinstafeed_lang`');
        if ($pwdatabase == false) {return false;}

        return (
            Configuration::deleteByName('pwinstafeed_accesstoken') &&
            Configuration::deleteByName('pwinstafeed_limit') &&
            Configuration::deleteByName('pwinstafeed_items1') &&
            Configuration::deleteByName('pwinstafeed_items2') &&
            Configuration::deleteByName('pwinstafeed_items3') &&
            Configuration::deleteByName('pwinstafeed_items4') &&
            Configuration::deleteByName('pwinstafeed_items5') &&
            Configuration::deleteByName('pwinstafeed_hook') &&
            Configuration::deleteByName('pwinstafeed_style') &&
            Configuration::deleteByName('pwinstafeed_carousel') &&
            Configuration::deleteByName('pwinstafeed_infinite') &&
            Configuration::deleteByName('pwinstafeed_modal') &&
            Configuration::deleteByName('pwinstafeed_likes') &&
            Configuration::deleteByName('pwinstafeed_comments') &&
            Configuration::deleteByName('pwinstafeed_pagelimit') &&
            Configuration::deleteByName('pwinstafeed_pagegrid_xs') &&
            Configuration::deleteByName('pwinstafeed_pagegrid_sm') &&
            Configuration::deleteByName('pwinstafeed_pagegrid_md') &&
            Configuration::deleteByName('pwinstafeed_pagegrid_lg') &&
            Configuration::deleteByName('pwinstafeed_pagegrid_xl') &&
            Configuration::deleteByName('pwinstafeed_grid_xs') &&
            Configuration::deleteByName('pwinstafeed_grid_sm') &&
            Configuration::deleteByName('pwinstafeed_grid_md') &&
            Configuration::deleteByName('pwinstafeed_grid_lg') &&
            Configuration::deleteByName('pwinstafeed_grid_xl') &&
            Configuration::deleteByName('pwinstafeed_pagespacing') &&
            Configuration::deleteByName('pwinstafeed_spacing') &&
            Configuration::deleteByName('pwinstafeed_pagemodal') &&
            Configuration::deleteByName('pwinstafeed_pagelikes') &&
            Configuration::deleteByName('pwinstafeed_pagecomments') &&
            Configuration::deleteByName('pwinstafeed_pagestyle') &&
            Configuration::deleteByName('pwinstafeed_pagebgcolor') &&
            Configuration::deleteByName('pwinstafeed_pagefgcolor') &&
            Configuration::deleteByName('pwinstafeed_bgcolor') &&
            Configuration::deleteByName('pwinstafeed_fgcolor') &&
            Configuration::deleteByName('pwinstafeed_btnbgcolor') &&
            Configuration::deleteByName('pwinstafeed_btnfgcolor') &&
            Configuration::deleteByName('pwinstafeed_pagebtnbgcolor') &&
            Configuration::deleteByName('pwinstafeed_pagebtnfgcolor') &&
            parent::uninstall()
        );
    }

    public function getContent()
    {
        if (Tools::isSubmit('submitModule'))
        {
            $id_shop = (int)$this->context->shop->id;
            $pwinstafeed_languages = pwinstafeedClass::getByIdShop($id_shop);
            $pwinstafeed_languages->copyFromPost();
            if (empty($pwinstafeed_languages->id_shop))
                $pwinstafeed_languages->id_shop = (int)$id_shop;
            $pwinstafeed_languages->save();

            Configuration::updateValue('pwinstafeed_accesstoken', Tools::getValue('pwinstafeed_accesstoken', ''));
            Configuration::updateValue('pwinstafeed_limit', (int)Tools::getValue('pwinstafeed_limit', ''));
            Configuration::updateValue('pwinstafeed_items1', (int)Tools::getValue('pwinstafeed_items1', ''));
            Configuration::updateValue('pwinstafeed_items2', (int)Tools::getValue('pwinstafeed_items2', ''));
            Configuration::updateValue('pwinstafeed_items3', (int)Tools::getValue('pwinstafeed_items3', ''));
            Configuration::updateValue('pwinstafeed_items4', (int)Tools::getValue('pwinstafeed_items4', ''));
            Configuration::updateValue('pwinstafeed_items5', (int)Tools::getValue('pwinstafeed_items5', ''));
            Configuration::updateValue('pwinstafeed_hook', Tools::getValue('pwinstafeed_hook', ''));
            Configuration::updateValue('pwinstafeed_style', Tools::getValue('pwinstafeed_style', ''));
            Configuration::updateValue('pwinstafeed_carousel', (int)Tools::getValue('pwinstafeed_carousel', ''));
            Configuration::updateValue('pwinstafeed_infinite', (bool)Tools::getValue('pwinstafeed_infinite', ''));
            Configuration::updateValue('pwinstafeed_modal', (int)Tools::getValue('pwinstafeed_modal', ''));
            Configuration::updateValue('pwinstafeed_likes', (int)Tools::getValue('pwinstafeed_likes', ''));
            Configuration::updateValue('pwinstafeed_comments', (int)Tools::getValue('pwinstafeed_comments', ''));
            Configuration::updateValue('pwinstafeed_pagelimit', (int)Tools::getValue('pwinstafeed_pagelimit', ''));
            Configuration::updateValue('pwinstafeed_pagegrid_xs', Tools::getValue('pwinstafeed_pagegrid_xs', ''));
            Configuration::updateValue('pwinstafeed_pagegrid_sm', Tools::getValue('pwinstafeed_pagegrid_sm', ''));
            Configuration::updateValue('pwinstafeed_pagegrid_md', Tools::getValue('pwinstafeed_pagegrid_md', ''));
            Configuration::updateValue('pwinstafeed_pagegrid_lg', Tools::getValue('pwinstafeed_pagegrid_lg', ''));
            Configuration::updateValue('pwinstafeed_pagegrid_xl', Tools::getValue('pwinstafeed_pagegrid_xl', ''));
            Configuration::updateValue('pwinstafeed_grid_xs', Tools::getValue('pwinstafeed_grid_xs', ''));
            Configuration::updateValue('pwinstafeed_grid_sm', Tools::getValue('pwinstafeed_grid_sm', ''));
            Configuration::updateValue('pwinstafeed_grid_md', Tools::getValue('pwinstafeed_grid_md', ''));
            Configuration::updateValue('pwinstafeed_grid_lg', Tools::getValue('pwinstafeed_grid_lg', ''));
            Configuration::updateValue('pwinstafeed_grid_xl', Tools::getValue('pwinstafeed_grid_xl', ''));
            Configuration::updateValue('pwinstafeed_pagespacing', Tools::getValue('pwinstafeed_pagespacing', ''));
            Configuration::updateValue('pwinstafeed_spacing', Tools::getValue('pwinstafeed_spacing', ''));
            Configuration::updateValue('pwinstafeed_pagemodal', (bool)Tools::getValue('pwinstafeed_pagemodal', ''));
            Configuration::updateValue('pwinstafeed_pagelikes', (bool)Tools::getValue('pwinstafeed_pagelikes', ''));
            Configuration::updateValue('pwinstafeed_pagecomments', (bool)Tools::getValue('pwinstafeed_pagecomments', ''));
            Configuration::updateValue('pwinstafeed_pagestyle', Tools::getValue('pwinstafeed_pagestyle', ''));
            Configuration::updateValue('pwinstafeed_pagebgcolor', Tools::getValue('pwinstafeed_pagebgcolor', ''));
            Configuration::updateValue('pwinstafeed_pagefgcolor', Tools::getValue('pwinstafeed_pagefgcolor', ''));
            Configuration::updateValue('pwinstafeed_bgcolor', Tools::getValue('pwinstafeed_bgcolor', ''));
            Configuration::updateValue('pwinstafeed_fgcolor', Tools::getValue('pwinstafeed_fgcolor', ''));
            Configuration::updateValue('pwinstafeed_btnbgcolor', Tools::getValue('pwinstafeed_btnbgcolor', ''));
            Configuration::updateValue('pwinstafeed_btnfgcolor', Tools::getValue('pwinstafeed_btnfgcolor', ''));
            Configuration::updateValue('pwinstafeed_pagebtnbgcolor', Tools::getValue('pwinstafeed_pagebtnbgcolor', ''));
            Configuration::updateValue('pwinstafeed_pagebtnfgcolor', Tools::getValue('pwinstafeed_pagebtnfgcolor', ''));
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules').'&configure='.$this->name.'&tab_module='.$this->tab.'&conf=4&module_name='.$this->name);
        }

        $this->context->controller->addCSS(($this->_path).'views/admin/css/admin.css');
        $this->_html .= '
            <script type="text/javascript">
                var pwi_base_uri = "'.__PS_BASE_URI__.'";
                var pwi_refer = "'.(int)Tools::getValue('ref').'";
            </script>
        ';
        $this->context->controller->addJS(($this->_path).'views/admin/js/admin.js');

        $this->initFieldsForm();
        $helper = $this->initForm();

        $this->context->smarty->assign(array(
            'pwinstatabs' => $this->initTab(),
            'pwinstaform' => $helper->generateForm($this->fields_form),
        ));
        return $this->_html.$this->display(__FILE__, 'views/templates/admin/documentation.tpl').$this->display(__FILE__, 'views/templates/admin/configuration.tpl');
    }

    public function initTab()
    {

        $html = '<div class="sidebar col-xs-12 col-lg-2"><ul class="nav nav-tabs">';
        foreach(self::$customTabs AS $tab) {
            $html .= '<li class="nav-item"><a href="javascript:;" title="'.$this->l($tab['name']).'" data-fieldset="'.$tab['id'].'"><i class="'.$this->l($tab['icon']).'"></i> '.$this->l($tab['name']).'</a></li>';
        }
        $html .= '</ul></div>';
        return $html;
    }

    public function initFieldsForm()
    {
        $this->context->smarty->assign(array(
            'pwtokenurl' => 'http://demo.prestaworks.com/modules/pwinstafeed/',
            'pwaccesstoken' => Configuration::get('pwinstafeed_accesstoken'),
        ));
        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Instagram setup'),
                'icon' => 'icon-instagram',
            ),
            'input' => array(
                array(
                    'type' => 'html',
                    'label' => '',
                    'name' => '',
                    'desc' => '<strong class="settings-header">'.$this->l('Documentation').'</strong>'
                ),
                array(
                    'type' => 'html',
                    'label' => '<a href="#" class="btn btn-default" data-toggle="modal" data-target="#pwi-documentation">'.$this->l('Documentation').'</a>',
                    'desc' => $this->l('Please take a few moments to look through the documentation which explains the steps needed to set the module up.'),
                    'name' => '',
                ),
                array(
                    'type' => 'html',
                    'label' => '',
                    'desc' => $this->display(__FILE__, 'views/templates/admin/getAccessToken.tpl'),
                    'name' => '',
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Access token'),
                    'name' => 'pwinstafeed_accesstoken',
                    'required' => true,
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save all'),
            ),
        );
        $this->fields_form[1]['form'] = array(
            'legend' => array(
                'title' => $this->l('Feed settings'),
                'icon' => 'icon-cog',
            ),
            'input' => array(
                array(
                    'type' => 'html',
                    'label' => '',
                    'name' => '',
                    'desc' => '<strong class="settings-header">'.$this->l('Basic settings').'</strong>'
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Number of photos'),
                    'name' => 'pwinstafeed_limit',
                    'prefix' => '<i class="icon-image"></i>',
                    'hint' => $this->l('The limit for a fetch from API is 20 photos, so any number higher than that will automatically return 20 photos'),
                    'class' => 'fixed-width-lg',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Feed style'),
                    'name' => 'pwinstafeed_style',
                    'hint' => $this->l('Which style to apply to the module and its controls'),
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 'square',
                                'name' => $this->l('Square')),
                            array(
                                'id' => 'rounded',
                                'name' => $this->l('Rounded')),
                            array(
                                'id' => 'circle',
                                'name' => $this->l('Circle')),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Spacing'),
                    'name' => 'pwinstafeed_spacing',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 0,
                                'name' => $this->l('No spacing')),
                            array(
                                'id' => 10,
                                'name' => $this->l('10px')),
                            array(
                                'id' => 20,
                                'name' => $this->l('20px')),
                            array(
                                'id' => 30,
                                'name' => $this->l('30px')),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'html',
                    'label' => '',
                    'name' => '',
                    'desc' => '<strong class="settings-header">'.$this->l('Carousel settings').'</strong>'
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Carousel'),
                    'name' => 'pwinstafeed_carousel',
                    'hint' => $this->l('Only if the number of fetched photos are more than 8. "Left column" or "Right column" hook positions will not use this feature.'),
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Carousel infinite loop'),
                    'name' => 'pwinstafeed_infinite',
                    'hint' => $this->l('If you wish to have an infinite loop in the carousel'),
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Next & previous background color'),
                    'name' => 'pwinstafeed_btnbgcolor',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Next & previous icon color'),
                    'name' => 'pwinstafeed_btnfgcolor',
                ),
                array(
                    'type' => 'html',
                    'label' => '',
                    'name' => '',
                    'desc' => '<strong class="settings-subheader">'.$this->l('How many photos to show in different breakpoints').'</strong>'
                ),
                array(
                    'type' => 'text',
                    'name' => 'pwinstafeed_items1',
                    'label' => $this->l('Extra small').' - 0 →',
                    'class' => 'fixed-width-lg',
                ),
                array(
                    'type' => 'text',
                    'name' => 'pwinstafeed_items2',
                    'label' => $this->l('Small').' - 480px →',
                    'class' => 'fixed-width-lg',
                ),
                array(
                    'type' => 'text',
                    'name' => 'pwinstafeed_items3',
                    'label' => $this->l('Medium').' - 768px →',
                    'class' => 'fixed-width-lg',
                ),
                array(
                    'type' => 'text',
                    'name' => 'pwinstafeed_items4',
                    'label' => $this->l('Large').' - 992px →',
                    'class' => 'fixed-width-lg',
                ),
                array(
                    'type' => 'text',
                    'name' => 'pwinstafeed_items5',
                    'label' => $this->l('Extra large').' - 1180px →',
                    'class' => 'fixed-width-lg',
                ),
                array(
                    'type' => 'html',
                    'label' => '',
                    'name' => '',
                    'desc' => '<strong class="settings-header">'.$this->l('Number of columns').'</strong><span>'.$this->l('Per breakpoint, remember to change this depending on which hook you\'re using for this feed.').'</span>'
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Extra small').' - 0 →',
                    'name' => 'pwinstafeed_grid_xs',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 1,
                                'name' => $this->l('12')),
                            array(
                                'id' => 2,
                                'name' => $this->l('6')),
                            array(
                                'id' => 3,
                                'name' => $this->l('4')),
                            array(
                                'id' => 4,
                                'name' => $this->l('3')),
                            array(
                                'id' => 6,
                                'name' => $this->l('2')),
                            array(
                                'id' => 12,
                                'name' => $this->l('1')),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Small').' - 480px →',
                    'name' => 'pwinstafeed_grid_sm',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 1,
                                'name' => $this->l('12')),
                            array(
                                'id' => 2,
                                'name' => $this->l('6')),
                            array(
                                'id' => 3,
                                'name' => $this->l('4')),
                            array(
                                'id' => 4,
                                'name' => $this->l('3')),
                            array(
                                'id' => 6,
                                'name' => $this->l('2')),
                            array(
                                'id' => 12,
                                'name' => $this->l('1')),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Medium').' - 768px →',
                    'name' => 'pwinstafeed_grid_md',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 1,
                                'name' => $this->l('12')),
                            array(
                                'id' => 2,
                                'name' => $this->l('6')),
                            array(
                                'id' => 3,
                                'name' => $this->l('4')),
                            array(
                                'id' => 4,
                                'name' => $this->l('3')),
                            array(
                                'id' => 6,
                                'name' => $this->l('2')),
                            array(
                                'id' => 12,
                                'name' => $this->l('1')),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Large').' - 992px →',
                    'name' => 'pwinstafeed_grid_lg',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 1,
                                'name' => $this->l('12')),
                            array(
                                'id' => 2,
                                'name' => $this->l('6')),
                            array(
                                'id' => 3,
                                'name' => $this->l('4')),
                            array(
                                'id' => 4,
                                'name' => $this->l('3')),
                            array(
                                'id' => 6,
                                'name' => $this->l('2')),
                            array(
                                'id' => 12,
                                'name' => $this->l('1')),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Extra large').' - 1180px →',
                    'name' => 'pwinstafeed_grid_xl',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 1,
                                'name' => $this->l('12')),
                            array(
                                'id' => 2,
                                'name' => $this->l('6')),
                            array(
                                'id' => 3,
                                'name' => $this->l('4')),
                            array(
                                'id' => 4,
                                'name' => $this->l('3')),
                            array(
                                'id' => 6,
                                'name' => $this->l('2')),
                            array(
                                'id' => 12,
                                'name' => $this->l('1')),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'html',
                    'label' => '',
                    'name' => '',
                    'desc' => '<strong class="settings-header">'.$this->l('Misc settings').'</strong>'
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Hover - Background color'),
                    'name' => 'pwinstafeed_bgcolor',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Hover - Text color'),
                    'name' => 'pwinstafeed_fgcolor',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Hover - Show no. of likes'),
                    'name' => 'pwinstafeed_likes',
                    'hint' => $this->l('Opens each photo in a modal window or links directly to the particular photo'),
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Hover - Show no. of comments'),
                    'name' => 'pwinstafeed_comments',
                    'hint' => $this->l('Opens each photo in a modal window or links directly to the particular photo'),
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Modal functionality'),
                    'name' => 'pwinstafeed_modal',
                    'hint' => $this->l('Opens each photo in a modal window or links directly to the particular photo'),
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Hook position'),
                    'name' => 'pwinstafeed_hook',
                    'hint' => $this->l('Remember to move the module in "Modules → Positions" to what suits you best.'),
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 1,
                                'name' => $this->l('Footer')),
                            array(
                                'id' => 2,
                                'name' => $this->l('Homepage')),
                            array(
                                'id' => 3,
                                'name' => $this->l('Left column')),
                            array(
                                'id' => 4,
                                'name' => $this->l('Right column')),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save all'),
            ),
        );
        $this->fields_form[2]['form'] = array(
            'legend' => array(
                'title' => $this->l('Custom page'),
                'icon' => 'icon-file-o',
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'name' => 'pwinstafeed_pagetitle',
                    'label' => $this->l('Page title'),
                    'lang' => true,
                ),
                array(
                    'type' => 'text',
                    'name' => 'pwinstafeed_pagebreadcrumb',
                    'label' => $this->l('Breadcrumb title'),
                    'lang' => true,
                ),
                array(
                    'type' => 'textarea',
                    'name' => 'pwinstafeed_pagecontent',
                    'label' => $this->l('Page content'),
                    'lang' => true,
                    'autoload_rte' => true,
                    'cols' => 60,
                    'rows' => 30
                ),
                array(
                    'type' => 'html',
                    'label' => '',
                    'desc' => '<a class="btn btn-default help-tooltip" title="'.$this->l('Opens in a new window/tab').'" href="'.$this->context->link->getAdminLink('AdminMeta', true).'" target="_blank">'.$this->l('Go to "SEO & URLs" to change and/or update this page URL so you can use it efficiently.').'&ensp;<i class="icon-external-link"></i></a>',
                    'name' => '',
                ),
                array(
                    'type' => 'html',
                    'label' => '',
                    'name' => '',
                    'desc' => '<strong class="settings-header">'.$this->l('Feed setup').'</strong>'
                ),
                array(
                    'type' => 'text',
                    'name' => 'pwinstafeed_pagelimit',
                    'label' => $this->l('Number of photos'),
                    'prefix' => '<i class="icon-image"></i>',
                    'hint' => $this->l('How many photos to display, and fetch when using the "Load more" button'),
                    'class' => 'fixed-width-lg',
                ),
                array(
                    'type' => 'html',
                    'label' => '',
                    'name' => '',
                    'desc' => '<strong class="settings-header">'.$this->l('Number of columns').'</strong><span>'.$this->l('Per breakpoint').'</span>'
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Extra small').' - 0 →',
                    'name' => 'pwinstafeed_pagegrid_xs',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 1,
                                'name' => $this->l('12')),
                            array(
                                'id' => 2,
                                'name' => $this->l('6')),
                            array(
                                'id' => 3,
                                'name' => $this->l('4')),
                            array(
                                'id' => 4,
                                'name' => $this->l('3')),
                            array(
                                'id' => 6,
                                'name' => $this->l('2')),
                            array(
                                'id' => 12,
                                'name' => $this->l('1')),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Small').' - 480px →',
                    'name' => 'pwinstafeed_pagegrid_sm',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 1,
                                'name' => $this->l('12')),
                            array(
                                'id' => 2,
                                'name' => $this->l('6')),
                            array(
                                'id' => 3,
                                'name' => $this->l('4')),
                            array(
                                'id' => 4,
                                'name' => $this->l('3')),
                            array(
                                'id' => 6,
                                'name' => $this->l('2')),
                            array(
                                'id' => 12,
                                'name' => $this->l('1')),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Medium').' - 768px →',
                    'name' => 'pwinstafeed_pagegrid_md',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 1,
                                'name' => $this->l('12')),
                            array(
                                'id' => 2,
                                'name' => $this->l('6')),
                            array(
                                'id' => 3,
                                'name' => $this->l('4')),
                            array(
                                'id' => 4,
                                'name' => $this->l('3')),
                            array(
                                'id' => 6,
                                'name' => $this->l('2')),
                            array(
                                'id' => 12,
                                'name' => $this->l('1')),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Large').' - 992px →',
                    'name' => 'pwinstafeed_pagegrid_lg',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 1,
                                'name' => $this->l('12')),
                            array(
                                'id' => 2,
                                'name' => $this->l('6')),
                            array(
                                'id' => 3,
                                'name' => $this->l('4')),
                            array(
                                'id' => 4,
                                'name' => $this->l('3')),
                            array(
                                'id' => 6,
                                'name' => $this->l('2')),
                            array(
                                'id' => 12,
                                'name' => $this->l('1')),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Extra large').' - 1180px →',
                    'name' => 'pwinstafeed_pagegrid_xl',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 1,
                                'name' => $this->l('12')),
                            array(
                                'id' => 2,
                                'name' => $this->l('6')),
                            array(
                                'id' => 3,
                                'name' => $this->l('4')),
                            array(
                                'id' => 4,
                                'name' => $this->l('3')),
                            array(
                                'id' => 6,
                                'name' => $this->l('2')),
                            array(
                                'id' => 12,
                                'name' => $this->l('1')),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'html',
                    'label' => '',
                    'name' => '',
                    'desc' => '<strong class="settings-header">'.$this->l('Appearance').'</strong>'
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Page feed style'),
                    'name' => 'pwinstafeed_pagestyle',
                    'hint' => $this->l('Which style to apply to the modules own page and its controls'),
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 'square',
                                'name' => $this->l('Square')),
                            array(
                                'id' => 'rounded',
                                'name' => $this->l('Rounded')),
                            array(
                                'id' => 'circle',
                                'name' => $this->l('Circle')),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Spacing'),
                    'name' => 'pwinstafeed_pagespacing',
                    'options' => array(
                        'query' => array(
                            array(
                                'id' => 0,
                                'name' => $this->l('No spacing')),
                            array(
                                'id' => 10,
                                'name' => $this->l('10px')),
                            array(
                                'id' => 20,
                                'name' => $this->l('20px')),
                            array(
                                'id' => 30,
                                'name' => $this->l('30px')),
                        ),
                        'id' => 'id',
                        'name' => 'name'
                    )
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Modal functionality'),
                    'name' => 'pwinstafeed_pagemodal',
                    'hint' => $this->l('Opens each photo in a modal window or links directly to the particular photo'),
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Hover - Background color'),
                    'name' => 'pwinstafeed_pagebgcolor',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Hover - Text color'),
                    'name' => 'pwinstafeed_pagefgcolor',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Hover - Show no. of likes'),
                    'name' => 'pwinstafeed_pagelikes',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Hover - Show no. of comments'),
                    'name' => 'pwinstafeed_pagecomments',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type' => 'html',
                    'label' => '',
                    'name' => '',
                    'desc' => '<strong class="settings-header">'.$this->l('Load more button').'</strong>'
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Button background color'),
                    'name' => 'pwinstafeed_pagebtnbgcolor',
                ),
                array(
                    'type' => 'color',
                    'label' => $this->l('Button text color'),
                    'name' => 'pwinstafeed_pagebtnfgcolor',
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save all'),
            ),
        );
    }

    protected function initForm()
    {
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table =  $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper;
    }

    public function getConfigFieldsValues()
    {
        $id_shop = (int)$this->context->shop->id;
        $pwinstafeed_languages = pwinstafeedClass::getByIdShop($id_shop);
        foreach ($this->fields_form[2]['form']['input'] as $input)
        {
            if($input['name'] != '') {
                    $fields_value[$input['name']] = $pwinstafeed_languages->{$input['name']};
            }
        }

        return array_merge($fields_value, array(
            'pwinstafeed_accesstoken'     => Tools::getValue('pwinstafeed_accesstoken', Configuration::get('pwinstafeed_accesstoken')),
            'pwinstafeed_limit'           => Tools::getValue('pwinstafeed_limit', Configuration::get('pwinstafeed_limit')),
            'pwinstafeed_items1'          => Tools::getValue('pwinstafeed_items1', Configuration::get('pwinstafeed_items1')),
            'pwinstafeed_items2'          => Tools::getValue('pwinstafeed_items2', Configuration::get('pwinstafeed_items2')),
            'pwinstafeed_items3'          => Tools::getValue('pwinstafeed_items3', Configuration::get('pwinstafeed_items3')),
            'pwinstafeed_items4'          => Tools::getValue('pwinstafeed_items4', Configuration::get('pwinstafeed_items4')),
            'pwinstafeed_items5'          => Tools::getValue('pwinstafeed_items5', Configuration::get('pwinstafeed_items5')),
            'pwinstafeed_hook'            => Tools::getValue('pwinstafeed_hook', Configuration::get('pwinstafeed_hook')),
            'pwinstafeed_style'           => Tools::getValue('pwinstafeed_style', Configuration::get('pwinstafeed_style')),
            'pwinstafeed_carousel'        => Tools::getValue('pwinstafeed_carousel', Configuration::get('pwinstafeed_carousel')),
            'pwinstafeed_infinite'        => Tools::getValue('pwinstafeed_infinite', Configuration::get('pwinstafeed_infinite')),
            'pwinstafeed_modal'           => Tools::getValue('pwinstafeed_modal', Configuration::get('pwinstafeed_modal')),
            'pwinstafeed_likes'           => Tools::getValue('pwinstafeed_likes', Configuration::get('pwinstafeed_likes')),
            'pwinstafeed_comments'         => Tools::getValue('pwinstafeed_comments', Configuration::get('pwinstafeed_comments')),
            'pwinstafeed_pagelimit'       => Tools::getValue('pwinstafeed_pagelimit', Configuration::get('pwinstafeed_pagelimit')),
            'pwinstafeed_pagegrid_xs'     => Tools::getValue('pwinstafeed_pagegrid_xs', Configuration::get('pwinstafeed_pagegrid_xs')),
            'pwinstafeed_pagegrid_sm'     => Tools::getValue('pwinstafeed_pagegrid_sm', Configuration::get('pwinstafeed_pagegrid_sm')),
            'pwinstafeed_pagegrid_md'     => Tools::getValue('pwinstafeed_pagegrid_md', Configuration::get('pwinstafeed_pagegrid_md')),
            'pwinstafeed_pagegrid_lg'     => Tools::getValue('pwinstafeed_pagegrid_lg', Configuration::get('pwinstafeed_pagegrid_lg')),
            'pwinstafeed_pagegrid_xl'     => Tools::getValue('pwinstafeed_pagegrid_xl', Configuration::get('pwinstafeed_pagegrid_xl')),
            'pwinstafeed_grid_xs'         => Tools::getValue('pwinstafeed_grid_xs', Configuration::get('pwinstafeed_grid_xs')),
            'pwinstafeed_grid_sm'         => Tools::getValue('pwinstafeed_grid_sm', Configuration::get('pwinstafeed_grid_sm')),
            'pwinstafeed_grid_md'         => Tools::getValue('pwinstafeed_grid_md', Configuration::get('pwinstafeed_grid_md')),
            'pwinstafeed_grid_lg'         => Tools::getValue('pwinstafeed_grid_lg', Configuration::get('pwinstafeed_grid_lg')),
            'pwinstafeed_grid_xl'         => Tools::getValue('pwinstafeed_grid_xl', Configuration::get('pwinstafeed_grid_xl')),
            'pwinstafeed_pagespacing'     => Tools::getValue('pwinstafeed_pagespacing', Configuration::get('pwinstafeed_pagespacing')),
            'pwinstafeed_spacing'         => Tools::getValue('pwinstafeed_spacing', Configuration::get('pwinstafeed_spacing')),
            'pwinstafeed_pagemodal'       => Tools::getValue('pwinstafeed_pagemodal', Configuration::get('pwinstafeed_pagemodal')),
            'pwinstafeed_pagelikes'       => Tools::getValue('pwinstafeed_pagelikes', Configuration::get('pwinstafeed_pagelikes')),
            'pwinstafeed_pagecomments'    => Tools::getValue('pwinstafeed_pagecomments', Configuration::get('pwinstafeed_pagecomments')),
            'pwinstafeed_pagestyle'       => Tools::getValue('pwinstafeed_pagestyle', Configuration::get('pwinstafeed_pagestyle')),
            'pwinstafeed_pagebgcolor'     => Tools::getValue('pwinstafeed_pagebgcolor', Configuration::get('pwinstafeed_pagebgcolor')),
            'pwinstafeed_pagefgcolor'     => Tools::getValue('pwinstafeed_pagefgcolor', Configuration::get('pwinstafeed_pagefgcolor')),
            'pwinstafeed_bgcolor'         => Tools::getValue('pwinstafeed_bgcolor', Configuration::get('pwinstafeed_bgcolor')),
            'pwinstafeed_fgcolor'         => Tools::getValue('pwinstafeed_fgcolor', Configuration::get('pwinstafeed_fgcolor')),
            'pwinstafeed_btnbgcolor'      => Tools::getValue('pwinstafeed_btnbgcolor', Configuration::get('pwinstafeed_btnbgcolor')),
            'pwinstafeed_btnfgcolor'      => Tools::getValue('pwinstafeed_btnfgcolor', Configuration::get('pwinstafeed_btnfgcolor')),
            'pwinstafeed_pagebtnbgcolor'  => Tools::getValue('pwinstafeed_pagebtnbgcolor', Configuration::get('pwinstafeed_pagebtnbgcolor')),
            'pwinstafeed_pagebtnfgcolor'  => Tools::getValue('pwinstafeed_pagebtnfgcolor', Configuration::get('pwinstafeed_pagebtnfgcolor')),
        ));
    }

    public function hookDisplayHeader()
    {
        $userID = Configuration::get('pwinstafeed_accesstoken');
        $userIDExplode = explode('.', $userID);
        $userID = $userIDExplode[0];
        $pwi_pageUrl = 'https://api.instagram.com/v1/users/'.$userID.'/media/recent/?access_token='.Configuration::get('pwinstafeed_accesstoken').'&count='.Configuration::get('pwinstafeed_pagelimit');
        $pwi_url = 'https://api.instagram.com/v1/users/'.$userID.'/media/recent/?access_token='.Configuration::get('pwinstafeed_accesstoken').'&count='.Configuration::get('pwinstafeed_limit');
        $this->smarty->assign(array(
            'pwi_accesstoken'     => Configuration::get('pwinstafeed_accesstoken'),
            'pwi_limit'           => Configuration::get('pwinstafeed_limit'),
            'pwi_items1'          => Configuration::get('pwinstafeed_items1'),
            'pwi_items2'          => Configuration::get('pwinstafeed_items2'),
            'pwi_items3'          => Configuration::get('pwinstafeed_items3'),
            'pwi_items4'          => Configuration::get('pwinstafeed_items4'),
            'pwi_items5'          => Configuration::get('pwinstafeed_items5'),
            'pwi_hook'            => Configuration::get('pwinstafeed_hook'),
            'pwi_style'           => Configuration::get('pwinstafeed_style'),
            'pwi_carousel'        => Configuration::get('pwinstafeed_carousel'),
            'pwi_infinite'        => (bool)Configuration::get('pwinstafeed_infinite'),
            'pwi_modal'           => Configuration::get('pwinstafeed_modal'),
            'pwi_likes'           => Configuration::get('pwinstafeed_likes'),
            'pwi_comments'        => Configuration::get('pwinstafeed_comments'),
            'pwi_pagelimit'       => Configuration::get('pwinstafeed_pagelimit'),
            'pwi_pagegrid_xs'     => Configuration::get('pwinstafeed_pagegrid_xs'),
            'pwi_pagegrid_sm'     => Configuration::get('pwinstafeed_pagegrid_sm'),
            'pwi_pagegrid_md'     => Configuration::get('pwinstafeed_pagegrid_md'),
            'pwi_pagegrid_lg'     => Configuration::get('pwinstafeed_pagegrid_lg'),
            'pwi_pagegrid_xl'     => Configuration::get('pwinstafeed_pagegrid_xl'),
            'pwi_grid_xs'         => Configuration::get('pwinstafeed_grid_xs'),
            'pwi_grid_sm'         => Configuration::get('pwinstafeed_grid_sm'),
            'pwi_grid_md'         => Configuration::get('pwinstafeed_grid_md'),
            'pwi_grid_lg'         => Configuration::get('pwinstafeed_grid_lg'),
            'pwi_grid_xl'         => Configuration::get('pwinstafeed_grid_xl'),
            'pwi_pagespacing'     => Configuration::get('pwinstafeed_pagespacing'),
            'pwi_spacing'         => Configuration::get('pwinstafeed_spacing'),
            'pwi_pagemodal'       => Configuration::get('pwinstafeed_pagemodal'),
            'pwi_pagelikes'       => Configuration::get('pwinstafeed_pagelikes'),
            'pwi_pagecomments'    => Configuration::get('pwinstafeed_pagecomments'),
            'pwi_pagestyle'       => Configuration::get('pwinstafeed_pagestyle'),
            'pwi_pagebgcolor'     => Configuration::get('pwinstafeed_pagebgcolor'),
            'pwi_pagefgcolor'     => Configuration::get('pwinstafeed_pagefgcolor'),
            'pwi_bgcolor'         => Configuration::get('pwinstafeed_bgcolor'),
            'pwi_fgcolor'         => Configuration::get('pwinstafeed_fgcolor'),
            'pwi_btnbgcolor'      => Configuration::get('pwinstafeed_btnbgcolor'),
            'pwi_btnfgcolor'      => Configuration::get('pwinstafeed_btnfgcolor'),
            'pwi_pagebtnbgcolor'  => Configuration::get('pwinstafeed_pagebtnbgcolor'),
            'pwi_pagebtnfgcolor'  => Configuration::get('pwinstafeed_pagebtnfgcolor'),
            'pwi_pageUrl'         => $pwi_pageUrl,
            'pwi_url'             => $pwi_url
        ));
        Media::addJsDef(array(
            'pwi_accesstoken'     => Configuration::get('pwinstafeed_accesstoken'),
            'pwi_limit'           => Configuration::get('pwinstafeed_limit'),
            'pwi_items1'          => Configuration::get('pwinstafeed_items1'),
            'pwi_items2'          => Configuration::get('pwinstafeed_items2'),
            'pwi_items3'          => Configuration::get('pwinstafeed_items3'),
            'pwi_items4'          => Configuration::get('pwinstafeed_items4'),
            'pwi_items5'          => Configuration::get('pwinstafeed_items5'),
            'pwi_hook'            => Configuration::get('pwinstafeed_hook'),
            'pwi_style'           => Configuration::get('pwinstafeed_style'),
            'pwi_carousel'        => Configuration::get('pwinstafeed_carousel'),
            'pwi_infinite'        => (bool)Configuration::get('pwinstafeed_infinite'),
            'pwi_modal'           => Configuration::get('pwinstafeed_modal'),
            'pwi_likes'           => Configuration::get('pwinstafeed_likes'),
            'pwi_comments'        => Configuration::get('pwinstafeed_comments'),
            'pwi_pagelimit'       => Configuration::get('pwinstafeed_pagelimit'),
            'pwi_pagegrid_xs'     => Configuration::get('pwinstafeed_pagegrid_xs'),
            'pwi_pagegrid_sm'     => Configuration::get('pwinstafeed_pagegrid_sm'),
            'pwi_pagegrid_md'     => Configuration::get('pwinstafeed_pagegrid_md'),
            'pwi_pagegrid_lg'     => Configuration::get('pwinstafeed_pagegrid_lg'),
            'pwi_pagegrid_xl'     => Configuration::get('pwinstafeed_pagegrid_xl'),
            'pwi_grid_xs'         => Configuration::get('pwinstafeed_grid_xs'),
            'pwi_grid_sm'         => Configuration::get('pwinstafeed_grid_sm'),
            'pwi_grid_md'         => Configuration::get('pwinstafeed_grid_md'),
            'pwi_grid_lg'         => Configuration::get('pwinstafeed_grid_lg'),
            'pwi_grid_xl'         => Configuration::get('pwinstafeed_grid_xl'),
            'pwi_pagespacing'     => Configuration::get('pwinstafeed_pagespacing'),
            'pwi_spacing'         => Configuration::get('pwinstafeed_spacing'),
            'pwi_pagemodal'       => Configuration::get('pwinstafeed_pagemodal'),
            'pwip_likes'       => Configuration::get('pwinstafeed_pagelikes'),
            'pwip_comments'    => Configuration::get('pwinstafeed_pagecomments'),
            'pwi_pagestyle'       => Configuration::get('pwinstafeed_pagestyle'),
            'pwi_pagebgcolor'     => Configuration::get('pwinstafeed_pagebgcolor'),
            'pwi_pagefgcolor'     => Configuration::get('pwinstafeed_pagefgcolor'),
            'pwi_bgcolor'         => Configuration::get('pwinstafeed_bgcolor'),
            'pwi_fgcolor'         => Configuration::get('pwinstafeed_fgcolor'),
            'pwi_btnbgcolor'      => Configuration::get('pwinstafeed_btnbgcolor'),
            'pwi_btnfgcolor'      => Configuration::get('pwinstafeed_btnfgcolor'),
            'pwi_pagebtnbgcolor'  => Configuration::get('pwinstafeed_pagebtnbgcolor'),
            'pwi_pagebtnfgcolor'  => Configuration::get('pwinstafeed_pagebtnfgcolor'),
            'pwip_url'         => $pwi_pageUrl,
            'pwi_url'             => $pwi_url
        ));
        $this->context->controller->addJqueryPlugin('fancybox');
        $this->context->controller->addCSS(($this->_path).'views/css/pwinstafeed.css', 'all');
        $this->context->controller->addJS(($this->_path).'views/js/pwinstafeed.js');
    }

    public function hookDisplayFooter()
    {
        if (Configuration::get('pwinstafeed_hook') == 1) {
            return $this->display(__FILE__, 'views/templates/hook/pwinstafeed.tpl');
        }
        else {
            return;
        }
    }

    public function hookDisplayHome()
    {
        if (Configuration::get('pwinstafeed_hook') == 2) {
            return $this->display(__FILE__, 'views/templates/hook/pwinstafeed.tpl');
        }
        else {
            return;
        }
    }

    public function hookdisplayLeftColumn()
    {
        if (Configuration::get('pwinstafeed_hook') == 3) {
            return $this->display(__FILE__, 'views/templates/hook/pwinstafeed.tpl');
        }
        else {
            return;
        }
    }

    public function hookdisplayRightColumn()
    {
        if (Configuration::get('pwinstafeed_hook') == 4) {
            return $this->display(__FILE__, 'views/templates/hook/pwinstafeed.tpl');
        }
        else {
            return;
        }
    }
}
