<?php
class FrontController extends FrontControllerCore
{
    public function init()
	{
		parent::init();
		
		$link = $this->context->link->getBaseLink();
		if (Configuration::get('SEOURL_REMOVE_DEFAULT_ISO') && Configuration::get('PS_LANG_DEFAULT') != $this->context->language->id) {
			$iso = Language::getIsoById($this->context->language->id).'/';			
			$link .= $iso;	
		}
		
		$this->context->smarty->assign(array(
			'logo_lang_url' => $link
		));	
	}
}