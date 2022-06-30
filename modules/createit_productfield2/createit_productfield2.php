<?php

declare(strict_types=1);

use Doctrine\DBAL\DBALException;
use PrestaShop\Module\CreateitProductfield2\Database\CreateitProductfield2Installer;
use PrestaShop\Module\CreateitProductfield2\Entity\CreateitProductfield2;
use PrestaShop\Module\CreateitProductfield2\ObjectModel\CreateitProductfield2ObjectModel;
use PrestaShop\Module\CreateitProductfield2\Repository\CreateitProductfield2Repository;
use PrestaShop\PrestaShop\Core\Exception\ContainerNotFoundException;

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require_once __DIR__.'/vendor/autoload.php';
}

class createit_productfield2 extends Module
{
    /**
     * @var int
     */
    private $newProductId;

    /**
     * @var int
     */
    private $oldProductId;

    /**
     * @return int
     */
    public function getOldProductId(): int
    {
        return $this->oldProductId;
    }

    /**
     * @param int $oldProductId
     */
    public function setOldProductId(int $oldProductId): void
    {
        $this->oldProductId = $oldProductId;
    }

    /**
     * @return int
     */
    public function getNewProductId(): int
    {
        return $this->newProductId;
    }

    /**
     * @param int $newProductId
     */
    public function setNewProductId(int $newProductId): void
    {
        $this->newProductId = $newProductId;
    }

    public function __construct()
    {
        $this->name = 'createit_productfield2';
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

        $this->displayName = $this->trans('createIT Productfield 2', [], 'Modules.Createitproductfield2.Admin');
        $this->description = $this->trans('Add custom product field for prestashop.', [], 'Modules.Createitproductfield2.Admin');
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', [], 'Modules.Createitproductfield2.Admin');
    }

    /**
     * @return CreateitProductfield2Installer
     * @throws ContainerNotFoundException
     */
    private function getInstaller(): CreateitProductfield2Installer
    {
        $installer = new CreateitProductfield2Installer(
            $this->get('doctrine.dbal.default_connection'),
            $this->getContainer()->getParameter('database_prefix'),
            $this->getContainer()->getParameter('database_engine')
        );

        return $installer;
    }

    /**
     * @throws DBALException
     * @throws ContainerNotFoundException
     */
    private function installTables()
    {
        $installer = $this->getInstaller();
        $errors = $installer->createTables();

        return empty($errors);
    }

    /**
     * @return bool
     * @throws ContainerNotFoundException
     * @throws DBALException
     */
    private function removeTables()
    {
        $installer = $this->getInstaller();
        $errors = $installer->dropTables();

        return empty($errors);
    }

    /**
     * @throws DBALException
     * @throws ContainerNotFoundException
     */
    public function install()
    {
        return $this->installTables()
            && parent::install()
            && $this->registerHook('displayAdminProductsMainStepLeftColumnMiddle')
            && $this->registerHook('actionProductUpdate')
            && $this->registerHook('actionAdminDuplicateAfter')
            && $this->registerHook('actionProductAdd')
            && $this->registerHook('displayBackOfficeHeader')
            && $this->registerHook('displayCreateitProductfield2')
            && $this->registerHook('displayCreateitProductfield2_id_product');
    }

    /**
     * @throws DBALException
     * @throws ContainerNotFoundException
     */
    public function uninstall()
    {
        return $this->removeTables() && parent::uninstall();
    }

    public function hookDisplayAdminProductsMainStepLeftColumnMiddle($params)
    {
        $this->context->smarty->assign([
            'productfield2_list' => $this->getProductfield2List($params['id_product']),
            'languages' => Language::getLanguages(),
        ]);

        return $this->display(__FILE__, 'views/templates/admin/productfield2_field.tpl');
    }

    public function hookActionProductUpdate($params)
    {
        $id_product = (int)Tools::getValue('id_product');
        $createit_productfields = Tools::getValue('createit_productfield2');
        $currentDateTime = new \DateTime('now', new \DateTimeZone('UTC'));

        if($this->deletePreviousRecord($id_product)){
            $objectModel = new CreateitProductfield2ObjectModel(null);
            $objectModel->id_product = $id_product;
            $objectModel->id_product_linked = $createit_productfields;
            $objectModel->created_at = $currentDateTime->format('Y-m-d H:i:s');
            $objectModel->updated_at = $currentDateTime->format('Y-m-d H:i:s');
            $objectModel->save();
        }

    }

    public function hookDisplayCreateitProductfield2($params)
    {
        $this->context->smarty->assign([
            'product_object' => $this->getProductfield2ListFront($params),
            'languages' => Language::getLanguages(),
        ]);

        return $this->display(__FILE__, 'views/templates/admin/productfield_front_field.tpl');
    }

    public function deletePreviousRecord($id_product)
    {
        if(Db::getInstance()->delete( 'createit_productfield2', 'id_product = ' . $id_product)){
            return true;
        }else{
            return false;
        }
    }

    private function getProductfield2List($id_product)
    {
        $list = [
            'product_name' => '',
            'product_url' => ''
        ];

        $productObj = [];

        /**
         * @var $repository CreateitProductfield2Repository
         */
        $repository = $this->get('prestashop.module.createit_productfield.createit_productfield2_repository');

        /**
        * @var $product CreateitProductfield2
        */
        $product = $repository->findOneBy(['productId' => $id_product]);

        if($product) {
            $productObj = new Product($product->getProductIdLinked(), false, Context::getContext()->language->getId());

            $link = new Link();

            $url = $link->getProductLink($productObj);

            $list = [
                'product_name' => $productObj->name,
                'product_url' => $url
            ];
        }

        return $list;
    }

    /**
     * TODO TO BE REFACTOR!!!
     * @param $params
     * @return array|string[]
     * @throws PrestaShopException
     */
    private function getProductfield2ListFront($params)
    {

        $id_product = $params['product']->id;

        $isLinked = array_key_exists('is_linked', $params) ? $params['is_linked'] : false;

        $list = [
            'product_name' => '',
            'product_url' => '',
            'is_linked' => ''
        ];

        /**
         * @var $repository CreateitProductfield2Repository
         */
        $repository = $this->get('prestashop.module.createit_productfield.createit_productfield2_repository');

        /**
         * @var $product CreateitProductfield2
         */
        $product = $repository->findOneBy(['productId' => $id_product]);

        if($product){
            $productObj = new Product($product->getProductIdLinked(), false, Context::getContext()->language->getId());

            $link = new Link();

            $url = $link->getProductLink($productObj);

            $list = [
                'product_name' => $productObj->name,
                'product_url' => $url,
                'is_linked' => $isLinked
            ];

        }

        return $list;
    }

    /**
     * Store old and new product id.
     * @param $params
     */
    public function hookActionProductAdd($params)
    {
        $this->setNewProductId($params['id_product']);
        $this->setOldProductId($params['id_product_old']);
    }

    /**
     * Select and insert new field.
     * @param $params
     * @return bool
     */
    public function hookActionAdminDuplicateAfter($params)
    {
        return Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'createit_productfield2` (id_lang,id_product,content,created_at,updated_at)
        SELECT  id_lang,  '.$this->getNewProductId().' ,content,created_at,updated_at FROM `' . _DB_PREFIX_ . 'createit_productfield2` where id_product = '.$this->getOldProductId());
    }

    public function hookDisplayBackOfficeHeader()
    {
        $this->context->controller->addJS($this->_path.'views/js/productfield2.js');
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

}