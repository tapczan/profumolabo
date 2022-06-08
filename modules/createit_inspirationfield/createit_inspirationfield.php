<?php

declare(strict_types=1);

use Doctrine\DBAL\DBALException;
use PrestaShop\Module\CreateitInspirationfield\Database\CreateitInspirationfieldInstaller;
use PrestaShop\Module\CreateitInspirationfield\Entity\CreateitInspirationfield;
use PrestaShop\Module\CreateitInspirationfield\ObjectModel\CreateitInspirationfieldObjectModel;
use PrestaShop\Module\CreateitInspirationfield\Repository\CreateitInspirationfieldRepository;
use PrestaShop\PrestaShop\Core\Exception\ContainerNotFoundException;

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require_once __DIR__.'/vendor/autoload.php';
}

class createit_inspirationfield extends Module
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
        $this->name = 'createit_inspirationfield';
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

        $this->displayName = $this->trans('createIT Inspiration', [], 'Modules.Createitinspirationfield.Admin');
        $this->description = $this->trans('Add custom inspiration field for prestashop.', [], 'Modules.Createitinspirationfield.Admin');
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', [], 'Modules.Createitinspirationfield.Admin');
    }

    /**
     * @return CreateitInspirationfieldInstaller
     * @throws ContainerNotFoundException
     */
    private function getInstaller(): CreateitInspirationfieldInstaller
    {
        $installer = new CreateitInspirationfieldInstaller(
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
            && $this->registerHook('displayCreateitInspirationfield');
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
            'languages' => Language::getLanguages(),
            'inspirationfield' => $this->getInspirationfieldAdminList($params['id_product'])
        ]);

        return $this->display(__FILE__, 'views/templates/admin/inspiration_field.tpl');
    }

    private function getInspirationfieldAdminList($id_product)
    {
        $list = [];

        /**
         * @var $repository CreateitInspirationfieldRepository
         */
        $repository = $this->get('prestashop.module.createit_productfield.createit_inspirationfield_repository');

        /**
         * @var $fields CreateitInspirationfield
         */
        foreach($repository->findBy(['productId' => $id_product]) as $fields)
        {
            $list[$fields->getLang()->getId()]['content'] = $fields->getContent();
        }

        return $list;
    }

    private function getInspirationfieldFrontList($params)
    {
        $id_product = $params['product']->id;

        $list = [];

        /**
         * @var $repository CreateitInspirationfieldRepository
         */
        $repository = $this->get('prestashop.module.createit_productfield.createit_inspirationfield_repository');

        /**
         * @var $fields CreateitInspirationfield
         */
        foreach($repository->findBy(['productId' => $id_product, 'lang' => Context::getContext()->language->getId()]) as $fields)
        {
            $list[$fields->getLang()->getId()]['content'] = $fields->getContent();
        }

        return $list;
    }

    public function hookActionProductUpdate($params)
    {
        $id_product = (int)Tools::getValue('id_product');
        $createit_inspirationfield = Tools::getValue('createit_inspirationfield');
        $currentDateTime = new \DateTime('now', new \DateTimeZone('UTC'));

        if($this->deletePreviousRecord($id_product)){
            foreach ($createit_inspirationfield as $createit_inspirationfieldKey => $productfield)
            {
                $objectModel = new CreateitInspirationfieldObjectModel(null);
                $objectModel->id_lang = $createit_inspirationfieldKey;
                $objectModel->id_product = $id_product;
                $objectModel->content = $productfield['content'];
                $objectModel->created_at = $currentDateTime->format('Y-m-d H:i:s');
                $objectModel->updated_at = $currentDateTime->format('Y-m-d H:i:s');
                $objectModel->save();
            }
        }

    }

    public function hookDisplayCreateitInspirationfield($params)
    {
        $this->context->smarty->assign([
            'languages' => Language::getLanguages(),
            'inspirationfield' => $this->getInspirationfieldFrontList($params)
        ]);

        return $this->display(__FILE__, 'views/templates/front/inspiration_field.tpl');
    }

    public function deletePreviousRecord($id_product)
    {
        if(Db::getInstance()->delete( 'createit_inspirationfield', 'id_product = ' . $id_product)){
            return true;
        }else{
            return false;
        }
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
     * @param $params
     * @return bool
     */
    public function hookActionAdminDuplicateAfter($params)
    {
        return Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'createit_inspirationfield` (id_lang,id_product,content,created_at,updated_at)
        SELECT  id_lang,  '.$this->getNewProductId().' ,content,created_at,updated_at FROM `' . _DB_PREFIX_ . 'createit_inspirationfield` where id_product = '.$this->getOldProductId());
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }
}