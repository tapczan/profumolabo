<?php

/**
 * Add custom fields for each product, both on the backend.
 * @author CreateIt
 */
if (!defined('_PS_VERSION_')) {
    exit;
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

        if (
            parent::install() == false
            || !$this->registerHook('displayAdminProductsMainStepLeftColumnMiddle')
            || !$this->registerHook('actionProductUpdate')
            || !$this->registerHook('actionFrontControllerSetVariables')
        ) {
            return false;
        }

        return true;
    }

    public function uninstall($keep = true)
    {
        if (
            !parent::uninstall() || ($keep && !$this->deleteTables())
        ) {
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

    public function deleteTables()
    {
        return Db::getInstance()->execute('
			DROP TABLE IF EXISTS
			`' . _DB_PREFIX_ . 'createit_customfield`');
    }

    public function hookDisplayAdminProductsMainStepLeftColumnMiddle($params)
    {
        $customField = '';

        if($this->isHaveCustomFieldValue($params['id_product'], Context::getContext()->shop->id, Context::getContext()->language->id)){

            $currentProductCustomField = $this->getProductCustomField($params['id_product'], Context::getContext()->shop->id, Context::getContext()->language->id);

            $customField = reset($currentProductCustomField);
            $customField = $customField['content'];
        }

        $this->context->smarty->assign([
            'createit_custom_field' => $customField
        ]);

        return $this->display(__FILE__, 'views/templates/admin/products_custom_field.tpl');
    }

    public function isHaveCustomFieldValue($id_product, $id_shop, $id_lang)
    {
        if( $this->getProductCustomField($id_product, $id_shop, $id_lang) ){
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
        $createit_custom_field = Tools::getValue('createit_custom_field');

        $currentProductCustomField = $this->getProductCustomField($id_product, Context::getContext()->shop->id, Context::getContext()->language->id);

        if(!$currentProductCustomField){
            $id = null;
            $fn = new CreateitCustomField($id);
            $fn->id_product = $id_product;
            $fn->id_shop = Context::getContext()->shop->id;
            $fn->id_lang = Context::getContext()->language->id;
            $fn->content = $createit_custom_field;
            $fn->created_at = $currentDateTime->format('Y-m-d H:i:s');
            $fn->updated_at = $currentDateTime->format('Y-m-d H:i:s');

            $fn->save();
        }else{
            $this->update($id_product, Context::getContext()->shop->id, Context::getContext()->language->id, $createit_custom_field);
        }

    }

    public function update($id_product, $id_shop, $id_lang, $createit_custom_field)
    {
        $currentDateTime= new \DateTime('now', new \DateTimeZone('UTC'));
        $updatedDate = $currentDateTime->format('Y-m-d H:i:s');

        return Db::getInstance()->execute('
            UPDATE `' . _DB_PREFIX_ . 'createit_customfield`
            SET `content` = \'' . pSQL($createit_custom_field) . '\' , `updated_at` = \'' . pSQL($updatedDate) . '\'
            WHERE id_product = '. (int) $id_product .' AND
                    id_shop = '. (int) $id_shop  .' AND
                    id_lang = '. (int) $id_lang
        );
    }

    public function getProductCustomField($id_product, $id_shop, $id_lang)
    {
        if (!Validate::isUnsignedId($id_product) ||
            !Validate::isUnsignedId($id_lang) ||
            !Validate::isUnsignedId($id_lang)) {
            exit(Tools::displayError());
        }
        $res = Db::getInstance()->executeS('
                    SELECT *
                    FROM `' . _DB_PREFIX_ . 'createit_customfield`
                    WHERE id_product = '. (int) $id_product .'
                    AND id_shop = '. (int) $id_shop  .'
                    AND id_lang = '. (int) $id_lang);
        return $res;
    }

    public function getProductCustomFieldValue($id_product, $id_shop, $id_lang)
    {
        $customField = '';

        if($this->isHaveCustomFieldValue($id_product, $id_shop, $id_lang)){

            $currentProductCustomField = $this->getProductCustomField($id_product, $id_shop, $id_lang);

            $customField = reset($currentProductCustomField);
            $customField = $customField['content'];
        }

        return $customField;
    }

    public function hookActionFrontControllerSetVariables($param)
    {

        $customField = '';

        if(!is_null(Tools::getValue('id_product'))){
            /**
             * TODO
             */
            /**
            $customField = $this->getProductCustomFieldValue(Tools::getValue('id_product'), Context::getContext()->shop->id, Context::getContext()->language->id);
             **/
        }
        return [
            'custom_field' => ''
        ];
    }
}