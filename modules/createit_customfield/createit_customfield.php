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
            parent::install() == false ||
            !$this->registerHook('displayAdminProductsMainStepLeftColumnMiddle')
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
        return $this->display(__FILE__, 'views/templates/admin/products_custom_field.tpl');
    }


    public function hookActionProductUpdate($params)
    {
        require_once dirname(__FILE__) . '/CreateitCustomField.php';

        /**
         * TODO
         */

        $id_product = (int)Tools::getValue('id_product');
        $createit_custom_field = (int)Tools::getValue('createit_custom_field');

        if($id_product && Tools::isSubmit('createit_custom_field')) {
            $id = null;
            $fn = new CreateitCustomField($id);
            $fn->id_product = 14;
            $fn->id_shop = 1; //TODO
            $fn->id_lang = 1;
            $fn->content = $createit_custom_field;
            $fn->created_at = new \DateTime('now', new \DateTimeZone('UTC'));
            $fn->updated_at = new \DateTime('now', new \DateTimeZone('UTC'));

            $fn->save();
        }
    }
}