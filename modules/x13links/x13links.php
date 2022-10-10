<?php
if (!defined('_PS_VERSION_'))
	exit;

if (!defined('X13_ION_VERSION_LI')){
    if (PHP_VERSION_ID >= 70100) {
        $x13IonVer = '-71';
    } else if (PHP_VERSION_ID >= 70000) {
        $x13IonVer = '-7';
    } else {
        $x13IonVer = '';
    }

    if (file_exists(_PS_MODULE_DIR_.'x13links/dev')) {
        $x13IonVer = '';
        $x13IonFolder = 'php5';
    }
    
    define('X13_ION_VERSION_LI', $x13IonVer);
}

require_once (dirname(__FILE__) . '/classes/x13linksmodel' . X13_ION_VERSION_LI . '.php');



class x13links extends x13linksModel
{
	public $_html = '';

	public function __construct()
	{
		$this->name = 'x13links';
		$this->tab = 'front_office_features';
		$this->version = '1.3.0';
		$this->author = 'x13.pl';
		$this->bootstrap = true;
		
		parent::__construct();
		
		if ($this->is_1_7()) {
			 $this->local_path = _PS_MODULE_DIR_.$this->name.'/override/1_7/';
		} else {
			 $this->local_path = _PS_MODULE_DIR_.$this->name.'/override/1_6/';
		}
		
		$this->displayName = $this->l('Pretty Links');
		$this->description = $this->l('Petty Links - remove ID number and default language');
	}
	
	public function getWarningMessage()
	{
		return $this->myDisplayWarning($this->l('If you want to use the advanced module - be sure to read the instructions on: https://x13.pl/doc/dokumentacja-przyjazne-linki-dla-prestashop'));
	}

	/**
    * Helper displaying warning message(s)
    * @param string|array $error
    * @return string
    */
    public function myDisplayWarning($warning)
    {
        $output = '
		<div class="bootstrap">
		<div class="module_warning alert alert-warning" >
			<button type="button" class="close" data-dismiss="alert">&times;</button>';

        if (is_array($warning)) {
            $output .= '<ul>';
            foreach ($warning as $msg) {
                $output .= '<li>'.$msg.'</li>';
            }
            $output .= '</ul>';
        } else {
            $output .= $warning;
        }

        // Close div openned previously
        $output .= '</div></div>';

        return $output;
    }
	
