<?php

/**
 * Add custom fields for each product, both on the backend.
 * @author CreateIt
 */

use PrestaShop\Module\CreateITCustomField\Entity\CreateitProductCustomfield;
use PrestaShop\Module\CreateITCustomField\Entity\CreateitProductCustomfieldLabelLang;
use PrestaShop\Module\CreateITCustomField\Entity\CreateitCustomfield;
use PrestaShop\Module\CreateITCustomField\Repository\CreateitCustomfieldRepository;
use PrestaShop\Module\CreateITCustomField\Repository\CreateitProductCustomfieldLabelLangRepository;
use PrestaShop\Module\CreateITCustomField\Repository\CreateitProductCustomfieldRepository;

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require_once __DIR__.'/vendor/autoload.php';
}

class CreateIt_CustomField extends Module
{
    const INSTALL_SQL_FILE = 'install.sql';

    public function __construct()
    {
        $this->name = 'createit_customfield';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'createIT';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('createIT Custom Field', [], 'Modules.Createitcustomfield.Admin');
        $this->description = $this->trans('Add custom fields for each product, both on the backend.', [], 'Modules.Createitcustomfield.Admin');
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', [], 'Modules.Createitcustomfield.Admin');

    }

    public function install($keep = true)
    {
        if ($keep) {
            if (!file_exists(dirname(__FILE__) . '/' . self::INSTALL_SQL_FILE)) {
                return false;
            } elseif (!$sql = file_get_contents(dirname(__FILE__) . '/' . self::INSTALL_SQL_FILE)) {
                return false;
            }
            $sql = str_replace(['PREFIX_', 'ENGINE_TYPE'], [_DB_PREFIX_, _MYSQL_ENGINE_], $sql);
            $sql = preg_split("/;\s*[\r\n]+/", trim($sql));

            foreach ($sql as $query) {
                if (!Db::getInstance()->execute(trim($query))) {
                    return false;
                }
            }
        }

        if (Hook::getIdByName('displayProductCustomField')) {
            $hook = new Hook();
            $hook->name = 'displayProductCustomField';
            $hook->title = 'CreateIT Custom Field Hook';
            $hook->description = 'This hooks displays CreateIT custom field.';
            $hook->position = 1;
            $hook->add();
        }


        if (
            parent::install() == false
            || !$this->registerHook('displayAdminProductsMainStepLeftColumnMiddle')
            || !$this->registerHook('actionProductUpdate')
            || !$this->registerHook('displayProductCustomField')
            || !$this->registerHook('displayProductCustomFieldByName')
            || !$this->registerHook('displayProductCustomFields')
            || !$this->registerHook('displayBackOfficeHeader')
            || !$this->installTab()
        ) {
            return false;
        }

        return true;
    }

    public function installTab()
    {
       $tabId = (int) Tab::getIdFromClassName('CreateITCustomFieldController');

        if(!$tabId){
            $tabId = null;
        }

        $tab = new Tab($tabId);
        $tab->active = 1;
        $tab->class_name = 'CreateITCustomFieldController';
        $tab->route_name = 'createit_custom_field';
        $tab->name = [];
        foreach (Language::getLanguages() as $lang){
            $tab->name[$lang['id_lang']] = $this->trans('createIT Custom Field', array(), 'Modules.Createitcustomfield.Admin', $lang['locale']);
        }
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminCatalog');

        $tab->module = $this->name;

        return $tab->save();
    }

    private function uninstallTab()
    {
        $tabId = (int) Tab::getIdFromClassName('CreateITCustomFieldController');
        if (!$tabId) {
            return true;
        }

        $tab = new Tab($tabId);

        return $tab->delete();
    }

