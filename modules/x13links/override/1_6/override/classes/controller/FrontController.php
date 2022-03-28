<?php
class FrontController extends FrontControllerCore
{
    public function init()
	{
		parent::init();
		
		if (version_compare(_PS_VERSION_, '1.5.0', '>=') === true) {
			$link = $this->context->link->getBaseLink_1_5();
		} else {
			$link = $this->context->link->getBaseLink();
		}		
		
		if (Configuration::get('SEOURL_REMOVE_DEFAULT_ISO') && Configuration::get('PS_LANG_DEFAULT') != $this->context->language->id) {
			$iso = Language::getIsoById($this->context->language->id).'/';			
			$link .= $iso;	
		}
		
		$this->context->smarty->assign(array(
			'logo_lang_url' => $link,
			'base_uri' => $link
		));	
	}
}