	public function renderForm()
	{
		$productsParamsList = '{rewrite}<span class="required">*</span>, {category:/}, {categories:/}';

		$productsDefaultConfiguration = $this->l('default configuration:').' '.self::$configNames['SEOURL_PRODUCT_RULE']['default'];

		if (Configuration::get('SEOURL_PRODUCT_COMBINATION')) {
			$productsParamsList .= ', {id_product_attribute:_}';
			$productsDefaultConfiguration = $this->l('default configuration (position od id_product_attribute should not be changed):').' {category:/}{id_product_attribute:_}{rewrite}.html';
		}

		$fields_form_1 = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => ($this->is_1_5() ? 'radio' : 'switch'),
						'label' => $this->l('Products'),
						'desc' => $this->l('Remove ID with links products'),
						'class' => ($this->is_1_5() ? 't' : 'fixed-width-xs'),
						'name' => 'SEOURL_PRODUCT',
						'default' => 0,
						'values' => array(
							array(
								'id' => 'SEOURL_PRODUCT_ON',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'SEOURL_PRODUCT_OFF',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
					array(
						'type' => 'text',
						'label' => $this->l('Product rule'),
						'param_list' => $productsParamsList,
						'desc' => $productsDefaultConfiguration,
						'name' => 'SEOURL_PRODUCT_RULE',
						'rule_for' => 'SEOURL_PRODUCT',
						'hide' => (Configuration::get('SEOURL_PRODUCT') && Configuration::get('SEOURL_ADVANCED_RULE') ? 0 : 1),
					),
					array(
						'type' => ($this->is_1_5() ? 'radio' : 'switch'),
						'label' => $this->l('Categories'),
						'desc' => $this->l('Remove ID with links categories'),
						'class' => ($this->is_1_5() ? 't' : 'fixed-width-xs'),
						'name' => 'SEOURL_CATEGORY',
						'default' => 0,
						'values' => array(
							array(
								'id' => 'SEOURL_CATEGORY_ON',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'SEOURL_CATEGORY_OFF',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
					array(
						'type' => 'text',
						'name' => 'SEOURL_CATEGORY_RULE',
						'label' => $this->l('Category rule'),
						'param_list' => '{rewrite}<span class="required">*</span>, {parents}',
						'desc' => $this->l('default configuration:').' '.self::$configNames['SEOURL_CATEGORY_RULE']['default'],
						'rule_for' => 'SEOURL_CATEGORY',
						'hide' => (Configuration::get('SEOURL_CATEGORY') && Configuration::get('SEOURL_ADVANCED_RULE') ? 0 : 1),
					),
					array(
						'type' => ($this->is_1_5() ? 'radio' : 'switch'),
						'label' => $this->l('Manufacturer'),
						'desc' => $this->l('Remove ID with links manufacturer'),
						'class' => ($this->is_1_5() ? 't' : 'fixed-width-xs'),
						'name' => 'SEOURL_MANUFACTURER',
						'default' => 0,
						'values' => array(
							array(
								'id' => 'SEOURL_MANUFACTURER_ON',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'SEOURL_MANUFACTURER_OFF',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
					array(
						'type' => 'text',
						'name' => 'SEOURL_MANUFACTURER_RULE',
						'label' => $this->l('Manufacturer rule'),
						'param_list' => '{rewrite}<span class="required">*</span>',
						'desc' => $this->l('default configuration:').' '.self::$configNames['SEOURL_MANUFACTURER_RULE']['default'],
						'rule_for' => 'SEOURL_MANUFACTURER',
						'hide' => (Configuration::get('SEOURL_MANUFACTURER') && Configuration::get('SEOURL_ADVANCED_RULE') ? 0 : 1),
					),
					array(
						'type' => ($this->is_1_5() ? 'radio' : 'switch'),
						'label' => $this->l('Supplier'),
						'desc' => $this->l('Remove ID with links supplier'),
						'class' => ($this->is_1_5() ? 't' : 'fixed-width-xs'),
						'name' => 'SEOURL_SUPPLIER',
						'default' => 0,
						'values' => array(
							array(
								'id' => 'SEOURL_SUPPLIER_ON',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'SEOURL_SUPPLIER_OFF',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
					array(
						'type' => 'text',
						'name' => 'SEOURL_SUPPLIER_RULE',
						'label' => $this->l('Supplier rule'),
						'param_list' => '{rewrite}<span class="required">*</span>',
						'desc' => $this->l('default configuration:').' '.self::$configNames['SEOURL_SUPPLIER_RULE']['default'],
						'rule_for' => 'SEOURL_SUPPLIER',
						'hide' => (Configuration::get('SEOURL_SUPPLIER') && Configuration::get('SEOURL_ADVANCED_RULE') ? 0 : 1),
					),
					array(
						'type' => ($this->is_1_5() ? 'radio' : 'switch'),
						'label' => $this->l('CMS category'),
						'desc' => $this->l('Remove ID with category CMS'),
						'class' => ($this->is_1_5() ? 't' : 'fixed-width-xs'),
						'name' => 'SEOURL_CMS_CATEGORY',
						'default' => 0,
						'values' => array(
							array(
								'id' => 'SEOURL_CMS_CATEGORY_ON',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'SEOURL_CMS_CATEGORY_OFF',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
					array(
						'type' => 'text',
						'name' => 'SEOURL_CMS_CATEGORY_RULE',
						'label' => $this->l('CMS rule'),
						'param_list' => '{rewrite}<span class="required">*</span>',
						'desc' => $this->l('default configuration:').' '.self::$configNames['SEOURL_CMS_CATEGORY_RULE']['default'],
						'rule_for' => 'SEOURL_CMS_CATEGORY',
						'hide' => (Configuration::get('SEOURL_CMS_CATEGORY') && Configuration::get('SEOURL_ADVANCED_RULE') ? 0 : 1),
					),
					array(
						'type' => ($this->is_1_5() ? 'radio' : 'switch'),
						'label' => $this->l('CMS'),
						'desc' => $this->l('Remove ID with links CMS'),
						'class' => ($this->is_1_5() ? 't' : 'fixed-width-xs'),
						'name' => 'SEOURL_CMS',
						'default' => 0,
						'values' => array(
							array(
								'id' => 'SEOURL_CMS_ON',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'SEOURL_CMS_OFF',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
					array(
						'type' => 'text',
						'name' => 'SEOURL_CMS_RULE',
						'label' => $this->l('CMS rule'),
						'param_list' => '{rewrite}<span class="required">*</span>',
						'desc' => $this->l('default configuration:').' '.self::$configNames['SEOURL_CMS_RULE']['default'],
						'rule_for' => 'SEOURL_CMS',
						'hide' => (Configuration::get('SEOURL_CMS') && Configuration::get('SEOURL_ADVANCED_RULE') ? 0 : 1),
					),
					array(
						'type' => ($this->is_1_5() ? 'radio' : 'switch'),
						'label' => $this->l('Language'),
						'desc' => $this->l('Remove default ISO with link'),
						'name' => 'SEOURL_REMOVE_DEFAULT_ISO',
						'class' => ($this->is_1_5() ? 't' : 'fixed-width-xs'),
						'default' => 0,
						'values' => array(
							array(
								'id' => 'SEOURL_REMOVE_DEFAULT_ISO_ON',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'SEOURL_REMOVE_DEFAULT_ISO_OFF',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
					array(
						'type' => ($this->is_1_5() ? 'radio' : 'switch'),
						'label' => $this->l('Enable advanced link editing'),
						'desc' => $this->l('You can modify the urle'),
						'class' => ($this->is_1_5() ? 't' : 'fixed-width-xs'),
						'name' => 'SEOURL_ADVANCED_RULE',
						'default' => 0,
						'values' => array(
							array(
								'id' => 'SEOURL_ADVANCED_RULE_ON',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'SEOURL_ADVANCED_RULE_OFF',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
				)
			),
		);

		if ($this->is_1_7()) {
			$keepIdOptions = array(
				array(
					'type' => ($this->is_1_5() ? 'radio' : 'switch'),
					'label' => $this->l('Keep ID of the combiation'),
					'desc' => $this->l('If you choose this option to "yes" it will always keep combination ID in URL of the product, unless you will change settings of the advanced custom rewrite'),
					'class' => ($this->is_1_5() ? 't' : 'fixed-width-xs'),
					'name' => 'SEOURL_PRODUCT_COMBINATION',
					'default' => 0,
					'values' => array(
						array(
							'id' => 'SEOURL_PRODUCT_COMBINATION_ON',
							'value' => 1,
							'label' => $this->l('Yes')
						),
						array(
							'id' => 'SEOURL_PRODUCT_COMBINATION_OFF',
							'value' => 0,
							'label' => $this->l('No')
						)
					),
					'hide' => !Configuration::get('SEOURL_PRODUCT'),
				)
			);

			self::arrayInsert($fields_form_1['form']['input'], 1, $keepIdOptions);
		}

		$tpl_vars = array();
		$tpl_vars['fields_value'] = $this->getConfigFieldsValues();
		
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$helper->module = $this;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitSeoLinks';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = $tpl_vars;
		$helper->override_folder = '/';
		$helper->base_folder = '/';
		if ($this->is_1_5()) {
			$helper->base_tpl = 'form_1_5.tpl';
		} else {
			$helper->base_tpl = 'form_1_6.tpl';
		}

		return $helper->generateForm(array($fields_form_1));
	}

	public static function arrayInsert(&$array, $position, $insert)
	{
	    if (is_int($position)) {
	        array_splice($array, $position, 0, $insert);
	    } else {
	        $pos   = array_search($position, array_keys($array));
	        $array = array_merge(
	            array_slice($array, 0, $pos),
	            $insert,
	            array_slice($array, $pos)
	        );
	    }
	}
	
	public function renderDupliacetsList()
	{
		$html = '';
		$types = array();
			
		if (Configuration::get('SEOURL_PRODUCT'))
			$types['products'] = array(
				'lang' => true,
				'title' => $this->l('Repetitions URL products'),
				'title_list' => $this->l('Products'),
			);
			
		if (Configuration::get('SEOURL_CATEGORY'))
			$types['categories'] = array(
				'lang' => true,
				'title' => $this->l('Repetitions URL categories'),
				'title_list' => $this->l('Category'),
			);
			
		if (Configuration::get('SEOURL_SUPPLIER'))
			$types['manufacturers'] =  array(
				'title' => $this->l('Repetitions URL manufacturers'),
				'title_list' => $this->l('Manufacture'),
			);
			
		if (Configuration::get('SEOURL_MANUFACTURER'))
			$types['suppliers'] =  array(
				'title' => $this->l('Repetitions URL suppliers'),
				'title_list' => $this->l('Suppliers'),
			);
		
		if (Configuration::get('SEOURL_CMS_CATEGORY'))
			$types['cms_category'] = array(
				'lang' => true,
				'title' => $this->l('Repetitions URL CMS CATEGORY'),
				'title_list' => $this->l('CMS CATEGORY'),
			);
			
		if (Configuration::get('SEOURL_CMS'))
			$types['cms'] = array(
				'lang' => true,
				'title' => $this->l('Repetitions URL CMS'),
				'title_list' => $this->l('CMS'),
			);
		
		$languages = Language::getLanguages(true);

		foreach ($types as $key => $params) {
		
			$fields_list = array(

				'id' => array(
					'title' => $this->l('#'),
					'search' => false,
				),
				'name' => array(
					'title' => $params['title_list'],
					'search' => false,
				),
				'link_rewrite' => array(
					'title' => $this->l('Rewrite'),
					'search' => false,
				)
			);
			
			if ($key == 'categories') {
				$fields_list['path'] = array(
					'title' => $this->l('Path'),
					'search' => false,
				);
			}
			
			if ($this->is_1_5()) {
				$fields_list['action'] = array(
					'title' => $this->l('Action'),
					'search' => false,
					'callback_object' => $this,
					'callback' => 'display_1_5_edit',
				);
			}
			
			$displayList = array();
			
			if (isset($params['lang']) && $params['lang']) {
				foreach ($languages as $lang) {
					$duplicates = $this->{'getDuplicates'.$key}($lang['id_lang']);
					
					if ($duplicates) {
						$displayList[] = array(
							'title' => $params['title'].' - '.$lang['iso_code'],
							'duplicates' => $this->prepareDuplicates($duplicates, $key),
						);
					}
				}
			} else {
				$duplicates = $this->{'getDuplicates'.$key}();
					
				if ($duplicates) {
					$displayList[] = array(
						'title' => $params['title'],
						'duplicates' => $this->prepareDuplicates($duplicates, $key),
					);
				}
			}
			
			if ($displayList)
				foreach ($displayList as $list) {
					$helper_list = New HelperList();
					$helper_list->module = $this;
					$helper_list->title = $list['title'];
					$helper_list->shopLinkType = '';
					$helper_list->no_link = true;
					$helper_list->show_toolbar = true;
					$helper_list->simple_header = false;
					$helper_list->identifier = 'id';
					$helper_list->table = 'seolink';
					if (!$this->is_1_5()) {
						$reflect = new ReflectionClass($helper_list);
						$props   = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
						
						foreach ($props as $prop) {
							if ($prop->getName() == '_pagination') {
								$helper_list->_pagination = array(2000);
							} else if ($prop->getName() == '_pagination') {
								$helper_list->_default_pagination = 2000;
							}
						}
					} else {
						$helper_list->simple_header = true;
					}
					$helper_list->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name;
					$helper_list->token = Tools::getAdminTokenLite('AdminModules');
					if (!$this->is_1_5()) {
						$helper_list->actions = array('repairrewrite'.$key);
					}
					
					$helper_list->listTotal = count($list['duplicates']);

					$html .= $helper_list->generateList($list['duplicates'], $fields_list);
				}
		}
		
		return $html;
	}
	
	protected function prepareDuplicates($duplicates, $key)
	{
		foreach ($duplicates as &$duplicate) {
			$url = '#';
			if ($key == 'products') {
				if(version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
					$url = $this->context->link->getAdminLink('AdminProducts').'&updateproduct&id_product='.$duplicate['id'];
				} else {
					global $kernel; // sf kernel
					if ($kernel instanceof Symfony\Component\HttpKernel\HttpKernelInterface) {
						$sfRouter = $kernel->getContainer()->get('router');
					}
					$url = $sfRouter->generate('admin_product_form', array('id' => $duplicate['id']));
				}	
				$duplicate['action'] = '<a href="'.$url.'">'.$this->l('Edit').'</a>';
			} else {
				$duplicate['action'] = $this->{'displayrepairrewrite'.$key.'link'}(null, $duplicate['id']);
			}
		}
		
		return $duplicates;
	}
	
	public function display_1_5_edit($echo, $tr) {
		return $echo;
	}
	
	public function displayRepairRewriteProductsLink($token = null, $id, $name = null)
	{
		if(version_compare(_PS_VERSION_, '1.7.0.0', '<')) {
			$url = $this->context->link->getAdminLink('AdminProducts').'&updateproduct&id_product='.$id;
		} else {
			global $kernel; // sf kernel
			if ($kernel instanceof Symfony\Component\HttpKernel\HttpKernelInterface) {
				$sfRouter = $kernel->getContainer()->get('router');
			}		
			$url = $sfRouter->generate('admin_product_form', array('id' => $id));
		}		
		
		$this->smarty->assign(array(
			'href' => $url,
			'action' => $this->l('Edit'),
		));

		return $this->display(__FILE__, 'views/templates/admin/repairrewrite.tpl');
	}
	
	public function displayRepairRewriteCategoriesLink($token = null, $id, $name = null)
	{
		if(version_compare(_PS_VERSION_, '1.7.6.0', '<')) {
			$url = $this->context->link->getAdminLink('AdminCategories').'&updatecategory&id_category='.$id;
		} else {
			global $kernel; // sf kernel
			if ($kernel instanceof Symfony\Component\HttpKernel\HttpKernelInterface) {
				$sfRouter = $kernel->getContainer()->get('router');
			}		
			$url = $sfRouter->generate('admin_categories_edit', array('categoryId' => $id));
		}
		
		$this->smarty->assign(array(
			'href' => $url,
			'action' => $this->l('Edit'),
		));

		return $this->display(__FILE__, 'views/templates/admin/repairrewrite.tpl');
	}
	
	public function displayRepairRewriteSuppliersLink($token = null, $id, $name = null)
	{
		$this->smarty->assign(array(
			'href' => $this->context->link->getAdminLink('AdminSuppliers').'&updatesupplier&id_supplier='.$id,
			'action' => $this->l('Edit'),
		));

		return $this->display(__FILE__, 'views/templates/admin/repairrewrite.tpl');
	}
	
	public function displayRepairRewriteManufacturersLink($token = null, $id, $name = null)
	{
		if(version_compare(_PS_VERSION_, '1.7.6.0', '<')) {
			$url = $this->context->link->getAdminLink('AdminManufacturers').'&updatemanufacturer&id_manufacturer='.$id;
		} else {
			global $kernel; // sf kernel
			if ($kernel instanceof Symfony\Component\HttpKernel\HttpKernelInterface) {
				$sfRouter = $kernel->getContainer()->get('router');
			}		
			$url = $sfRouter->generate('admin_manufacturers_edit', array('manufacturerId' => $id));
		}	
		
		$this->smarty->assign(array(
			'href' => $url,
			'action' => $this->l('Edit'),
		));

		return $this->display(__FILE__, 'views/templates/admin/repairrewrite.tpl');
	}
	
	public function displayRepairRewriteCmsLink($token = null, $id, $name = null)
	{
		if(version_compare(_PS_VERSION_, '1.7.6.0', '<')) {
			$url = $this->context->link->getAdminLink('AdminCmsContent').'&updatecms&id_cms='.$id;
		} else {
			global $kernel; // sf kernel
			if ($kernel instanceof Symfony\Component\HttpKernel\HttpKernelInterface) {
				$sfRouter = $kernel->getContainer()->get('router');
			}		
			$url = $sfRouter->generate('admin_cms_pages_edit', array('cmsPageId' => $id));
		}	
		
		$this->smarty->assign(array(
			'href' => $url,
			'action' => $this->l('Edit'),
		));

		return $this->display(__FILE__, 'views/templates/admin/repairrewrite.tpl');
	}
	
	public function displayRepairRewriteCms_categoryLink($token = null, $id, $name = null)
	{
		if(version_compare(_PS_VERSION_, '1.7.6.0', '<')) {
			$url = $this->context->link->getAdminLink('AdminCmsContent').'&updatecms_category&id_cms_category='.$id;
		} else {
			global $kernel; // sf kernel
			if ($kernel instanceof Symfony\Component\HttpKernel\HttpKernelInterface) {
				$sfRouter = $kernel->getContainer()->get('router');
			}		
			$url = $sfRouter->generate('admin_cms_pages_category_edit', array('cmsCategoryId' => $id));
		}	
		
		$this->smarty->assign(array(
			'href' => $url,
			'action' => $this->l('Edit'),
		));
		
		return $this->display(__FILE__, 'views/templates/admin/repairrewrite.tpl');
	}
	
	public function getConfigFieldsValues()
	{
		$values = array();
		
		foreach (self::$configNames as $key => $params)
			$values[$key] = Tools::getValue($key, Configuration::get($key));
			
		return $values;
	}

	/*
	 * Generuje szablon popup z informacją o powtórzeniach przyjaznych odnośników
	 *
	 * Dodane od 1.2.3
	 */
	public function renderModal()
	{
		$version = $this->is_1_5() ? '-1.5' : '-1.6';

		return $this->display(__FILE__, 'views/templates/admin/modal'.$version.'.tpl');
	}
}
