<?php

declare(strict_types=1);

use Configuration as ConfigurationLegacy;
use PrestaShop\Module\CreateitCountdown\Entity\CreateitCountdown;
use PrestaShop\Module\CreateitCountdown\Repository\CreateitCountdownRepository;

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

class createit_countdown extends Module
{

    const INSTALL_SQL_FILE = 'install.sql';

    public function __construct()
    {
        $this->name = 'createit_countdown';
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


        $this->displayName = $this->l('createIT Countdown');
        $this->description = $this->l('createIT\'s countdown module to free delivery while displaying the cart summary.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

    }

    public function installTab()
    {

        $tabId = (int) Tab::getIdFromClassName('CreateitCountdownController');

        if(!$tabId){
            $tabId = null;
        }

        $tab = new Tab($tabId);
        $tab->active = 1;
        $tab->class_name = 'CreateitCountdownController';
        $tab->route_name = 'createit_countdown_index';
        $tab->name = [];
        foreach (Language::getLanguages() as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans('createIT Countdown', array(), 'Modules.CreateitCountdown.Admin.', $lang['locale']);
        }
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminParentOrders');

        $tab->module = $this->name;

        return $tab->save();

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
            || !$this->registerHook('displayCountdown')
            || !$this->installTab()
        ) {
            return false;
        }

        return true;

    }

    private function uninstallTab()
    {
        $tabId = (int) Tab::getIdFromClassName('CreateitCountdownController');
        if (!$tabId) {
            return true;
        }

        $tab = new Tab($tabId);

        return $tab->delete();
    }

    public function uninstall($keep = true)
    {
        return parent::uninstall()
            && ($keep && $this->deleteTables())
            && $this->uninstallTab();
    }

    public function deleteTables()
    {
        return Db::getInstance()->execute('
			DROP TABLE IF EXISTS
			`' . _DB_PREFIX_ . 'createit_countdown`
			 ');
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

    private function getFreeShippingAmountValue(): float
    {
        $value = 0;

        $amountValue = ConfigurationLegacy::get('PS_SHIPPING_FREE_PRICE');

        if(!is_null($amountValue)){
            $value = $amountValue;
        }

        return (float)$value;
    }

    public function getSetting(string $default,string $setting):string
    {
        $value = $default;
        /**
         * @var $repository CreateitCountdownRepository
         */
        $repository = $this->get('prestashop.module.createit_countdown.repository.createit_countdown_repository');

        $amountValue = $repository->findSetting($setting);

        if(!is_null($amountValue)){
            $value = $amountValue->getValue();
        }

        return (string)$value;
    }

    public function hookDisplayCountdown()
    {
        $freeShippingAmountValue = $this->getFreeShippingAmountValue();
        $backgroundColor = $this->getSetting('#ff8080', CreateitCountdown::BACKGROUND_COLOR);
        $borderColor = $this->getSetting('#ffff80', CreateitCountdown::BORDER_COLOR);
        $textColor = $this->getSetting('#80ff80', CreateitCountdown::TEXT_COLOR);

        $summaryTotalPrice = $this->context->cart->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING);
        $hasProducts = $this->context->cart->hasProducts();

        $freeShippingTotalPriceLeft = $freeShippingAmountValue - $summaryTotalPrice;

        $this->context->smarty->assign([
            'freeShippingTotalPriceLeft' => sprintf('%0.2f', $freeShippingTotalPriceLeft),
            'backgroundColor' => $backgroundColor,
            'borderColor' => $borderColor,
            'textColor' => $textColor,
            'hasProducts' => $hasProducts
        ]);

        return $this->display(__FILE__, 'views/templates/hook/countdown.tpl');
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

}