<?php
/**
 * Accordion modules for prestashop by Createit.
 * @author CreateIt
 */
declare(strict_types=1);

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\Module\CreateitAccordion\Database\CreateitAccordionInstaller;
use PrestaShop\Module\CreateitAccordion\Entity\CreateitAccordion;
use PrestaShop\Module\CreateitAccordion\Entity\CreateitAccordionContent;
use PrestaShop\Module\CreateitAccordion\Repository\CreateitAccordionContentRepository;
use PrestaShop\Module\CreateitAccordion\Repository\CreateitAccordionRepository;
//use PrestaShop\PrestaShop\Adapter\Entity\ObjectModel;
use PrestaShop\PrestaShop\Core\Exception\ContainerNotFoundException;
use PrestaShop\Module\CreateitAccordion\ObjectModel\CreateitAccordion as CreateitAccordionObjectModel;

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require_once __DIR__.'/vendor/autoload.php';
}

class createit_accordion extends Module
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
        $this->name = 'createit_accordion';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'createIT';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;

        $this->tabs = [
            [
                'route_name' => 'createit_accordion_index',
                'class_name' => 'CreateitAccordionLink',
                'visible' => true,
                'name' => 'CreateIT Accordion',
                'parent_class_name' => 'AdminCatalog',
            ],
        ];

        parent::__construct();

        $this->displayName = $this->trans('createIT Accordion', [], 'Modules.CreateitAccordion.Admin');
        $this->description = $this->trans('Add accordion modules for prestashop.', [], 'Modules.CreateitAccordion.Admin');
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', [], 'Modules.CreateitAccordion.Admin');
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
            && $this->registerHook('displayCreateitAccordion')
            && $this->registerHook('displayCreateitAccordionFront')
            && $this->registerHook('actionAdminDuplicateAfter')
            && $this->registerHook('actionProductAdd');
    }

    /**
     * @throws DBALException
     * @throws ContainerNotFoundException
     */
    public function uninstall()
    {
        return $this->removeTables() && parent::uninstall();
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
     * @return CreateitAccordionInstaller
     * @throws ContainerNotFoundException
     */
    private function getInstaller(): CreateitAccordionInstaller
    {
        $installer = new CreateitAccordionInstaller(
            $this->get('doctrine.dbal.default_connection'),
            $this->getContainer()->getParameter('database_prefix'),
            $this->getContainer()->getParameter('database_engine')
        );

        return $installer;
    }

    /**
     * Display form in admin side.
     * @param $params
     * @return false|string
     */
    public function hookDisplayAdminProductsMainStepLeftColumnMiddle($params)
    {
        $this->context->smarty->assign([
            'accordion_list' => $this->getAccordionList(),
            'accordion_value' => $this->getAccordionValue($params['id_product']),
            'languages' => Language::getLanguages(),
        ]);

        return $this->display(__FILE__, 'views/templates/admin/accordion_product_field.tpl');
    }

    /**
     * Hook for saving values to database on updating products.
     * @param $params
     * @return void
     * @throws PrestaShopException
     */
    public function hookActionProductUpdate($params)
    {
        $id_product = (int)Tools::getValue('id_product');
        $createit_accordions = Tools::getValue('createit_accordion');
        $currentDateTime = new \DateTime('now', new \DateTimeZone('UTC'));

        if($this->deletePreviousRecord($id_product)){
            foreach($createit_accordions as $createitAccordionKey=>$createit_accordion){
                foreach($createit_accordion as $languageKey=>$language){
                    $objectModel = new CreateitAccordionObjectModel(null);
                    $objectModel->id_createit_accordion = $createitAccordionKey;
                    $objectModel->id_lang = $languageKey;
                    $objectModel->id_product = $id_product;
                    $objectModel->content = $language['content'];
                    $objectModel->created_at = $currentDateTime->format('Y-m-d H:i:s');
                    $objectModel->updated_at = $currentDateTime->format('Y-m-d H:i:s');
                    $objectModel->save();
                }
            }
        }
    }

    /**
     * Deletes record before saving a new one.
     * @param $id_product
     * @return bool
     */
    public function deletePreviousRecord($id_product)
    {
        if(Db::getInstance()->delete( 'createit_accordion_content', 'id_product = ' . $id_product)){
            return true;
        }else{
            return false;
        }
    }


    /**
     * Get accordion forms.
     * @return array
     * @throws Exception
     */
    private function getAccordionList(): array
    {
        $accordionList = [];

        /**
         * @var $createitAccordionRepository CreateitAccordionRepository
         */
        $createitAccordionRepository = $this->get('prestashop.module.createit_accordion.createit_accordion_repository');

            /**
             * @var $accordion CreateitAccordion
             */
            foreach ($createitAccordionRepository->findAll() as $accordion)
            {
                $accordionList[$accordion->getId()]['id_createit_accordion'] = $accordion->getId();
                $accordionList[$accordion->getId()]['field_name'] = $accordion->getFieldName();
                $accordionList[$accordion->getId()]['field_label'] = $accordion->getCreateitAccordionHeaderAll();
            }

        return $accordionList;
    }

    /**
     * Retrieve accordion product values.
     * @param $product_id
     * @return array|void
     * @throws PrestaShopException
     */
    private function getAccordionValue($product_id)
    {
        if (!Validate::isUnsignedId($product_id)) {
            exit(Tools::displayError());
        }

        /**
         * @var $createitAccordionContentRepository CreateitAccordionContentRepository
         */
        $createitAccordionContentRepository = $this->get('prestashop.module.createit_accordion.createit_accordion_content_repository');

        $accordions = $createitAccordionContentRepository->findBy(['productId' => $product_id]);

        /**
         * @var $accordion CreateitAccordionContent
         */
        foreach($accordions as $accordion)
        {
            $content['content'] = !empty($accordion->getContent()) ? $accordion->getContent() : '';
            $accordionList[$accordion->getCreateitAccordion()->getId()]['field_content'][$accordion->getLang()->getId()] = $content;
        }
        return $accordionList;
    }

    /**
     * Hook for displaying accordion values in front-end.
     * @param $params
     * @return false|string
     * @throws PrestaShopException
     */
    public function hookDisplayCreateitAccordionFront($params)
    {
        $product = $params['product'];

        $list = $this->getAccordionList();

        if(empty($list)){
            $content = '';
        }else{

            $this->context->smarty->assign([
                'accordion_list' => $list,
                'accordion_value' => $this->getAccordionValue($product['id_product']),
                'languages' => Language::getLanguages(),
            ]);

            $content = $this->display(__FILE__, 'views/templates/front/products_accordion.tpl');

        }

        return $content;
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
     * Select and insert new custom field.
     * @param $params
     * @return bool
     */
    public function hookActionAdminDuplicateAfter($params)
    {
        return Db::getInstance()->execute('
                INSERT INTO `' . _DB_PREFIX_ . 'createit_accordion_content` (id_product,id_createit_accordion,id_lang,content,created_at,updated_at)
SELECT '.$this->getNewProductId().',id_createit_accordion,id_lang,content,created_at,updated_at FROM `' . _DB_PREFIX_ . 'createit_accordion_content` where id_product = '.$this->getOldProductId()
        );
    }

    /**
     * Activate the new translation system.
     * @return bool
     */
    public function isUsingNewTranslationSystem()
    {
        return true;
    }
}