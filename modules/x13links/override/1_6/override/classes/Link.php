<?php
class Link extends LinkCore
{
    /*
    * module: x13links
    * date: 2017-09-16 15:25:41
    * version: 1.1
    */
    public function getCategoryLink($category, $alias = null, $id_lang = null, $selected_filters = null, $id_shop = null, $relative_protocol = false)
    {
        if (!$id_lang) {
            $id_lang = Context::getContext()->language->id;
        }
        $url = $this->getBaseLink($id_shop, null, $relative_protocol).$this->getLangLink($id_lang, null, $id_shop);
        if (!is_object($category)) {
            $category = new Category($category, $id_lang);
        }
        $params = array();
		$params['rewrite'] = (!$alias) ? $category->link_rewrite : $alias;
		$params['id'] = $category->id;
		$params['meta_keywords'] =    Tools::str2url($category->getFieldByLang('meta_keywords'));
		$params['meta_title'] = Tools::str2url($category->getFieldByLang('meta_title'));
        if (Configuration::get('SEOURL_CATEGORY')) {
		
            $cats = array();
            foreach ($category->getParentsCategories($id_lang) as $cat) {
                    if (!in_array($cat['id_category'], array(
                        Configuration::get('PS_HOME_CATEGORY'),
                        Configuration::get('PS_ROOT_CATEGORY'),
                        $category->id
                    ))
                    ) {
                        $cats[] = $cat['link_rewrite'];
                    }
            }
			
            $params['parents'] = implode('/', array_reverse($cats));
        }
		
        $selected_filters = is_null($selected_filters) ? '' : $selected_filters;
        if (empty($selected_filters)) {
            $rule = 'category_rule';
        } else {
            $rule = 'layered_rule';
            $params['selected_filters'] = $selected_filters;
        }
        return $url.Dispatcher::getInstance()->createUrl($rule, $id_lang, $params, $this->allow, '', $id_shop);
    }
	
	/*
    * module: x13links
    * date: 2017-09-16 15:25:41
    * version: 1.1
    */
    protected function getLangLink($id_lang = null, Context $context = null, $id_shop = null)
    {
		if (Configuration::get('SEOURL_REMOVE_DEFAULT_ISO') && $id_lang == Configuration::get('PS_LANG_DEFAULT'))
			return;
		
		return parent::getLangLink($id_lang, $context, $id_shop);
    }
	
	public function getBaseLink_1_5($id_shop = null, $ssl = null)
	{
		return $this->getBaseLink($id_shop, $ssl);
	}	
}
