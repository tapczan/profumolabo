<?php
/**
 * Blog for PrestaShop module by Krystian Podemski from PrestaHome.
 *
 * @author    Krystian Podemski <krystian@prestahome.com>
 * @copyright Copyright (c) 2014-2019 Krystian Podemski - www.PrestaHome.com / www.Podemski.info
 * @license   You only can use module, nothing more!
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class PhBlogRecentPosts extends Module
{
    private $_html = '';
    public $fields_form;
    public $fields_value;
    public $validation_errors = array();

    public static $cfg_prefix = 'PH_RECENTPOSTS_';

    public function __construct()
    {
        $this->name = 'phblogrecentposts';
        $this->tab = 'front_office_features';
        $this->version = '2.0.3';
        $this->author = 'PrestaHome';
        $this->need_instance = 0;
        $this->is_configurable = 1;
        $this->ps_versions_compliancy['min'] = '1.7.0.0';
        $this->ps_versions_compliancy['max'] = _PS_VERSION_;
        $this->secure_key = Tools::encrypt($this->name);

        $this->bootstrap = true;

        if (!Module::isInstalled('ph_simpleblog') || !Module::isEnabled('ph_simpleblog')) {
            $this->warning = $this->l('You have to install and activate ph_simpleblog before use PhBlogRecentPosts');
        }

        parent::__construct();

        $this->displayName = $this->l('Blog for PrestaShop - Recent posts on the homepage');
        $this->description = $this->l('Widget to display recently added posts to Blog for PrestaShop module');

        $this->confirmUninstall = $this->l('Are you sure you want to delete this module?');

        $this->templateFile = 'module:phblogrecentposts/views/templates/hook/recent.tpl';
    }

    public function getDefaults()
    {
        return array(
            'LAYOUT' => 'grid',
            'GRID_COLUMNS' => 2,
            'FROM' => 'recent',
            'POSITION' => 'home',
            'NB' => 4,
        );
    }

    public function install()
    {
        // Hooks & Install
        return (parent::install()
            && $this->prepareModuleSettings()
            && $this->registerHook('displaySimpleBlogRecentPosts')
            && $this->registerHook('displayHome')
            && $this->registerHook('displayHeader'));
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        foreach ($this->getDefaults() as $key => $value) {
            Configuration::deleteByName(self::$cfg_prefix.$key);
        }

        return true;
    }

    public function prepareModuleSettings()
    {
        foreach ($this->getDefaults() as $key => $value) {
            Configuration::updateValue(self::$cfg_prefix.$key, $value, true);
        }

        return true;
    }

    public function prepareValueForLangs($value)
    {
        $languages = Language::getLanguages(false);

        $output = array();
        foreach ($languages as $language) {
            $output[$language['id_lang']] = $value;
        }

        return $output;
    }

    public function clearPostsCache()
    {
        return $this->_clearCache('*');
    }

    public function getContent()
    {
        $id_shop = (int) $this->context->shop->id;

        $this->initFieldsForm();
        if (isset($_POST['save'.$this->name])) {
            $multiLangFields = array();
            foreach ($this->getDefaults() as $field_name => $field_value) {
                if (is_array($field_value)) {
                    $multiLangFields[] = self::$cfg_prefix.$field_name;
                }
            }

            foreach ($_POST as $key => $value) {
                $fieldName = substr($key, 0, -2);

                if (in_array($fieldName, $multiLangFields)) {
                    $thisFieldValue = array();
                    foreach (Language::getLanguages(true) as $language) {
                        if (isset($_POST[$fieldName.'_'.$language['id_lang']])) {
                            $thisFieldValue[$language['id_lang']] = $_POST[$fieldName.'_'.$language['id_lang']];
                        }
                    }
                    $_POST[$fieldName] = $thisFieldValue;
                }
            }

            foreach ($this->getDefaults() as $field_name => $field_value) {
                if (is_array($field_value)) {
                    Configuration::updateValue($field_name, ${$field_name}, true);
                }
            }

            foreach ($this->fields_form as $form) {
                foreach ($form['form']['input'] as $field) {
                    if (isset($field['validation'])) {
                        $errors = array();
                        $value = Tools::getValue($field['name']);
                        if (isset($field['required']) && $field['required'] && $value == false && (string) $value != '0') {
                            $errors[] = sprintf(Tools::displayError('Field "%s" is required.'), $field['label']);
                        } elseif ($value) {
                            if (!Validate::{$field['validation']}($value)) {
                                $errors[] = sprintf(Tools::displayError('Field "%s" is invalid.'), $field['label']);
                            }
                        }

                        if ($value === false && isset($field['default_value'])) {
                            $value = $field['default_value'];
                        }

                        if (count($errors)) {
                            $this->validation_errors = array_merge($this->validation_errors, $errors);
                        } elseif ($value == false) {
                            switch ($field['validation']) {
                                case 'isUnsignedId':
                                case 'isUnsignedInt':
                                case 'isInt':
                                case 'isBool':
                                    $value = 0;
                                    break;
                                default:
                                    $value = '';
                                    break;
                            }
                            Configuration::updateValue($field['name'], $value, true);
                        } else {
                            $value = Tools::getValue($field['name']);
                            Configuration::updateValue($field['name'], $value, true);
                        }
                    } else {
                        $value = Tools::getValue($field['name']);
                        Configuration::updateValue($field['name'], $value, true);
                    }
                }
            }

            $this->clearPostsCache();

            if (count($this->validation_errors)) {
                $this->_html .= $this->displayError(implode('<br/>', $this->validation_errors));
            } else {
                $this->_html .= Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&conf=6&token='.Tools::getAdminTokenLite('AdminModules'));
            }
        }

        $helper = $this->initForm();
        foreach ($this->getDefaults() as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $lang => $val) {
                    $helper->fields_value[self::$cfg_prefix.$key][(int) $lang] = Tools::getValue(self::$cfg_prefix.$key.'_'.(int) $lang, Configuration::get(self::$cfg_prefix.$key, (int) $lang));
                }
            } else {
                $helper->fields_value[self::$cfg_prefix.$key] = Configuration::get(self::$cfg_prefix.$key);
            }
        }

        return $this->_html.$helper->generateForm($this->fields_form);
    }

    protected function initFieldsForm()
    {
        $from = array();
        $from[] = array('name' => $this->l('Recent posts'), 'id' => 'recent');
        $from[] = array('name' => $this->l('Featured posts'), 'id' => 'featured');

        $layout = array();
        $layout[] = array('name' => $this->l('Grid'), 'id' => 'grid');
        $layout[] = array('name' => $this->l('List'), 'id' => 'list');

        $available_categories = SimpleBlogCategory::getCategories($this->context->language->id, true, false);

        foreach ($available_categories as &$category) {
            $category['name'] = 'Category: '.$category['name'];
            if ($category['is_child']) {
                $category['name'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$category['name'];
            }

            $from[] = array('name' => $category['name'], 'id' => $category['id']);
        }

        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->displayName,
            ),
            'input' => array(
                array(
                    'type' => 'select',
                    'label' => $this->l('Display posts from:'),
                    'name' => self::$cfg_prefix.'FROM',
                    'validation' => 'isAnything',
                    'options' => array(
                        'query' => $from,
                        'id' => 'id',
                        'name' => 'name',
                    ),
                ),
                array(
                    'type' => 'select',
                    'name' => self::$cfg_prefix.'LAYOUT',
                    'label' => $this->l('Recent Posts layout:'),
                    'options' => array(
                        'query' => $layout,
                        'id' => 'id',
                        'name' => 'name',
                    ),
                ),
                array(
                    'type' => 'select',
                    'name' => self::$cfg_prefix.'GRID_COLUMNS',
                    'label' => $this->l('Grid columns:'),
                    'desc' => $this->l('Working only with "Recent Posts layout:" setup to "Grid"'),
                    'options' => array(
                        'query' => array(
                            array('name' => 2, 'id' => 2),
                            array('name' => 3, 'id' => 3),
                            array('name' => 4, 'id' => 4),
                            array('name' => 6, 'id' => 6),
                        ),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                ),
                array(
                    'name' => self::$cfg_prefix.'NB',
                    'type' => 'text',
                    'label' => $this->l('Number of posts:'),
                    'validation' => 'isUnsignedInt',
                    'desc' => $this->l('Default: 4'),
                    'class' => 'input-mini',
                    'size' => 2
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right',
            ),
        );
    }

    protected function initForm()
    {
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        foreach (Language::getLanguages(false) as $lang) {
            $helper->languages[] = array(
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0),
            );
        }

        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'save'.$this->name;
        $helper->toolbar_btn = array(
            'save' => array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
            ),
        );

        return $helper;
    }

    public function assignModuleVariables()
    {
        foreach ($this->getDefaults() as $key => $value) {
            if (is_array($value)) {
                $this->smarty->assign(strtolower($key), Configuration::get(self::$cfg_prefix.$key, $this->context->language->id));
            } else {
                $this->smarty->assign(strtolower($key), Configuration::get(self::$cfg_prefix.$key));
            }
        }
    }

    public function preparePosts($nb = 6, $from = null)
    {
        if (!Module::isInstalled('ph_simpleblog') || !Module::isEnabled('ph_simpleblog')) {
            return false;
        }

        require_once _PS_MODULE_DIR_.'ph_simpleblog/classes/BlogPostsFinder.php';

        $finder = new BlogPostsFinder;
        if ($from == 'featured') {
            $finder->setFeatured(true);
        } elseif ($from == 'recent') {
            // n/a
        } else {
            $finder->setIdCategory((int) $from);
        }

        $finder->setLimit((int) $nb);

        $posts = $finder->findPosts();

        return $posts;
    }

    public function prepareSimpleBlogPosts()
    {
        $posts = $this->preparePosts((int) Configuration::get(self::$cfg_prefix.'NB'), Configuration::get(self::$cfg_prefix.'FROM'));

        if (!$posts) {
            return;
        }

        $gridColumns = Configuration::get(self::$cfg_prefix.'GRID_COLUMNS');
        $blogLayout = Configuration::get(self::$cfg_prefix.'LAYOUT');

        $postFinder = new BlogPostsFinder;
        $postFinder->setSimpleResults(true);
        $nbBlogPosts = count($postFinder->findPosts());

        if ((int) Configuration::get(self::$cfg_prefix.'NB') == 1) {
            $posts = [$posts];
        }

        $this->context->smarty->assign(array(
            'blogTitle' => Configuration::get('PH_BLOG_MAIN_TITLE', (int) $this->context->language->id),
            'nbBlogPosts' => $nbBlogPosts,
            'blogLayout' => $blogLayout,
            'columns' => $gridColumns,
            'gallery_dir' => _MODULE_DIR_.'ph_simpleblog/galleries/',
            'recent_posts' => $posts,
            'tpl_path' => dirname(__FILE__).'/views/templates/hook/',
            'is_category' => false,
            'blogLink' => $this->context->link->getModuleLink('ph_simpleblog', 'list'),
            'isWarehouse' => Module::getInstanceByName('ph_simpleblog')->isWarehouse
        ));
    }

    public function hookDisplaySimpleBlogRecentPosts($params)
    {
        if (isset($params['template'])) {
            $this->prepareSimpleBlogPosts();
             return $this->display(__FILE__, $params['template'].'.tpl');
        } else {
            return $this->hookDisplayHome($params);
        }
    }

    public function hookDisplayHome($params)
    {
        if (!$this->isCached($this->templateFile, $this->getCacheId($this->name))) {
            $this->prepareSimpleBlogPosts();
        }

        return $this->fetch($this->templateFile, $this->getCacheId($this->name));
    }
}
