<?php

class pwinstafeedpwinstafeedModuleFrontController extends ModuleFrontController
{
    public $display_column_left = false;
    public $display_column_right = false;
    public $ssl = true;

    public function setMedia()
    {
        parent::setMedia();
        $this->addJqueryPlugin(array('fancybox'));
        $this->context->controller->addCSS(_MODULE_DIR_.'pwinstafeed/views/css/pwinstafeed.css', 'all');
        $this->context->controller->addJS(_MODULE_DIR_.'pwinstafeed/views/js/pwinstafeed_front.js', 'all');
    }

    public function initContent()
    {
        parent::initContent();

        $id_shop = (int)$this->context->shop->id;
        $pwinstafeed_languages = pwinstafeedClass::getByIdShop($id_shop);

        if (!$pwinstafeed_languages) { return; }
        $pwinstafeed_languages = new pwinstafeedClass((int)$pwinstafeed_languages->id, $this->context->language->id);
        if (!$pwinstafeed_languages) { return; }

        $pwi_hashtag = str_replace('#','', Configuration::get('pwinstafeed_hashtag'));
        $pwi_hashtag_explode = explode(' ', $pwi_hashtag);
        $pwi_hashtag = $pwi_hashtag_explode[0];
        $pwi_prefix = str_replace('#','', Configuration::get('pwinstafeed_prefix'));
        $pwi_prefix_explode = explode(' ', $pwi_prefix);
        $pwi_prefix = $pwi_prefix_explode[0];
        $pwi_pagehashtag = str_replace('#','', Configuration::get('pwinstafeed_pagehashtag'));
        $pwi_pagehashtag_explode = explode(' ', $pwi_pagehashtag);
        $pwi_pagehashtag = $pwi_pagehashtag_explode[0];
        $this->context->smarty->assign(array(
            'pwinstafeed_page'      => $pwinstafeed_languages,
            'default_lang'          => (int)$this->context->language->id,
            'id_lang'               => $this->context->language->id,
            'pwi_clientid'          => Configuration::get('pwinstafeed_clientid'),
            'pwi_accesstoken'       => Configuration::get('pwinstafeed_accesstoken'),
            'pwi_userid'            => Configuration::get('pwinstafeed_userid'),
            'pwi_limit'             => Configuration::get('pwinstafeed_limit'),
            'pwi_items1'            => Configuration::get('pwinstafeed_items1'),
            'pwi_items2'            => Configuration::get('pwinstafeed_items2'),
            'pwi_items3'            => Configuration::get('pwinstafeed_items3'),
            'pwi_productlimit'      => Configuration::get('pwinstafeed_productlimit'),
            'pwi_hashtag'           => $pwi_hashtag,
            'pwi_prefix'            => $pwi_prefix,
            'pwi_producthash'       => $pwi_prefix.'_'.Tools::getValue('id_product'),
            'pwi_product'           => (bool)Configuration::get('pwinstafeed_product'),
            'pwi_hook'              => Configuration::get('pwinstafeed_hook'),
            'pwi_productstyle'      => Configuration::get('pwinstafeed_productstyle'),
            'pwi_style'             => Configuration::get('pwinstafeed_style'),
            'pwi_productcolumns'    => Configuration::get('pwinstafeed_productcolumns'),
            'pwi_bigcolumns'        => Configuration::get('pwinstafeed_bigcolumns'),
            'pwi_smallcolumns'      => Configuration::get('pwinstafeed_smallcolumns'),
            'pwi_feed'              => Configuration::get('pwinstafeed_feed'),
            'pwi_carousel'          => Configuration::get('pwinstafeed_carousel'),
            'pwi_infinite'          => (bool)Configuration::get('pwinstafeed_infinite'),
            'pwi_productcarousel'   => Configuration::get('pwinstafeed_productcarousel'),
            'pwi_productinfinite'   => (bool)Configuration::get('pwinstafeed_productinfinite'),
            'pwi_modal'             => Configuration::get('pwinstafeed_modal'),
            'pwi_mobile'            => Configuration::get('pwinstafeed_mobile'),
            'pwi_pagefeed'          => Configuration::get('pwinstafeed_pagefeed'),
            'pwi_pageuserid'        => Configuration::get('pwinstafeed_pageuserid'),
            'pwi_pagehashtag'       => $pwi_pagehashtag,
            'pwi_pagelimit'         => Configuration::get('pwinstafeed_pagelimit'),
            'pwi_pagecolumns'       => Configuration::get('pwinstafeed_pagecolumns'),
            'pwi_pagemodal'         => Configuration::get('pwinstafeed_pagemodal'),
            'pwi_pagestyle'         => Configuration::get('pwinstafeed_pagestyle'),
        ));

        $this->setTemplate('module:pwinstafeed/views/templates/front/pwinstafeedfront.tpl');
    }

    public function getBreadcrumbLinks()
    {
        $breadcrumb = parent::getBreadcrumbLinks();

        $breadcrumb['links'][] = [
            'title' => $this->getTranslator()->trans('PW Instafeed', [], 'Breadcrumb'),
            'url' => ''
         ];

         return $breadcrumb;
    }
}