    public function uninstall($keep = true)
    {
        if (
            !parent::uninstall()
            && ($keep && !$this->deleteTables())
            && $this->uninstallTab()
        ) {
            return false;
        }

        $id_hook = Hook::getIdByName('displayProductCustomField');
        $hook = new Hook($id_hook);
        $hook->delete();

        $id_hook2 = Hook::getIdByName('displayProductCustomFields');
        $hook2 = new Hook($id_hook2);
        $hook2->delete();

        $id_hook3 = Hook::getIdByName('displayProductCustomFieldByName');
        $hook3 = new Hook($id_hook3);
        $hook3->delete();

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

    public function deleteTables()
    {
        return Db::getInstance()->execute('
			DROP TABLE IF EXISTS
			`' . _DB_PREFIX_ . 'createit_customfield`,
			`' . _DB_PREFIX_ . 'createit_product_customfield`,
			`' . _DB_PREFIX_ . 'createit_product_customfield_label_lang`
			 ');
    }

    public function hookDisplayAdminProductsMainStepLeftColumnMiddle($params)
    {
        /**
         * @var $custom_fields CreateitProductCustomfieldRepository
         */
        $custom_fields = $this->get('prestashop.module.createit_custom_field.repository.custom_field_product_repository');

        $currentProductCustomFields = [];

        if(!empty($custom_fields->findAll())){
            $currentProductCustomFields = $this->getProductCustomFieldValues($params['id_product'], Context::getContext()->shop->id);
        }

        $this->context->smarty->assign([
            'createit_custom_fields' => $currentProductCustomFields,
            'custom_fields' => $custom_fields->findAll(),
            'empty_fields' => $this->getProductCustomField(),
            'languages' => Language::getLanguages(),
        ]);

        return $this->display(__FILE__, 'views/templates/admin/products_custom_field.tpl');
    }

    public function hookDisplayBackOfficeHeader()
    {
        $this->context->controller->addJS($this->_path.'views/js/products_custom_field.js');
        $this->context->controller->addCSS($this->_path.'views/css/style.css');
    }

    public function isHaveCustomFieldValue($id_product, $id_shop, $id_lang)
    {
        $res = Db::getInstance()->executeS('
                    SELECT *
                    FROM `' . _DB_PREFIX_ . 'createit_customfield`
                    WHERE id_product = '. (int) $id_product .'
                    AND id_shop = '. (int) $id_shop);

        if( $res ){
            return true;
        }else{
            return false;
        }
    }

    public function hookActionProductUpdate($params)
    {
        require_once dirname(__FILE__) . '/CreateitCustomField.php';

        $currentDateTime= new \DateTime('now', new \DateTimeZone('UTC'));
        $id_product = (int)Tools::getValue('id_product');

        $createit_custom_fields = Tools::getValue('createit_custom_field');

        if($this->deletePreviousRecord($id_product)){

            foreach($createit_custom_fields as $createit_custom_field) {

                foreach($createit_custom_field as $language){
                    $id = null;
                    $fn = new \CreateitCustomField($id);
                    $fn->id_product = $id_product;
                    $fn->id_shop = Context::getContext()->shop->id;
                    $fn->id_lang = $language['id_lang'];
                    $fn->content = $language['content'];
                    $fn->lang_iso_code = $language['lang_iso_code'];
                    $fn->id_createit_products_customfield = $language['id_createit_products_customfield'];
                    $fn->created_at = $currentDateTime->format('Y-m-d H:i:s');
                    $fn->updated_at = $currentDateTime->format('Y-m-d H:i:s');

                    $fn->save();
                }
            }

        }

    }

    public function deletePreviousRecord($id_product)
    {
        if(Db::getInstance()->delete( 'createit_customfield', 'id_product = ' . $id_product)){
            return true;
        }else{
            return false;
        }
    }

    public function getProductCustomField()
    {
        /**
         * @var $customFields CreateitProductCustomfieldRepository
         */
        $customFields = $this->get('prestashop.module.createit_custom_field.repository.custom_field_product_repository');

        $cFields = [];

        /**
         * @var $fields CreateitProductCustomfield
         */
        foreach($customFields->findAll() as $fields){
            $cFields[$fields->getId()]['id_createit_customfield'] = $fields->getId();
            $cFields[$fields->getId()]['field_name'] = $fields->getFieldName();
            $cFields[$fields->getId()]['field_type'] = $fields->getFieldType();
            $cFields[$fields->getId()]['field_label'] = $fields->getCreateitProductCustomfieldLabelAllLangContent();
        }

       return $cFields;
    }

    public function getProductCustomFieldValues($id_product, $id_shop)
    {
        if (!Validate::isUnsignedId($id_product) ||
            !Validate::isUnsignedId($id_shop)) {
            exit(Tools::displayError());
        }

        $values = [];
        $cfValues = [];

        $custom_fields = $this->get('prestashop.module.createit_custom_field.repository.custom_field_product_repository');

        $res = Db::getInstance()->executeS('
                SELECT c.id_createit_customfield, c.id_product, c.lang_iso_code, c.id_shop, c.id_lang, c.content,pc.field_type, pc.id_createit_products_customfield, c.created_at, c.updated_at
                FROM `' . _DB_PREFIX_ . 'createit_customfield` c INNER JOIN `' . _DB_PREFIX_ . 'createit_product_customfield` pc
                ON c.id_createit_products_customfield = pc.id_createit_products_customfield
                WHERE id_product = '. (int) $id_product .'
                AND id_shop = '. (int) $id_shop
        );

        foreach ($res as $value)
        {
            $values[$value['id_createit_products_customfield']][$value['id_lang']]['id_createit_products_customfield'] = $value['id_createit_products_customfield'] ?? '';
            $values[$value['id_createit_products_customfield']][$value['id_lang']]['content'] = $value['content'] ?? '';
            $values[$value['id_createit_products_customfield']][$value['id_lang']]['lang_iso_code'] = $value['lang_iso_code'] ?? '';
            $values[$value['id_createit_products_customfield']][$value['id_lang']]['id_lang'] = $value['id_lang'] ?? '';
            $values[$value['id_createit_products_customfield']][$value['id_lang']]['field_type'] = $value['field_type'] ?? '';
        }

        return $values;

    }

    public function getProductAllCustomField($id_product, $id_shop, $id_lang)
    {
        if (!Validate::isUnsignedId($id_product) ||
            !Validate::isUnsignedId($id_shop) ||
            !Validate::isUnsignedId($id_lang)) {
            exit(Tools::displayError());
        }

        $res = Db::getInstance()->executeS('
                    SELECT *
                    FROM `' . _DB_PREFIX_ . 'createit_customfield`
                    WHERE id_product = '. (int) $id_product .'
                    AND id_shop = '. (int) $id_shop);
        return $res;
    }

    public function getProductCustomFieldFrontEndValues($id_product, $id_shop)
    {
        if (!Validate::isUnsignedId($id_product) ||
            !Validate::isUnsignedId($id_shop)) {
            exit(Tools::displayError());
        }

        $res = Db::getInstance()->executeS('
                SELECT c.id_createit_customfield, c.id_product, c.lang_iso_code, c.id_shop, c.id_lang, c.content,pc.field_type, pc.id_createit_products_customfield, c.created_at, c.updated_at
                FROM `' . _DB_PREFIX_ . 'createit_customfield` c INNER JOIN `' . _DB_PREFIX_ . 'createit_product_customfield` pc
                ON c.id_createit_products_customfield = pc.id_createit_products_customfield
                WHERE id_product = '. (int) $id_product .'
                AND id_shop = '. (int) $id_shop .'
                AND id_lang = '. (int) Context::getContext()->language->getId()
        );

        return $res;

    }

    public function hookDisplayProductCustomField($params)
    {
        $productCustomFields = [];

        if(isset($params['product'])) {
            $product = $params['product'];
            if($this->isHaveCustomFieldValue($product['id_product'], Context::getContext()->shop->id, Context::getContext()->language->id)){
                $productCustomFields = $this->getProductCustomFieldFrontEndValues($product['id_product'], Context::getContext()->shop->id);
            }
        }

        $this->context->smarty->assign([
            'product_custom_fields' => $productCustomFields
        ]);

        return $this->display(__FILE__, 'views/templates/front/products_custom_field.tpl');
    }

    public function hookDisplayProductCustomFields($params)
    {
        $productCustomFields = [];

        if(isset($params['product'])) {
            $product = $params['product'];
            if($this->isHaveCustomFieldValue($product['id_product'], Context::getContext()->shop->id, Context::getContext()->language->id)){
                $productCustomFields = $this->getProductCustomFieldValues($product['id_product'], Context::getContext()->shop->id);
            }
        }

        $this->context->smarty->assign([
            'product_custom_fields' => $productCustomFields,
            'languages' => Language::getLanguages(),
        ]);

        return $this->display(__FILE__, 'views/templates/front/products_custom_field.tpl');
    }

    public function getProductCustomFieldFrontEndValueAccordion($productId)
    {
        /**
         * @var $customFields CreateitProductCustomfieldRepository
         */
        $customFields = $this->get('prestashop.module.createit_custom_field.repository.custom_field_product_repository');

        $cFields = [];

        /**
         * @var $fields CreateitProductCustomfield
         */
        foreach($customFields->findAll() as $fields){

            /**
             * @var $lang CreateitProductCustomfieldLabelLang
             */
            $label = $fields->getCreateitProductCustomfieldLabelLangByLangId((int) Context::getContext()->language->id);

            /**
             * @var $content CreateitCustomfield
             */
            $content = $fields->getCreateitProductCustomfieldContentByProductAndLang((int) $productId, (int) Context::getContext()->language->id);

            $cFields[$fields->getId()]['id_createit_customfield'] = $fields->getId();
            $cFields[$fields->getId()]['field_name'] = $fields->getFieldName();
            $cFields[$fields->getId()]['field_type'] = $fields->getFieldType();
            $cFields[$fields->getId()]['field_label'] = $label->getContent();
            $cFields[$fields->getId()]['content'] = $content == null ? '' : $content->getContent();
        }

        return $cFields;
    }

    public function hookDisplayProductCustomFieldByName($params)
    {
        $product = $params['product'];
        $values = $this->getProductCustomFieldFrontEndValueAccordion($product['id_product']);

        if(empty($values)){
            $content = '';
        }else{
            $this->context->smarty->assign([
                'customfield' => $values
            ]);
            $content = $this->display(__FILE__, 'views/templates/front/products_custom_field_accordion.tpl');
        }

        return $content;
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }
}