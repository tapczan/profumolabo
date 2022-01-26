<?php
/*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*
*  MODIFIED BY MYPRESTA.EU FOR PRESTASHOP 1.7 PURPOSES !
*
*/

if (!defined('_PS_VERSION_')) {
    exit;
}
require_once _PS_MODULE_DIR_ . '/myprestacomments/MyprestaComment.php';
require_once _PS_MODULE_DIR_ . '/myprestacomments/MyprestaCommentCriterion.php';

class myprestacomments extends Module
{
    const INSTALL_SQL_FILE = 'install.sql';

    private $_html = '';
    private $_postErrors = array();
    private $_filters = array();

    private $_myprestacommentsCriterionTypes = array();
    private $_baseUrl;

    public function __construct()
    {
        $this->name = 'myprestacomments';
        $this->tab = 'front_office_features';
        $this->version = '9.5.6';
        $this->author = 'MyPresta.eu';
        $this->mypresta_link = 'https://mypresta.eu/modules/advertising-and-marketing/free-product-reviews-comments.html';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->_setFilters();
        parent::__construct();

        $this->secure_key = Tools::encrypt($this->name);
        $this->displayName = $this->l('Product Comments');
        $this->description = $this->l('Allows users to post reviews and rate products on specific criteria.');

        $this->ps_versions_compliancy = array(
            'min' => '1.7.0.0',
            'max' => '1.7.99.99'
        );
        if (Module::isEnabled('productcomments')) {
            $this->context->controller->warnings[] = $this->l('Default productcomments is active') . ' ' . $this->l('To use this module you need to disable the official "productcomments" first');
            if (Module::disableByName('productcomments')) {
                if (Module::isEnabled('myprestacomments')) {
                    $this->context->controller->success[] = $this->l('We disabled this module automatically');
                }
            }
        }

        $this->checkforupdates(0, 0);
    }

    public function install($keep = true)
    {
        if ($keep) {
            if (!file_exists(dirname(__FILE__) . '/' . self::INSTALL_SQL_FILE)) {
                return false;
            } elseif (!$sql = file_get_contents(dirname(__FILE__) . '/' . self::INSTALL_SQL_FILE)) {
                return false;
            }
            $sql = str_replace(array(
                'PREFIX_',
                'ENGINE_TYPE'
            ), array(
                _DB_PREFIX_,
                _MYSQL_ENGINE_
            ), $sql);
            $sql = preg_split("/;\s*[\r\n]+/", trim($sql));

            foreach ($sql as $query) {
                if (!Db::getInstance()->execute(trim($query))) {
                    return false;
                }
            }
        }
        $this->maybeUpdateDatabase('product_comment', 'customer_email', 'varchar(200)', NULL);
        $this->maybeUpdateDatabase('product_comment', 'id_shop', 'int(5)', 1);
        $this->maybeUpdateDatabase('product_comment_criterion', 'id_shop', 'int(5)', 1);

        if (parent::install() == false ||
            !$this->registerHook('ActionAdminControllerSetMedia') ||
            !$this->registerHook('productFooter') ||
            !$this->registerHook('header') ||
            !$this->registerHook('displayProductExtraContent') ||
            !$this->registerHook('displayRightColumnProduct') ||
            !$this->registerHook('displayProductListReviews') ||
            !Configuration::updateValue('PRODUCT_COMMENTS_MINIMAL_TIME', 30) ||
            !Configuration::updateValue('PRODUCT_COMMENTS_ALLOW_GUESTS', 0) ||
            !Configuration::updateValue('PRODUCT_COMMENTS_MODERATE', 1)) {
            return false;
        }

        return true;
    }

    public function uninstall($keep = true)
    {
        if (!parent::uninstall() || ($keep && !$this->deleteTables()) || !Configuration::deleteByName('PRODUCT_COMMENTS_MODERATE') || !Configuration::deleteByName('PRODUCT_COMMENTS_ALLOW_GUESTS') || !Configuration::deleteByName('PRODUCT_COMMENTS_MINIMAL_TIME') || !$this->unregisterHook('displayRightColumnProduct') || !$this->unregisterHook('header') || !$this->unregisterHook('productFooter') || !$this->unregisterHook('top') || !$this->unregisterHook('displayProductListReviews')) {
            return false;
        }

        return true;
    }

    public function reset()
    {
        if (!$this->uninstall(false)) {
            return false;
        }
        if (!$this->install(false)) {
            return false;
        }

        return true;
    }

