<?php

/**
 * Add custom fields for each product, both on the backend.
 * @author CreateIt
 */

use PrestaShop\Module\CreateITCustomField\Repository\CreateitCustomfieldRepository;
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


        $this->displayName = $this->l('createIT Custom Field');
        $this->description = $this->l('Add custom fields for each product, both on the backend.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

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
            $tab->name[$lang['id_lang']] = $this->trans('createIT Custom Field', array(), 'Modules.CreateITCustomField.Admin', $lang['locale']);
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
			`' . _DB_PREFIX_ . 'createit_product_customfield`
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
            $currentProductCustomFields = $this->getProductCustomField($params['id_product'], Context::getContext()->shop->id);
        }

        $this->context->smarty->assign([
            'createit_custom_fields' => $currentProductCustomFields,
            'custom_fields' => $custom_fields->findAll()
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

                $id = null;
                $fn = new CreateitCustomField($id);
                $fn->id_product = $id_product;
                $fn->id_shop = Context::getContext()->shop->id;
                $fn->id_lang = Context::getContext()->language->id;
                $fn->content = $createit_custom_field['value'];
                $fn->id_createit_products_customfield = $createit_custom_field['name'];
                $fn->created_at = $currentDateTime->format('Y-m-d H:i:s');
                $fn->updated_at = $currentDateTime->format('Y-m-d H:i:s');

                $fn->save();

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

    public function getProductCustomField($id_product, $id_shop)
    {
        if (!Validate::isUnsignedId($id_product) ||
            !Validate::isUnsignedId($id_shop)) {
            exit(Tools::displayError());
        }

        $values = [];
        $cfValues = [];

        $custom_fields = $this->get('prestashop.module.createit_custom_field.repository.custom_field_product_repository');


        $res = Db::getInstance()->executeS('
                SELECT c.id_createit_customfield, c.id_product, c.id_shop, c.id_lang, c.content, pc.field_name, pc.label_name, pc.id_createit_products_customfield, c.created_at, c.updated_at
                FROM `' . _DB_PREFIX_ . 'createit_customfield` c INNER JOIN `' . _DB_PREFIX_ . 'createit_product_customfield` pc
                ON c.id_createit_products_customfield = pc.id_createit_products_customfield
                WHERE id_product = '. (int) $id_product .'
                AND id_shop = '. (int) $id_shop);


        foreach ($res as $value)
        {
            $values[$value['id_createit_products_customfield']]['id_createit_products_customfield'] = $value['id_createit_products_customfield'] ?? '';
            $values[$value['id_createit_products_customfield']]['content'] = $value['content'] ?? '';
            $values[$value['id_createit_products_customfield']]['field_name'] = $value['field_name'] ?? '';
            $values[$value['id_createit_products_customfield']]['label_name'] = $value['label_name'] ?? '';
        }

        /**
         * @var $cfValue \PrestaShop\Module\CreateITCustomField\Entity\CreateitProductCustomfield
         */
        $cfValueIterator = 0;
        foreach($custom_fields->findAll() as $cfValue)
        {
            $cfValues[$cfValueIterator]['id'] = $cfValue->getId();
            $cfValues[$cfValueIterator]['field_name'] = $cfValue->getFieldName();
            $cfValues[$cfValueIterator]['field_type'] = $cfValue->getFieldType();
            $cfValues[$cfValueIterator]['label_name'] = $cfValue->getLabelName();
            $cfValues[$cfValueIterator]['content'] = $values[$cfValue->getId()]['content'] ?? '';
            $cfValueIterator++;
        }

        return $cfValues;
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

    public function hookDisplayProductCustomField($params)
    {
        $productCustomFields = [];

        if(isset($params['product'])) {
            $product = $params['product'];
            if($this->isHaveCustomFieldValue($product['id_product'], Context::getContext()->shop->id, Context::getContext()->language->id)){
                $productCustomFields = $this->getProductCustomField($product['id_product'], Context::getContext()->shop->id);
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
                $productCustomFields = $this->getProductCustomField($product['id_product'], Context::getContext()->shop->id);
            }
        }

        $this->context->smarty->assign([
            'product_custom_fields' => $productCustomFields
        ]);

        return $this->display(__FILE__, 'views/templates/front/products_custom_field.tpl');
    }

    public function hookDisplayProductCustomFieldByName($params)
    {
        $product = $params['product'];
        $content = '';

        /**
         * @var $customField CreateitCustomfieldRepository
         */
        $customField = $this->get('prestashop.module.createit_custom_field.repository.custom_field_repository');

        /**
         * @var $res \PrestaShop\Module\CreateITCustomField\Entity\CreateitCustomfield
         */
        if(empty($res = $customField->findOneCustomFieldByFieldName($product['id_product'], Context::getContext()->shop->id, $params['field']))){
            $content = '';
        }else{
            $content = $res->getContent();
        }

        return $content;
    }
}