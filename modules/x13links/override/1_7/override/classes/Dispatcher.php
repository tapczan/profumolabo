<?php
class Dispatcher extends DispatcherCore
{
    protected $module;
	
    protected function __construct($request = null)
    {		
		if (Configuration::get('SEOURL_CATEGORY')) {
			$route_id = 'category_rule';
			$this->default_routes['default'.$route_id] = $this->default_routes[$route_id];
			if ($custom_route = Configuration::get('PS_ROUTE_'.$route_id, null, null)) {
				$this->default_routes['default'.$route_id]['rule'] = $custom_route;
			}
			
			$this->default_routes[$route_id] = array(
				'controller' =>    'category',
				'rule' =>        '{parents:/}{rewrite}',
				'keywords' => array(
					'id' =>            array('regexp' => '[0-9]+'),
					'rewrite' =>        array('regexp' => '[_a-zA-Z0-9\pL\pS-]*', 'param' => 'rewrite'),
					'meta_keywords' =>    array('regexp' => '[_a-zA-Z0-9-\pL]*'),
					'meta_title' =>        array('regexp' => '[_a-zA-Z0-9-\pL]*'),
					'parents' =>        array('regexp' => '[/_a-zA-Z0-9-\pL]*'),
				),
			);
			
			$module_category_rule = '{parents:/}{rewrite}';
			
			// Jeśli zaawansowane 
			if (Configuration::get('SEOURL_ADVANCED_RULE')) {
				$module_category_rule = Configuration::get('SEOURL_CATEGORY_RULE');
			}

			if (strpos($module_category_rule, '{parents') !== false) {
				$this->default_routes[$route_id]['keywords']['parents']['param'] = 'parents';
			}
		}
		
		if (Configuration::get('SEOURL_PRODUCT')) {
			$route_id = 'product_rule';
			$this->default_routes['default'.$route_id] = $this->default_routes[$route_id];
			if ($custom_route = Configuration::get('PS_ROUTE_'.$route_id, null, null)) {
				$this->default_routes['default'.$route_id]['rule'] = $custom_route;
			}
			
            $this->default_routes['default'.$route_id]['rule'] = str_replace('{-:id_product_attribute}', '', $this->default_routes['default'.$route_id]['rule']);
            $this->default_routes['default'.$route_id]['keywords']['id_product_attribute'] = array('regexp' => '[0-9]+');
			
			$this->default_routes[$route_id] = array(
				'controller' =>    'product',
				'rule' =>        '{category:/}{rewrite}.html',
				'keywords' => array(
					'id' =>            array('regexp' => '[0-9]+'),
					'rewrite' =>        array('regexp' => '[_a-zA-Z0-9\pL\pS-]*', 'param' => 'rewrite'),
					'ean13' =>        array('regexp' => '[0-9\pL]*'),
					'category' =>        array('regexp' => '[_a-zA-Z0-9-\pL]*', 'param' => 'category'),
                    'categories' =>        array('regexp' => '[/_a-zA-Z0-9-\pL]*', 'param' => 'category'),
					'reference' =>        array('regexp' => '[_a-zA-Z0-9-\pL]*'),
					'meta_keywords' =>    array('regexp' => '[_a-zA-Z0-9-\pL]*'),
					'meta_title' =>        array('regexp' => '[_a-zA-Z0-9-\pL]*'),
					'manufacturer' =>    array('regexp' => '[_a-zA-Z0-9-\pL]*'),
					'supplier' =>        array('regexp' => '[_a-zA-Z0-9-\pL]*'),
					'price' =>            array('regexp' => '[0-9\.,]*'),
					'tags' =>            array('regexp' => '[a-zA-Z0-9-\pL]*'),
				),
			);
			
			if (version_compare(_PS_VERSION_, '1.7.0', '>=') === true) {
                if (Configuration::get('SEOURL_PRODUCT_COMBINATION')) {
                    $this->default_routes[$route_id]['rule'] = '{category:/}{id_product_attribute:_}{rewrite}';
                    $this->default_routes[$route_id]['keywords']['rewrite'] = array('regexp' => '[_a-zA-Z0-9\pL\pS-]*', 'param' => 'rewrite');
                    $this->default_routes[$route_id]['keywords']['id_product_attribute'] = array('regexp' => '[0-9]+', 'param' => 'id_product_attribute');
                } else {
                    $this->default_routes[$route_id]['keywords']['id_product_attribute'] = array('regexp' => '[0-9]+', 'param' => 'id_product_attribute');
                }
            }
		}
		
		if (Configuration::get('SEOURL_SUPPLIER')) {
			$route_id = 'supplier_rule';
			$this->default_routes['default'.$route_id] = $this->default_routes[$route_id];
			if ($custom_route = Configuration::get('PS_ROUTE_'.$route_id, null, null)) {
				$this->default_routes['default'.$route_id]['rule'] = $custom_route;
			}
			
			$this->default_routes['supplier_rule'] = array(
				'controller' =>    'supplier',
				'rule' =>        'supplier/{rewrite}',
				'keywords' => array(
					'id' =>            array('regexp' => '[0-9]+'),
					'rewrite' =>        array('regexp' => '[_a-zA-Z0-9\pL\pS-]*', 'param' => 'rewrite'),
					'meta_keywords' =>    array('regexp' => '[_a-zA-Z0-9-\pL]*'),
					'meta_title' =>        array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
			);
		}
		
		if (Configuration::get('SEOURL_MANUFACTURER')) {
			$route_id = 'manufacturer_rule';
			$this->default_routes['default'.$route_id] = $this->default_routes[$route_id];
			if ($custom_route = Configuration::get('PS_ROUTE_'.$route_id, null, null)) {
				$this->default_routes['default'.$route_id]['rule'] = $custom_route;
			}
			
			$this->default_routes['manufacturer_rule'] = array(
				'controller' =>    'manufacturer',
				'rule' =>        'manufacturer/{rewrite}',
				'keywords' => array(
					'id' =>            array('regexp' => '[0-9]+'),
					'rewrite' =>        array('regexp' => '[_a-zA-Z0-9\pL\pS-]*', 'param' => 'rewrite'),
					'meta_keywords' =>    array('regexp' => '[_a-zA-Z0-9-\pL]*'),
					'meta_title' =>        array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
			);
		} else if (Configuration::get('SEOURL_SUPPLIER')) {
			$tmp = $this->default_routes['manufacturer_rule'];
			unset($this->default_routes['manufacturer_rule']);
			$this->default_routes['manufacturer_rule'] = $tmp;
		}
		
		if (Configuration::get('SEOURL_CMS')) {
			$route_id = 'cms_rule';
			$this->default_routes['default'.$route_id] = $this->default_routes[$route_id];
			if ($custom_route = Configuration::get('PS_ROUTE_'.$route_id, null, null)) {
				$this->default_routes['default'.$route_id]['rule'] = $custom_route;
			}
			
			$this->default_routes['cms_rule'] = array(
				'controller' =>    'cms',
				'rule' =>        'content/{rewrite}',
				'keywords' => array(
					'id' =>            array('regexp' => '[0-9]+'),
					'rewrite' =>        array('regexp' => '[_a-zA-Z0-9\pL\pS-]*', 'param' => 'rewrite'),
					'meta_keywords' =>    array('regexp' => '[_a-zA-Z0-9-\pL]*'),
					'meta_title' =>        array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
			);
		}
		
		if (Configuration::get('SEOURL_CMS_CATEGORY')) {
			$route_id = 'cms_category_rule';
			$this->default_routes['default'.$route_id] = $this->default_routes[$route_id];
			if ($custom_route = Configuration::get('PS_ROUTE_'.$route_id, null, null)) {
				$this->default_routes['default'.$route_id]['rule'] = $custom_route;
			}
			
			$this->default_routes['cms_category_rule'] = array(
				'controller' =>    'cms',
				'rule' =>        'content/category/{rewrite}',
				'keywords' => array(
					'id' =>            array('regexp' => '[0-9]+'),
					'rewrite' =>        array('regexp' => '[_a-zA-Z0-9\pL\pS-]*', 'param' => 'rewrite'),
					'meta_keywords' =>    array('regexp' => '[_a-zA-Z0-9-\pL]*'),
					'meta_title' =>        array('regexp' => '[_a-zA-Z0-9-\pL]*'),
				),
			);
		}
		
		parent::__construct($request);
	}
	
    public function initModule()
	{
		if (!$this->module)
			$this->module = Module::getInstanceByName('x13links');
	}
	
    protected function setRequestUri()
    {
		// Jeśli administracja to obsługuje wg domyślnych ustawień
		if (!in_array($this->front_controller, array(self::FC_FRONT, self::FC_MODULE))) {
			return parent::setRequestUri();
		}	
		
		// Obsługa x13linkrewrite
		if (Module::isEnabled('x13linkrewrite') && $this->front_controller == self::FC_FRONT) {
			$module = Module::getInstanceByName('x13linkrewrite');
			$module->parseRequest();
		}
		
        if (isset($_SERVER['REQUEST_URI'])) {
            $this->request_uri = $_SERVER['REQUEST_URI'];
        } elseif (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
            $this->request_uri = $_SERVER['HTTP_X_REWRITE_URL'];
        }
        $this->request_uri = rawurldecode($this->request_uri);
        if (isset(Context::getContext()->shop) && is_object(Context::getContext()->shop)) {
            $this->request_uri = preg_replace('#^'.preg_quote(Context::getContext()->shop->getBaseURI(), '#').'#i', '/', $this->request_uri);
        }
        if ($this->use_routes && Language::isMultiLanguageActivated()) {
			$iso = false;
            if (preg_match('#^/([a-z]{2})(?:/.*)?$#', $this->request_uri, $m)) {
				if (Configuration::get('SEOURL_REMOVE_DEFAULT_ISO') && !in_array($m[1], array('pl', 'de', 'en', 'it', 'ru', 'gb', 'es', 'fr'))) {
					$iso_code = DB::getInstance()->getvalue('SELECT iso_code FROM '._DB_PREFIX_.'lang WHERE iso_code = \''.pSQL($m[1]).'\'');
				} else {
					$iso_code = true;
				}
				
				if ($iso_code) {
                    $iso = true;
                    $_GET['isolang'] = $m[1];
                    $this->request_uri = substr($this->request_uri, 3);
                    $language = new Language(Language::getIdByIso($_GET['isolang']));
                } else {
                    $language = new Language(Configuration::get('PS_LANG_DEFAULT'));
                    $_GET['isolang'] = $language->iso_code;
                }

                if (!Validate::isLoadedObject($language)) {
                    $language = new Language(Configuration::get('PS_LANG_DEFAULT'));
                }

                Context::getContext()->language = $language;
                Context::getContext()->cookie->id_lang = $language->id;
                Tools::setCookieLanguage(Context::getContext()->cookie);
            } else if (Configuration::get('SEOURL_REMOVE_DEFAULT_ISO')) {
                if (strpos($_SERVER['REQUEST_URI'], 'modules/') != false) {
                    if (isset($_GET['id_lang']) && $_GET['id_lang']) {
                        $language = new Language((int) $_GET['id_lang']);
                    } else {
                        $language = new Language(Context::getContext()->cookie->id_lang);
                    }

                    if (!Validate::isLoadedObject($language)) {
                        $language = new Language(Configuration::get('PS_LANG_DEFAULT'));
                    }
                    
                    $_GET['isolang'] = $language->iso_code;
                    $iso = true;
                    
                    Context::getContext()->language = $language;
                    Context::getContext()->cookie->id_lang = $language->id;
                    Tools::setCookieLanguage(Context::getContext()->cookie);
                } else {
                    if (Context::getContext()->cookie->detect_language && Configuration::get('PS_DETECT_LANG') && strlen($this->request_uri) < 2) {
                        Tools::setCookieLanguage(Context::getContext()->cookie);
                    } else {
                        $language = new Language(Configuration::get('PS_LANG_DEFAULT'));
                        Context::getContext()->language = $language;
                        Context::getContext()->cookie->id_lang = $language->id;
                        $_GET['isolang'] = $language->iso_code;
                    }
                }
            }
        }
	}
	
    protected function loadRoutes($id_shop = null)
    {
		$this->initModule();
		parent::loadRoutes();
		
        $context = Context::getContext();
		
		if ($this->module->is_1_5()) {
			$language_ids = array();
			foreach (Language::getLanguages() as $lang) {
				$language_ids[] = $lang['id_lang'];
			}
		} else {
			$language_ids = Language::getIDs();
		}
        if (isset($context->language) && !in_array($context->language->id, $language_ids)) {
            $language_ids[] = (int)$context->language->id;
        }
        if ($this->use_routes) {
            foreach ($this->default_routes as $route_id => $route_data) {				
				if (in_array($route_id, array('category_rule', 'supplier_rule', 'manufacturer_rule', 'cms_category_rule', 'cms_rule', 'product_rule'))) {
					$exp = explode('_', $route_id);
					$name = 'SEOURL_'.strtoupper($exp[0]);
					$rewrite = '{rewrite}';
					
					if ($exp[0] == 'product') {
						$rewrite = $this->module->getRule('SEOURL_PRODUCT_RULE');
					} else if ($exp[0] == 'category') {
						$rewrite = $this->module->getRule('SEOURL_CATEGORY_RULE');
					} else if ($exp[0] == 'cms' && $exp[1] == 'category') {
						$rewrite = $this->module->getRule('SEOURL_CMS_CATEGORY_RULE');
						$name .= '_CATEGORY';
					} else if ($exp[0] == 'cms') {
						$rewrite = $this->module->getRule('SEOURL_CMS_RULE');
					} else if ($exp[0] == 'manufacturer') {
						$rewrite = $this->module->getRule('SEOURL_MANUFACTURER_RULE');
					} else if ($exp[0] == 'supplier') {
						$rewrite = $this->module->getRule('SEOURL_SUPPLIER_RULE');
					}
					
					if (Configuration::get($name)) {
						foreach ($language_ids as $id_lang) {
								$this->addRoute(
									$route_id,
									$rewrite,
									$route_data['controller'],
									$id_lang,
									$route_data['keywords'],
									isset($route_data['params']) ? $route_data['params'] : array(),
									$id_shop
								);
						}
					}
				}			
            }			
        }
    }	
	
    public function getController($id_shop = null)
    {
        if (defined('_PS_ADMIN_DIR_')) {
            $_GET['controllerUri'] = Tools::getvalue('controller');
        }
        if ($this->controller) {
            $_GET['controller'] = $this->controller;
            return $this->controller;
        }
        if (isset(Context::getContext()->shop) && $id_shop === null) {
            $id_shop = (int)Context::getContext()->shop->id;
        }
        $controller = Tools::getValue('controller');
        if (isset($controller) && is_string($controller) && preg_match('/^([0-9a-z_-]+)\?(.*)=(.*)$/Ui', $controller, $m)) {
            $controller = $m[1];
            if (isset($_GET['controller'])) {
                $_GET[$m[2]] = $m[3];
            } elseif (isset($_POST['controller'])) {
                $_POST[$m[2]] = $m[3];
            }
        }
        if (!Validate::isControllerName($controller)) {
            $controller = false;
        }
        if ($this->use_routes && !$controller && !defined('_PS_ADMIN_DIR_')) {
			// Sprawdzamy czy ma zostać wymuszony podgląd produktu
			if (Tools::isSubmit('force_preview') && ($id_product = (int)Tools::getValue('id_product'))) {
				$_POST['id_product'] = $id_product;
				$_GET['id_product'] = $id_product;
				$this->controller = 'product';
				$_GET['controller'] = $this->controller;
				return $this->controller;
			}	
			
            if (!$this->request_uri) {
                return strtolower($this->controller_not_found);
            }
            $test_request_uri = preg_replace('/(=http:\/\/)/', '=', $this->request_uri);
            if (!preg_match('/\.(gif|jpe?g|png|css|js|ico)$/i', parse_url($test_request_uri, PHP_URL_PATH))) {
                list($uri) = explode('?', $this->request_uri);
				$short_link = ltrim(parse_url($uri, PHP_URL_PATH), '/');
				
				if (!empty($short_link)) {
					$route = $this->routes[$id_shop][Context::getContext()->language->id]['product_rule'];					
					if (!$controller && $this->module->isProductLink($uri, $route)) {
						$controller = 'product';				
					}
					
					$route = $this->routes[$id_shop][Context::getContext()->language->id]['category_rule'];					
					if ($this->module->isCategoryLink($uri, $route)) {
						$controller = 'category';				
					}
					
					$route = $this->routes[$id_shop][Context::getContext()->language->id]['cms_category_rule'];					
					if (!$controller && $this->module->isCMSCategoryLink($uri, $route)) {
						$controller = 'cms';				
					}
					
					$route = $this->routes[$id_shop][Context::getContext()->language->id]['cms_rule'];					
					if (!$controller && $this->module->isCMSLink($uri, $route)) {
						$controller = 'cms';				
					}
					
					$route = $this->routes[$id_shop][Context::getContext()->language->id]['manufacturer_rule'];					
					if (!$controller && $this->module->isManufacturerLink($uri, $route)) {
						$controller = 'manufacturer';				
					}
					
					$route = $this->routes[$id_shop][Context::getContext()->language->id]['supplier_rule'];					
					if (!$controller && $this->module->isSupplierLink($uri, $route)) {
						$controller = 'supplier';				
					}
				}
            }
			
			if (!$controller) {
				$controller = $this->controller_not_found;
				if (!preg_match('/\.(gif|jpe?g|png|css|js|ico)$/i', parse_url($test_request_uri, PHP_URL_PATH))) {
					if ($this->empty_route) {
						$this->addRoute($this->empty_route['routeID'], $this->empty_route['rule'], $this->empty_route['controller'], Context::getContext()->language->id, array(), array(), $id_shop);
					}
					list($uri) = explode('?', $this->request_uri);
					if (isset($this->routes[$id_shop][Context::getContext()->language->id])) {
						foreach ($this->routes[$id_shop][Context::getContext()->language->id] as $id_route => $route) {
							
							if (in_array($id_route, array('product_rule', 'supplier_rule', 'category_rule', 'manufacturer_rule', 'cms_category_rule', 'cms_rule'))) {
								$exp = explode('_', $id_route);
								$name = 'SEOURL_'.strtoupper($exp[0]).($exp[1] == 'category' ? '_CATEGORY' : '');					
								
								if (Configuration::get($name))
									continue;
							}
							
							if (preg_match($route['regexp'], $uri, $m)) {
								foreach ($m as $k => $v) {
									if (!is_numeric($k)) {
										$_GET[$k] = $v;
									}
								}
								$controller = $route['controller'] ? $route['controller'] : $_GET['controller'];
								if (!empty($route['params'])) {
									foreach ($route['params'] as $k => $v) {
										$_GET[$k] = $v;
									}
								}
								if (preg_match('#module-([a-z0-9_-]+)-([a-z0-9_]+)$#i', $controller, $m)) {
									$_GET['module'] = $m[1];
									$_GET['fc'] = 'module';
									$controller = $m[2];
								}
								if (isset($_GET['fc']) && $_GET['fc'] == 'module') {
									$this->front_controller = self::FC_MODULE;
								}
								break;
							}
						}
					}
				}
            }
            if ($controller == 'index' || preg_match('/^\/index.php(?:\?.*)?$/', $this->request_uri)) {
                $controller = ($this->module->is_1_5() ? $this->default_controller : $this->useDefaultController());
            }
        }
		
        $this->controller = str_replace('-', '', $controller);
        $_GET['controller'] = $this->controller;
        return $this->controller;
    }
	
    public function validateRoute($route_id, $rule, &$errors = array())
    {
        $disabledRules = array(
            'category_rule',
            'product_rule',
            'supplier_rule',
            'manufacturer_rule',
            'cms_rule',
            'cms_category_rule'
        );

        foreach ($disabledRules as $ruleName) {
            unset($_POST['meta_settings_form']['url_schema'][$ruleName]);
        }

        if (Module::isEnabled('x13links') && in_array($route_id, $disabledRules)) {
            return true;
        }
        
        return parent::validateRoute($route_id, $rule, $errors);
    }
}