    private function maybeUpdateDatabase($table, $column, $type = "int(8)", $default = "1", $null = "NULL")
    {
        $sql = 'DESCRIBE ' . _DB_PREFIX_ . $table;
        $columns = Db::getInstance()->executeS($sql);
        $found = false;
        foreach ($columns as $col) {
            if ($col['Field'] == $column) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            if (!Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . $table . '` ADD `' . $column . '` ' . $type . ' DEFAULT ' . $default . ' ' . $null)) {
                return false;
            }
        }
        return true;
    }

    public function deleteTables()
    {
        return true;
        /**
         * return Db::getInstance()->execute('
         * DROP TABLE IF EXISTS
         * `' . _DB_PREFIX_ . 'product_comment`,
         * `' . _DB_PREFIX_ . 'product_comment_criterion`,
         * `' . _DB_PREFIX_ . 'product_comment_criterion_product`,
         * `' . _DB_PREFIX_ . 'product_comment_criterion_lang`,
         * `' . _DB_PREFIX_ . 'product_comment_criterion_category`,
         * `' . _DB_PREFIX_ . 'product_comment_grade`,
         * `' . _DB_PREFIX_ . 'product_comment_usefulness`,
         * `' . _DB_PREFIX_ . 'product_comment_report`');
         **/
    }

    public function getCacheId($id_product = null)
    {
        return parent::getCacheId() . '|' . (int)$id_product;
    }

    protected function _postProcess()
    {
        $this->_setFilters();

        if (Tools::isSubmit('submitModerate')) {
            Configuration::updateValue('SHOW_FIVE_STARS', (int)Tools::getValue('SHOW_FIVE_STARS'));
            Configuration::updateValue('MYPRESTACOMMENTS_ABUSE', (int)Tools::getValue('MYPRESTACOMMENTS_ABUSE'));
            Configuration::updateValue('MYPRESTACOMMENTS_USEFUL', (int)Tools::getValue('MYPRESTACOMMENTS_USEFUL'));
            Configuration::updateValue('PRODUCT_COMMENTS_LIST_ALL', (int)Tools::getValue('PRODUCT_COMMENTS_LIST_ALL'));
            Configuration::updateValue('PRODUCT_COMMENTS_DGRADE', (int)Tools::getValue('PRODUCT_COMMENTS_DGRADE'));
            Configuration::updateValue('PRODUCT_COMMENTS_GDPRCMS', (int)Tools::getValue('PRODUCT_COMMENTS_GDPRCMS'));
            Configuration::updateValue('PRODUCT_COMMENTS_MODERATE', (int)Tools::getValue('PRODUCT_COMMENTS_MODERATE'));
            Configuration::updateValue('PRODUCT_COMMENTS_GDPR', (int)Tools::getValue('PRODUCT_COMMENTS_GDPR'));
            Configuration::updateValue('PRODUCT_COMMENTS_ALLOW_GUESTS', (int)Tools::getValue('PRODUCT_COMMENTS_ALLOW_GUESTS'));
            Configuration::updateValue('PRODUCT_COMMENTS_MINIMAL_TIME', (int)Tools::getValue('PRODUCT_COMMENTS_MINIMAL_TIME'));
            Configuration::updateValue('PRODUCT_COMMENTS_LIST', (int)Tools::getValue('PRODUCT_COMMENTS_LIST'));
            Configuration::updateValue('PRODUCT_COMMENTS_MIN_TITLE', (int)Tools::getValue('PRODUCT_COMMENTS_MIN_TITLE', 20));
            Configuration::updateValue('PRODUCT_COMMENTS_MIN_BODY', (int)Tools::getValue('PRODUCT_COMMENTS_MIN_BODY', 50));
            Configuration::updateValue('PRODUCT_COMMENT_DATE', (int)Tools::getValue('PRODUCT_COMMENT_DATE', 1));
            Configuration::updateValue('PRODUCT_COMMENT_TITLE', (int)Tools::getValue('PRODUCT_COMMENT_TITLE', 1));
            Configuration::updateValue('PRODUCT_COMMENT_BODY', (int)Tools::getValue('PRODUCT_COMMENT_BODY', 1));
            Configuration::updateValue('PRODUCT_COMMENT_AUTHOR', (int)Tools::getValue('PRODUCT_COMMENT_AUTHOR', 1));
            Configuration::updateValue('PRODUCT_COMMENTS_POSITION', Tools::getValue('PRODUCT_COMMENTS_POSITION', 'productFooter'));
            Configuration::updateValue('PRODUCT_COMMENTS_STARS', Tools::getValue('PRODUCT_COMMENTS_STARS', '32'));
            Configuration::updateValue('PRODUCT_COMMENTS_NB_SHOW', Tools::getValue('PRODUCT_COMMENTS_NB_SHOW', '0'));


            $this->_html .= '<div class="conf confirm alert alert-success">' . $this->l('Settings updated') . '</div>';
        } elseif (Tools::isSubmit('myprestacomments')) {
            $id_product_comment = (int)Tools::getValue('id_product_comment');
            $comment = new MyprestaComment($id_product_comment);
            $comment->validate();
            MyprestaComment::deleteReports($id_product_comment);
        } elseif (Tools::isSubmit('deletemyprestacomments')) {
            $id_product_comment = (int)Tools::getValue('id_product_comment');
            $comment = new MyprestaComment($id_product_comment);
            $comment->delete();
        } elseif (Tools::isSubmit('submitEditCriterion')) {
            $criterion = new MyprestaCommentCriterion((int)Tools::getValue('id_product_comment_criterion'));
            $criterion->id_product_comment_criterion_type = Tools::getValue('id_product_comment_criterion_type');
            $criterion->active = Tools::getValue('active');
            $criterion->id_shop = Context::getContext()->shop->id;

            $languages = Language::getLanguages();
            $name = array();
            foreach ($languages as $key => $value) {
                $name[$value['id_lang']] = Tools::getValue('name_' . $value['id_lang']);
            }
            $criterion->name = $name;

            $criterion->save();

            // Clear before reinserting data
            $criterion->deleteCategories();
            $criterion->deleteProducts();
            if ($criterion->id_product_comment_criterion_type == 2) {
                if ($categories = Tools::getValue('categoryBox')) {
                    if (count($categories)) {
                        foreach ($categories as $id_category) {
                            $criterion->addCategory((int)$id_category);
                        }
                    }
                }
            } elseif ($criterion->id_product_comment_criterion_type == 3) {
                if ($products = Tools::getValue('ids_product')) {
                    if (count($products)) {
                        foreach ($products as $product) {
                            $criterion->addProduct((int)$product);
                        }
                    }
                }
            }
            if ($criterion->save()) {
                Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminModules') . '&configure=' . $this->name . '&conf=4');
            } else {
                $this->_html .= '<div class="conf confirm alert alert-danger">' . $this->l('The criterion could not be saved') . '</div>';
            }
        } elseif (Tools::isSubmit('deletemyprestacommentscriterion')) {
            $MyprestaCommentCriterion = new MyprestaCommentCriterion((int)Tools::getValue('id_product_comment_criterion'));
            if ($MyprestaCommentCriterion->id) {
                if ($MyprestaCommentCriterion->delete()) {
                    $this->_html .= '<div class="conf confirm alert alert-success">' . $this->l('Criterion deleted') . '</div>';
                }
            }
        } elseif (Tools::isSubmit('statusmyprestacommentscriterion')) {
            $criterion = new MyprestaCommentCriterion((int)Tools::getValue('id_product_comment_criterion'));
            if ($criterion->id) {
                $criterion->active = (int)(!$criterion->active);
                $criterion->save();
            }
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&conf=4&module_name=' . $this->name);
        } elseif ($id_product_comment = (int)Tools::getValue('approveComment')) {
            $comment = new MyprestaComment($id_product_comment);
            $comment->validate();
        } elseif ($id_product_comment = (int)Tools::getValue('noabuseComment')) {
            MyprestaComment::deleteReports($id_product_comment);
        }
        $this->_clearcache('mypresta_myprestacomments_reviews_list.tpl');
    }

    public function displayAdvert()
    {
        return $this->display(__file__, 'views/templates/admin/advert.tpl');
    }

    public function getContent()
    {
        $this->maybeUpdateDatabase('product_comment', 'customer_email', 'varchar(200)', NULL);
        $this->maybeUpdateDatabase('product_comment', 'id_shop', 'int(5)', 1);
        $this->maybeUpdateDatabase('product_comment_criterion', 'id_shop', 'int(5)', 1);

        include_once dirname(__FILE__) . '/MyprestaComment.php';
        include_once dirname(__FILE__) . '/MyprestaCommentCriterion.php';

        $this->_html = '';
        if (Tools::isSubmit('updatemyprestacommentscriterion')) {
            $this->_html .= $this->renderCriterionForm((int)Tools::getValue('id_product_comment_criterion'));
        } else {
            $this->_postProcess();
            $this->_html .= $this->renderConfigForm();
            $this->_html .= $this->renderModerateLists();
            $this->_html .= $this->renderCriterionList();
            $this->_html .= $this->renderCommentsList();
        }

        $this->_setBaseUrl();
        $this->_myprestacommentsCriterionTypes = MyprestaCommentCriterion::getTypes();

        $this->context->controller->addJs($this->_path . 'js/moderate.js');

        return $this->displayAdvert() . $this->_html . $this->checkforupdates(0, 1);
    }

    public function psversion($part = 1)
    {
        $version = _PS_VERSION_;
        $exp = $explode = explode(".", $version);
        if ($part == 1) {
            return $exp[1];
        }
        if ($part == 2) {
            return $exp[2];
        }
        if ($part == 3) {
            return $exp[3];
        }
    }

    private function _setBaseUrl()
    {
        $this->_baseUrl = 'index.php?';
        foreach ($_GET as $k => $value) {
            if (!in_array($k, array(
                'deleteCriterion',
                'editCriterion'
            ))
            ) {
                $this->_baseUrl .= $k . '=' . $value . '&';
            }
        }
        $this->_baseUrl = rtrim($this->_baseUrl, '&');
    }

    public function renderConfigForm()
    {
        $fields_form_1 = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Configuration'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->l('Position of comments'),
                        'name' => 'PRODUCT_COMMENTS_POSITION',
                        'options' => array(
                            'query' => array(array('id_position' => 'productFooter', 'name' => $this->l('Product Footer')), array('id_position' => 'extraContent', 'name' => $this->l('Product tabs'))),
                            'id' => 'id_position',
                            'name' => 'name'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'is_bool' => true,
                        //retro compat 1.5
                        'label' => $this->l('All reviews must be validated by an employee'),
                        'name' => 'PRODUCT_COMMENTS_MODERATE',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'is_bool' => true,
                        //retro compat 1.5
                        'label' => $this->l('Allow guest reviews'),
                        'name' => 'PRODUCT_COMMENTS_ALLOW_GUESTS',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('The minimum number of characters in the title'),
                        'name' => 'PRODUCT_COMMENTS_MIN_TITLE',
                        'class' => 'fixed-width-xs',
                        'suffix' => 'characters',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('The minimum number of characters in comment'),
                        'name' => 'PRODUCT_COMMENTS_MIN_BODY',
                        'class' => 'fixed-width-xs',
                        'suffix' => 'characters',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Minimum time between 2 reviews from the same user'),
                        'name' => 'PRODUCT_COMMENTS_MINIMAL_TIME',
                        'class' => 'fixed-width-xs',
                        'suffix' => 'seconds',
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Stars size'),
                        'name' => 'PRODUCT_COMMENTS_STARS',
                        'options' => array(
                            'query' => array(array('id_position' => '32', 'name' => $this->l('32px')), array('id_position' => '24', 'name' => $this->l('24px')), array('id_position' => '16', 'name' => $this->l('16px'))),
                            'id' => 'id_position',
                            'name' => 'name'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'is_bool' => true,
                        'label' => $this->l('Show reviews counter and stars on list of products'),
                        'name' => 'PRODUCT_COMMENTS_LIST',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'is_bool' => true,
                        'label' => $this->l('Show number of comments on list of products'),
                        'name' => 'PRODUCT_COMMENTS_NB_SHOW',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'is_bool' => true,
                        'desc' => $this->l('By default module does not display stars for items without reviews. Activate this option to show stars also for items without reviews. This option is applicable to list of products only.'),
                        'label' => $this->l('Show reviews counter always (on list of products)'),
                        'name' => 'PRODUCT_COMMENTS_LIST_ALL',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'is_bool' => true,
                        'desc' => $this->l('Show five stars for products without reviews'),
                        'label' => $this->l('When you will show "stars" for products without any review you can show 5 stars (not 0 that can indicate bad review)'),
                        'name' => 'SHOW_FIVE_STARS',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'is_bool' => true,
                        'label' => $this->l('GDPR Compliant'),
                        'name' => 'PRODUCT_COMMENTS_GDPR',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Privacy policy page'),
                        'desc' => $this->l('Choose a CMS page with privacy policy details for GDPR purposes'),
                        'name' => 'PRODUCT_COMMENTS_GDPRCMS',
                        'class' => 't',
                        'options' => array(
                            'query' => CMS::getCmsPages($this->context->language->id, null, false),
                            'id' => 'id_cms',
                            'name' => 'meta_title'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'is_bool' => true,
                        'label' => $this->l('Display detailed grade'),
                        'desc' => $this->l('If you use various grade criterions module displays only overal grade by default. This option - when active will show also detailed grade info'),
                        'name' => 'PRODUCT_COMMENTS_DGRADE',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'is_bool' => true,
                        'label' => $this->l('Show comment date'),
                        'name' => 'PRODUCT_COMMENT_DATE',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'is_bool' => true,
                        'label' => $this->l('Show comment title'),
                        'name' => 'PRODUCT_COMMENT_TITLE',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'is_bool' => true,
                        'label' => $this->l('Show comment contents'),
                        'name' => 'PRODUCT_COMMENT_BODY',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'is_bool' => true,
                        'label' => $this->l('Show comment auhtor name'),
                        'name' => 'PRODUCT_COMMENT_AUTHOR',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'is_bool' => true,
                        'label' => $this->l('Usefulness'),
                        'desc' => $this->l('Allow customers to mark review as useful by customer'),
                        'name' => 'MYPRESTACOMMENTS_USEFUL',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'is_bool' => true,
                        'label' => $this->l('Abuse'),
                        'desc' => $this->l('Allow customers to report review as abusive'),
                        'name' => 'MYPRESTACOMMENTS_ABUSE',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right',
                    'name' => 'submitModerate',
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->name;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitProducCommentsConfiguration';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );
        $this->context->controller->informations[] = $this->l('Do you know that module has page with all added comments? ') . '<strong><a href="' . $this->context->link->getModuleLink('myprestacomments', 'feedback') . '">' . $this->l('Check it here') . '</a></strong>';
        return $helper->generateForm(array($fields_form_1));
    }

    public function renderModerateLists()
    {
        $return = null;

        if (Configuration::get('PRODUCT_COMMENTS_MODERATE')) {
            $comments = MyprestaComment::getByValidate(0, false);

            $fields_list = $this->getStandardFieldList();

            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                $return .= '<h1>' . $this->l('Reviews waiting for approval') . '</h1>';
                $actions = array(
                    'enable',
                    'delete'
                );
            } else {
                $actions = array(
                    'approve',
                    'delete'
                );
            }

            $helper = new HelperList();
            $helper->shopLinkType = '';
            $helper->simple_header = true;
            $helper->actions = $actions;
            $helper->show_toolbar = false;
            $helper->module = $this;
            $helper->listTotal = count($comments);
            $helper->identifier = 'id_product_comment';
            $helper->title = $this->l('Reviews waiting for approval');
            $helper->table = $this->name;
            $helper->token = Tools::getAdminTokenLite('AdminModules');
            $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
            //$helper->tpl_vars = array('priority' => array($this->l('High'), $this->l('Medium'), $this->l('Low')));

            $return .= $helper->generateList($comments, $fields_list);
        }

        $comments = MyprestaComment::getReportedComments();

        $fields_list = $this->getStandardFieldList();

        if (version_compare(_PS_VERSION_, '1.6', '<')) {
            $return .= '<h1>' . $this->l('Reported Reviews') . '</h1>';
            $actions = array(
                'enable',
                'delete'
            );
        } else {
            $actions = array(
                'delete',
                'noabuse'
            );
        }

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->actions = $actions;
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->listTotal = count($comments);
        $helper->identifier = 'id_product_comment';
        $helper->title = $this->l('Reported Reviews');
        $helper->table = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        //$helper->tpl_vars = array('priority' => array($this->l('High'), $this->l('Medium'), $this->l('Low')));

        $return .= $helper->generateList($comments, $fields_list);

        return $return;
    }

    public function renderCriterionList()
    {
        include_once dirname(__FILE__) . '/MyprestaCommentCriterion.php';

        $criterions = MyprestaCommentCriterion::getCriterions($this->context->language->id, false, false);

        $fields_list = array(
            'id_product_comment_criterion' => array(
                'title' => $this->l('ID'),
                'type' => 'text',
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'type' => 'text',
            ),
            'type_name' => array(
                'title' => $this->l('Type'),
                'type' => 'text',
            ),
            'id_shop' => array(
                'title' => $this->l('Shop ID'),
                'type' => 'text',
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'active' => 'status',
                'type' => 'bool',
            ),
        );

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->actions = array(
            'edit',
            'delete'
        );
        $helper->show_toolbar = true;
        $helper->toolbar_btn['new'] = array(
            'href' => $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->name . '&module_name=' . $this->name . '&updatemyprestacommentscriterion',
            'desc' => $this->l('Add New Criterion', null, null, false),
        );
        $helper->module = $this;
        $helper->identifier = 'id_product_comment_criterion';
        $helper->title = $this->l('Review Criteria');
        $helper->table = $this->name . 'criterion';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        //$helper->tpl_vars = array('priority' => array($this->l('High'), $this->l('Medium'), $this->l('Low')));

        return $helper->generateList($criterions, $fields_list);
    }

    public function renderCommentsList()
    {
        $comments = MyprestaComment::getByValidate(1, false);
        $moderate = Configuration::get('PRODUCT_COMMENTS_MODERATE');
        if (empty($moderate)) {
            $comments = array_merge($comments, MyprestaComment::getByValidate(0, false));
        }

        $fields_list = $this->getStandardFieldList();

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->actions = array('delete');
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->listTotal = count($comments);
        $helper->identifier = 'id_product_comment';
        $helper->title = $this->l('Approved Reviews');
        $helper->table = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        //$helper->tpl_vars = array('priority' => array($this->l('High'), $this->l('Medium'), $this->l('Low')));

        return $helper->generateList($comments, $fields_list);
    }

    public function getConfigFieldsValues()
    {
        return array(
            'SHOW_FIVE_STARS' => Tools::getValue('SHOW_FIVE_STARS', Configuration::get('SHOW_FIVE_STARS')),
            'MYPRESTACOMMENTS_USEFUL' => Tools::getValue('MYPRESTACOMMENTS_USEFUL', Configuration::get('MYPRESTACOMMENTS_USEFUL')),
            'MYPRESTACOMMENTS_ABUSE' => Tools::getValue('MYPRESTACOMMENTS_ABUSE', Configuration::get('MYPRESTACOMMENTS_ABUSE')),
            'PRODUCT_COMMENTS_LIST_ALL' => Tools::getValue('PRODUCT_COMMENTS_LIST_ALL', Configuration::get('PRODUCT_COMMENTS_LIST_ALL')),
            'PRODUCT_COMMENTS_GDPRCMS' => Tools::getValue('PRODUCT_COMMENTS_GDPRCMS', Configuration::get('PRODUCT_COMMENTS_GDPRCMS')),
            'PRODUCT_COMMENTS_GDPR' => Tools::getValue('PRODUCT_COMMENTS_GDPR', Configuration::get('PRODUCT_COMMENTS_GDPR')),
            'PRODUCT_COMMENTS_MODERATE' => Tools::getValue('PRODUCT_COMMENTS_MODERATE', Configuration::get('PRODUCT_COMMENTS_MODERATE')),
            'PRODUCT_COMMENTS_ALLOW_GUESTS' => Tools::getValue('PRODUCT_COMMENTS_ALLOW_GUESTS', Configuration::get('PRODUCT_COMMENTS_ALLOW_GUESTS')),
            'PRODUCT_COMMENTS_MINIMAL_TIME' => Tools::getValue('PRODUCT_COMMENTS_MINIMAL_TIME', Configuration::get('PRODUCT_COMMENTS_MINIMAL_TIME')),
            'PRODUCT_COMMENTS_LIST' => Tools::getValue('PRODUCT_COMMENTS_LIST', Configuration::get('PRODUCT_COMMENTS_LIST')),
            'PRODUCT_COMMENTS_DGRADE' => Tools::getValue('PRODUCT_COMMENTS_DGRADE', Configuration::get('PRODUCT_COMMENTS_DGRADE')),
            'PRODUCT_COMMENTS_MIN_BODY' => Tools::getValue('PRODUCT_COMMENTS_MIN_BODY', Configuration::get('PRODUCT_COMMENTS_MIN_BODY')),
            'PRODUCT_COMMENTS_MIN_TITLE' => Tools::getValue('PRODUCT_COMMENTS_MIN_TITLE', Configuration::get('PRODUCT_COMMENTS_MIN_TITLE')),
            'PRODUCT_COMMENT_BODY' => Tools::getValue('PRODUCT_COMMENT_BODY', Configuration::get('PRODUCT_COMMENT_BODY')),
            'PRODUCT_COMMENT_TITLE' => Tools::getValue('PRODUCT_COMMENT_TITLE', Configuration::get('PRODUCT_COMMENT_TITLE')),
            'PRODUCT_COMMENT_DATE' => Tools::getValue('PRODUCT_COMMENT_DATE', Configuration::get('PRODUCT_COMMENT_DATE')),
            'PRODUCT_COMMENT_AUTHOR' => Tools::getValue('PRODUCT_COMMENT_AUTHOR', Configuration::get('PRODUCT_COMMENT_AUTHOR')),
            'PRODUCT_COMMENTS_POSITION' => Tools::getValue('PRODUCT_COMMENTS_POSITION', Configuration::get('PRODUCT_COMMENTS_POSITION')),
            'PRODUCT_COMMENTS_STARS' => Tools::getValue('PRODUCT_COMMENTS_STARS', Configuration::get('PRODUCT_COMMENTS_STARS')),
            'PRODUCT_COMMENTS_NB_SHOW' => Tools::getValue('PRODUCT_COMMENTS_NB_SHOW', Configuration::get('PRODUCT_COMMENTS_NB_SHOW')),
        );
    }

    public function getCriterionFieldsValues($id = 0)
    {
        $criterion = new MyprestaCommentCriterion($id);

        return array(
            'name' => $criterion->name,
            'id_product_comment_criterion_type' => $criterion->id_product_comment_criterion_type,
            'active' => $criterion->active,
            'id_product_comment_criterion' => $criterion->id,
        );
    }

    public function getStandardFieldList()
    {
        return array(
            'id_product_comment' => array(
                'title' => $this->l('ID'),
                'type' => 'text',
            ),
            'title' => array(
                'title' => $this->l('Review title'),
                'type' => 'text',
            ),
            'content' => array(
                'title' => $this->l('Review'),
                'type' => 'text',
            ),
            'grade' => array(
                'title' => $this->l('Rating'),
                'type' => 'text',
                'suffix' => '/5',
            ),
            'customer_name' => array(
                'title' => $this->l('Author'),
                'type' => 'text',
            ),
            'name' => array(
                'title' => $this->l('Product'),
                'type' => 'text',
            ),
            'date_add' => array(
                'title' => $this->l('Time of publication'),
                'type' => 'date',
            ),
        );
    }

    public function renderCriterionForm($id_criterion = 0)
    {
        $types = MyprestaCommentCriterion::getTypes();
        $query = array();
        foreach ($types as $key => $value) {
            $query[] = array(
                'id' => $key,
                'label' => $value,
            );
        }

        $criterion = new MyprestaCommentCriterion((int)$id_criterion);
        $selected_categories = $criterion->getCategories();

        $product_table_values = Product::getSimpleProducts($this->context->language->id);
        $selected_products = $criterion->getProducts();
        foreach ($product_table_values as $key => $product) {
            if (false !== array_search($product['id_product'], $selected_products)) {
                $product_table_values[$key]['selected'] = 1;
            }
        }

        if (version_compare(_PS_VERSION_, '1.6', '<')) {
            $field_category_tree = array(
                'type' => 'categories_select',
                'name' => 'categoryBox',
                'label' => $this->l('Criterion will be restricted to the following categories'),
                'category_tree' => $this->initCategoriesAssociation(null, $id_criterion),
            );
        } else {
            $field_category_tree = array(
                'type' => 'categories',
                'label' => $this->l('Criterion will be restricted to the following categories'),
                'name' => 'categoryBox',
                'desc' => $this->l('Mark the boxes of categories to which this criterion applies.'),
                'tree' => array(
                    'use_search' => false,
                    'id' => 'categoryBox',
                    'use_checkbox' => true,
                    'selected_categories' => $selected_categories,
                ),
                //retro compat 1.5 for category tree
                'values' => array(
                    'trads' => array(
                        'Root' => Category::getTopCategory(),
                        'selected' => $this->l('Selected'),
                        'Collapse All' => $this->l('Collapse All'),
                        'Expand All' => $this->l('Expand All'),
                        'Check All' => $this->l('Check All'),
                        'Uncheck All' => $this->l('Uncheck All'),
                    ),
                    'selected_cat' => $selected_categories,
                    'input_name' => 'categoryBox[]',
                    'use_radio' => false,
                    'use_search' => false,
                    'disabled_categories' => array(),
                    'top_category' => Category::getTopCategory(),
                    'use_context' => true,
                ),
            );
        }

        $fields_form_1 = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Add new criterion'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'id_product_comment_criterion',
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'label' => $this->l('Criterion name'),
                        'name' => 'name',
                    ),
                    array(
                        'type' => 'select',
                        'name' => 'id_product_comment_criterion_type',
                        'label' => $this->l('Application scope of the criterion'),
                        'options' => array(
                            'query' => $query,
                            'id' => 'id',
                            'name' => 'label',
                        ),
                    ),
                    $field_category_tree,
                    array(
                        'type' => 'products',
                        'label' => $this->l('The criterion will be restricted to the following products'),
                        'name' => 'ids_product',
                        'values' => $product_table_values,
                    ),
                    array(
                        'type' => 'switch',
                        'is_bool' => true,
                        //retro compat 1.5
                        'label' => $this->l('Active'),
                        'name' => 'active',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right',
                    'name' => 'submitEditCriterion',
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->name;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitEditCriterion';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getCriterionFieldsValues($id_criterion),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($fields_form_1));
    }

    private function _checkDeleteComment()
    {
        $action = Tools::getValue('delete_action');
        if (empty($action) === false) {
            $product_comments = Tools::getValue('delete_id_product_comment');

            if (count($product_comments)) {
                if ($action == 'delete') {
                    foreach ($product_comments as $id_product_comment) {
                        if (!$id_product_comment) {
                            continue;
                        }
                        $comment = new MyprestaComment((int)$id_product_comment);
                        $comment->delete();
                        MyprestaComment::deleteGrades((int)$id_product_comment);
                    }
                }
            }
        }
    }

    private function _setFilters()
    {
        $this->_filters = array(
            'page' => (string)Tools::getValue('submitFilter' . $this->name),
            'pagination' => (string)Tools::getValue($this->name . '_pagination'),
            'filter_id' => (string)Tools::getValue($this->name . 'Filter_id_product_comment'),
            'filter_content' => (string)Tools::getValue($this->name . 'Filter_content'),
            'filter_customer_name' => (string)Tools::getValue($this->name . 'Filter_customer_name'),
            'filter_grade' => (string)Tools::getValue($this->name . 'Filter_grade'),
            'filter_name' => (string)Tools::getValue($this->name . 'Filter_name'),
            'filter_date_add' => (string)Tools::getValue($this->name . 'Filter_date_add'),
        );
    }

    public function displayApproveLink($token, $id, $name = null)
    {
        $this->smarty->assign(array(
            'href' => $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->name . '&module_name=' . $this->name . '&approveComment=' . $id,
            'action' => $this->l('Approve'),
        ));

        return $this->display(__FILE__, 'views/templates/admin/list_action_approve.tpl');
    }

    public function displayNoabuseLink($token, $id, $name = null)
    {
        $this->smarty->assign(array(
            'href' => $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->name . '&module_name=' . $this->name . '&noabuseComment=' . $id,
            'action' => $this->l('Not abusive'),
        ));

        return $this->display(__FILE__, 'views/templates/admin/list_action_noabuse.tpl');
    }

    public function hookDisplayProductListReviews($params)
    {
        $id_product = (int)$params['product']['id_product'];
        if (!$this->isCached('module:myprestacomments/mypresta_myprestacomments_reviews_list.tpl', $this->getCacheId($id_product . '-list'))) {
            $average = MyprestaComment::getAverageGrade($id_product);
            $this->smarty->assign(array(
                'widget_type' => 'list',
                'product' => $params['product'],
                'averageTotal' => round($average['grade']),
                'ratings' => MyprestaComment::getRatings($id_product),
                'PRODUCT_COMMENTS_LIST_ALL' => Configuration::get('PRODUCT_COMMENTS_LIST_ALL'),
                'SHOW_FIVE_STARS' => Configuration::get('SHOW_FIVE_STARS'),
                'nbComments' => (int)MyprestaComment::getCommentNumber($id_product),
            ));
        }

        return $this->fetch('module:myprestacomments/mypresta_myprestacomments_reviews_list.tpl', $this->getCacheId($id_product . '-list'));
    }

    public function hookdisplayProductExtraContent($params)
    {
        if (Configuration::get('PRODUCT_COMMENTS_POSITION') != 'extraContent') {
            return array();
        }

        if (Tools::getValue('ajax', 'false') == 'false') {
            $tabz[] = (new PrestaShop\PrestaShop\Core\Product\ProductExtraContent())->setTitle($this->l('Reviews'))->setContent($this->renderCommentsForm($params));
            return $tabz;
        } else {
            return array();
        }
    }

    public function renderCommentsForm($params)
    {
        if (Tools::getValue('action') == 'quickview') {
            return false;
        }
        $id_guest = (!$id_customer = (int)$this->context->cookie->id_customer) ? (int)$this->context->cookie->id_guest : false;
        $customerComment = MyprestaComment::getByCustomer((int)(Tools::getValue('id_product')), (int)$this->context->customer->id, true, (int)$id_guest);

        $averages = MyprestaComment::getAveragesByProduct((int)Tools::getValue('id_product'), $this->context->language->id);
        $averageTotal = 0;
        foreach ($averages as $average) {
            $averageTotal += (float)($average);
        }
        $averageTotal = count($averages) ? ($averageTotal / count($averages)) : 0;

        $product = $this->context->controller->getProduct();
        $image = Product::getCover((int)Tools::getValue('id_product'));
        $cover_image = $this->context->link->getImageLink($product->link_rewrite, $image['id_image'], 'medium_default');

        $this->context->smarty->assign(array(
            'logged' => $this->context->customer->isLogged(true),
            'action_url' => '',
            'link' => $this->context->link,
            'myprestacomments_product' => $product,
            'comments' => MyprestaComment::getByProduct((int)Tools::getValue('id_product'), 1, null, $this->context->customer->id),
            'criterions' => MyprestaCommentCriterion::getByProduct((int)Tools::getValue('id_product'), $this->context->language->id),
            'averages' => $averages,
            'product_comment_path' => $this->_path,
            'averageTotal' => $averageTotal,
            'allow_guests' => (int)Configuration::get('PRODUCT_COMMENTS_ALLOW_GUESTS'),
            'PRODUCT_COMMENTS_DGRADE' => (int)Configuration::get('PRODUCT_COMMENTS_DGRADE'),
            'PRODUCT_COMMENTS_GDPR' => (int)Configuration::get('PRODUCT_COMMENTS_GDPR'),
            'PRODUCT_COMMENTS_GDPRCMS' => (int)Configuration::get('PRODUCT_COMMENTS_GDPRCMS'),
            'too_early' => ($customerComment && (strtotime($customerComment['date_add']) + Configuration::get('PRODUCT_COMMENTS_MINIMAL_TIME')) > time()),
            'delay' => Configuration::get('PRODUCT_COMMENTS_MINIMAL_TIME'),
            'id_product_comment_form' => (int)Tools::getValue('id_product'),
            'secure_key' => $this->secure_key,
            'MyprestaComment_cover' => (int)Tools::getValue('id_product') . '-' . (int)$image['id_image'],
            'MyprestaComment_cover_image' => $cover_image,
            'mediumSize' => Image::getSize(ImageType::getFormattedName('medium')),
            'nbComments' => (int)MyprestaComment::getCommentNumber((int)Tools::getValue('id_product')),
            'myprestacomments_controller_url' => $this->context->link->getModuleLink('myprestacomments'),
            'myprestacomments_url_rewriting_activated' => (int)Configuration::get('PS_REWRITING_SETTINGS'),
            'moderation_active' => (int)Configuration::get('PRODUCT_COMMENTS_MODERATE'),
            'PRODUCT_COMMENT_DATE' => Configuration::get('PRODUCT_COMMENT_DATE'),
            'PRODUCT_COMMENT_TITLE' => Configuration::get('PRODUCT_COMMENT_TITLE'),
            'PRODUCT_COMMENT_BODY' => Configuration::get('PRODUCT_COMMENT_BODY'),
            'PRODUCT_COMMENT_AUTHOR' => Configuration::get('PRODUCT_COMMENT_AUTHOR'),
        ));

        //$this->context->controller->pagination((int) MyprestaComment::getCommentNumber((int) Tools::getValue('id_product')));

        return $this->display(__FILE__, '/mypresta_myprestacomments.tpl');
    }

    public function hookProductFooter($params)
    {
        if (Configuration::get('PRODUCT_COMMENTS_POSITION') != 'productFooter') {
            return;
        }
        return $this->renderCommentsForm($params);
    }

    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path . 'js/jquery.rating.pack.js');
        $this->context->controller->addJS($this->_path . 'js/jquery.textareaCounter.plugin.js');
        $this->context->controller->addJS($this->_path . 'js/myprestacomments.js');
        $this->context->controller->addCSS($this->_path . 'myprestacomments.css', 'all');
        if (Configuration::get('PRODUCT_COMMENTS_STARS') == 32) {
            $this->context->controller->addCSS($this->_path . 'myprestacomments32.css', 'all');
        } else if (Configuration::get('PRODUCT_COMMENTS_STARS') == 24) {
            $this->context->controller->addCSS($this->_path . 'myprestacomments24.css', 'all');
        } else if (Configuration::get('PRODUCT_COMMENTS_STARS') == 16) {
            $this->context->controller->addCSS($this->_path . 'myprestacomments16.css', 'all');
        } else {
            $this->context->controller->addCSS($this->_path . 'myprestacomments32.css', 'all');
        }

        $this->context->controller->addjqueryPlugin('fancybox');
        $this->page_name = Dispatcher::getInstance()->getController();
    }

    public static function getImagesByID($id_product, $limit = 1)
    {
        $id_image = Db::getInstance()->ExecuteS('SELECT `id_image` FROM `' . _DB_PREFIX_ . 'image` WHERE `id_product` = ' . (int)($id_product) . ' ORDER BY position ASC LIMIT 0, ' . $limit);
        $toReturn = array();
        if (!$id_image) {
            return;
        } else {
            foreach ($id_image as $image) {
                $toReturn[] = $id_product . '-' . $image['id_image'];
            }
        }
        return $toReturn;
    }

    public function hookdisplayLeftColumnProduct($params)
    {
        if (Tools::getValue('controller') != 'product') {
            return;
        } else {
            return $this->hookProductFooter($params);
        }
    }

    public function hookdisplayRightColumnProduct($params)
    {
        return $this->hookdisplayLeftColumnProduct($params);
    }

    public function initCategoriesAssociation($id_root = null, $id_criterion = 0)
    {
        if (is_null($id_root)) {
            $id_root = Configuration::get('PS_ROOT_CATEGORY');
        }
        $id_shop = (int)Tools::getValue('id_shop');
        $shop = new Shop($id_shop);
        if ($id_criterion == 0) {
            $selected_cat = array();
        } else {
            $pdc_object = new MyprestaCommentCriterion($id_criterion);
            $selected_cat = $pdc_object->getCategories();
        }

        if (Shop::getContext() == Shop::CONTEXT_SHOP && Tools::isSubmit('id_shop')) {
            $root_category = new Category($shop->id_category);
        } else {
            $root_category = new Category($id_root);
        }
        $root_category = array(
            'id_category' => $root_category->id,
            'name' => $root_category->name[$this->context->language->id]
        );

        $helper = new Helper();

        return $helper->renderCategoryTree($root_category, $selected_cat, 'categoryBox', false, true);
    }

    public function inconsistency($return)
    {
        return;
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
                        $actual_version = myprestacommentsUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version);
                    }
                    if (myprestacommentsUpdate::version($this->version) < myprestacommentsUpdate::version(Configuration::get('updatev_' . $this->name)) && Tools::getValue('ajax', 'false') == 'false') {
                        $this->context->controller->warnings[] = '<strong>' . $this->displayName . '</strong>: ' . $this->l('New version available, check http://MyPresta.eu for more informations') . ' <a href="' . $this->mypresta_link . '">' . $this->l('More details in changelog') . '</a>';
                        $this->warning = $this->context->controller->warnings[0];
                    }
                } else {
                    if (Configuration::get('update_' . $this->name) < (date("U") - 259200)) {
                        $actual_version = myprestacommentsUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version);
                    }
                }
                if ($display_msg == 1) {
                    if (myprestacommentsUpdate::version($this->version) < myprestacommentsUpdate::version(myprestacommentsUpdate::verify($this->name, (isset($this->mkey) ? $this->mkey : 'nokey'), $this->version))) {
                        return "<span style='color:red; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('New version available!') . "</span>";
                    } else {
                        return "<span style='color:green; font-weight:bold; font-size:16px; margin-right:10px;'>" . $this->l('Module is up to date!') . "</span>";
                    }
                }
            }
        }
    }

    public function hookActionAdminControllerSetMedia($params)
    {
        // for update purposes only
    }

}

class myprestacommentsUpdate extends myprestacomments
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
