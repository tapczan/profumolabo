<?php
/**
 * NOTICE OF LICENSE
 *
 * @author    INVERTUS, UAB www.invertus.eu <support@invertus.eu>
 * @copyright Copyright (c) permanent, INVERTUS, UAB
 * @license   Addons PrestaShop license limitation
 * @see       /LICENSE
 *
 * International Registered Trademark & Property of INVERTUS, UAB
 */

use Invertus\SmartUpsellAdvanced\Helper\UpsellHelper;
use Invertus\SmartUpsellAdvanced\Installer\DbInstaller;
use Invertus\SmartUpsellAdvanced\Repository\SettingsRepository;
use Invertus\SmartUpsellAdvanced\Repository\SpecialOfferCartRelationRepository;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class SmartUpsellAdvanced extends Module
{
    const DISABLE_CACHE = false;

    const FEEDBACK_CONFIGURATION = 'UPSELL_FEEDBACK';

    private $moduleContainer;

    private $ajaxHookLink;

    /**
     * SmartUpsellAdvanced constructor.
     */
    public function __construct()
    {
        $this->tab = 'front_office_features';
        $this->name = 'smartupselladvanced';
        $this->version = '1.0.11';
        $this->author = 'Invertus';
        $this->module_key = '0b8231424e680bf98200f2f7294eed4d';

        parent::__construct();
        $this->displayName = $this->l('Smart Upsell in Cart Advanced');
        $this->description = $this->l('The module helps merchants to offer upsells for the clients.');

        $this->compile();
        $this->autoload();
    }

    /**
     * Redirect to the module settings page
     */
    public function getContent()
    {
        $controllerLink = $this->context->link->getAdminLink('AdminSmartUpsellAdvancedProductPage');
        Tools::redirectAdmin($controllerLink);
    }

    /**
     * Process install
     *
     * @return bool
     */
    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        return DbInstaller::install() &&
            DbInstaller::installConfiguration() &&
            $this->registerHook('displayShoppingCartFooter') &&
            $this->registerHook('actionFrontControllerSetMedia') &&
            $this->registerHook('displayFooterProduct') &&
            $this->registerHook('actionPaymentConfirmation');
    }

    /**
     * Process uninstall
     *
     * @return bool
     */
    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        return DbInstaller::uninstall();
    }

    /**
     * Initialise module settings tabs
     *
     * @return array
     */
    public function getTabs()
    {
        return [
            [
                'name' => $this->l('Smart Upsell in Cart Advanced'),
                'parent_class_name' => 'AdminParentModulesSf',
                'class_name' => 'AdminSmartUpsellAdvancedParent',
                'visible' => false,
            ],
            [
                'name' => $this->l('Product Page'),
                'parent_class_name' => 'AdminSmartUpsellAdvancedParent',
                'class_name' => 'AdminSmartUpsellAdvancedProductPage',
            ],
            [
                'name' => $this->l('Cart'),
                'parent_class_name' => 'AdminSmartUpsellAdvancedParent',
                'class_name' => 'AdminSmartUpsellAdvancedCart',
            ],
            [
                'name' => $this->l('Settings'),
                'parent_class_name' => 'AdminSmartUpsellAdvancedParent',
                'class_name' => 'AdminSmartUpsellAdvancedSettings',
            ],
            [
                'name' => $this->l('Product Details'),
                'parent_class_name' => 'AdminSmartUpsellAdvancedParent',
                'class_name' => 'AdminSmartUpsellAdvancedProductDetails',
                'visible' => false,
            ],
            [
                'name' => $this->l('Info'),
                'ParentClassName' => 'AdminSmartUpsellAdvancedParent',
                'class_name' => 'AdminSmartUpsellAdvancedInfo',
                'module_tab' => true,
            ],
        ];
    }

    /**
     * Add custom JS and CSS
     *
     * @param $params
     */
    public function hookActionFrontControllerSetMedia($params)
    {
        if ($this->context->controller->php_self === "cart") {
            $this->context->controller->registerStylesheet(
                'sua-special-offer-stylesheet',
                'modules/' . $this->name . '/views/css/fo-special-offer.css'
            );

            $this->context->controller->registerJavascript(
                'sua-countdown-counter',
                'modules/' . $this->name . '/views/js/fo_countdown_counter.js',
                [
                    'priority' => 200,
                    'attribute' => 'async',
                ]
            );

            $this->context->controller->registerJavascript(
                'sua-add-to-cart-special-offer',
                'modules/' . $this->name . '/views/js/fo_add_to_cart.js',
                [
                    'priority' => 400,
                    'attribute' => 'async',
                ]
            );

            $this->context->controller->registerJavascript(
                'sua-change-price',
                'modules/' . $this->name . '/views/js/fo_change_price.js',
                [
                    'priority' => 300,
                    'attribute' => 'async',
                ]
            );
        }

        if ($this->context->controller->php_self === "product") {
            $this->context->controller->registerStylesheet(
                'sua-upsell-modal-stylesheet',
                'modules/' . $this->name . '/views/css/fo-upsell-modal.css'
            );

            $this->context->controller->registerStylesheet(
                'sua-upsell-stylesheet',
                'modules/' . $this->name . '/views/css/fo-upsell.css'
            );

            $this->context->controller->registerJavascript(
                'sua-add-to-cart-upsell',
                'modules/' . $this->name . '/views/js/fo_add_to_cart_upsell.js',
                [
                    'priority' => 500,
                    'attribute' => 'async',
                ]
            );

            $this->context->controller->registerJavascript(
                'sua-show-modal',
                'modules/' . $this->name . '/views/js/fo_show_modal.js',
                [
                    'priority' => 501,
                    'attribute' => 'async',
                ]
            );
            $this->context->controller->registerJavascript(
                'sua-change-upsell-price-modal',
                'modules/' . $this->name . '/views/js/fo_change_price_upsell_modal.js',
                [
                    'priority' => 502,
                    'attribute' => 'async',
                ]
            );

            $this->context->controller->registerJavascript(
                'sua-change-upsell-price',
                'modules/' . $this->name . '/views/js/fo_change_price_upsell.js',
                [
                    'priority' => 503,
                    'attribute' => 'async',
                ]
            );

            $this->context->controller->registerJavascript(
                'sua-add-to-cart-upsell-modal',
                'modules/' . $this->name . '/views/js/fo_add_to_cart_upsell_modal.js',
                [
                    'priority' => 504,
                    'attribute' => 'async',
                ]
            );
        }

        if ($this->context->controller->php_self === "cart" ||
            $this->context->controller->php_self === "product"
        ) {
            $this->ajaxHookLink = $this->context->link->getModuleLink(
                'smartupselladvanced',
                'ajax'
            );
        }
    }

    /**
     * Display special offers at the bottom of the shopping cart
     *
     * @param array $params
     *
     * @return mixed
     */
    public function hookDisplayShoppingCartFooter(array $params)
    {
        /** @var \Invertus\SmartUpsellAdvanced\Helper\SpecialOfferHelper $specialOfferHelper */
        $specialOfferHelper = $this->getService('smartupselladvanced.helper.specialoffer');
        $specialOfferFilter = $this->getService('smartupselladvanced.clientbusinesslogicprovider.filter');

        $productsInCart = $this->context->cart->getProducts();
        $allSpecialOffers = $specialOfferHelper->getSpecialOffers($productsInCart);
        
        $specialOffers = $specialOfferFilter->filterSpecialOffers(
            $allSpecialOffers,
            $productsInCart,
            $this->context->customer->id_default_group,
            $this->context->customer->id
        );

        foreach ($specialOffers as $specialOffer) {
            if ((int) $specialOffer['id_shop'] !== $this->context->shop->id) {
                return;
            }
        }

        $customerId = $this->context->customer->id;
        if ($customerId === null) {
            $customerId = $this->context->customer->id_guest;
        }

        $specialOffersSmartyFriendly = $specialOfferHelper->getSmartyFriendlySpecialOffers(
            $specialOffers,
            $this->ajaxHookLink,
            $customerId
        );
        $this->context->smarty->assign([
            'special_offers' => $specialOffersSmartyFriendly,
        ]);
        $this->context->customer->getBoughtProducts();

        return $this->fetch($this->getLocalPath(). '/views/templates/hook/special_offer.tpl');
    }

    /**
     * Display upsell products at the bottom of the product page
     *
     * @param array $params
     *
     * @return mixed
     */
    public function hookDisplayFooterProduct(array $params)
    {
        /** @var UpsellHelper $upsellHelper */
        $upsellHelper = $this->getService('smartupselladvanced.helper.upsell');
        $currentProductId = Tools::getValue('id_product');
        $showModal = (bool)Configuration::get('SUA_OUT_OF_STOCK_POPUP');

        $smartyFriendlyUpsells = $upsellHelper->getSmartyFriendlyUpsells(
            $currentProductId,
            $this->context->shop->id,
            $this->context->language->id,
            $this->context->currency,
            $this->ajaxHookLink
        );

        $this->context->smarty->assign([
            'upsells' => $smartyFriendlyUpsells,
            'show_modal' => $showModal,

        ]);
        return $this->fetch($this->getLocalPath() . '/views/templates/hook/upsell_product.tpl');
    }

    /**
     * Set how long the same special offer souldn't be shown to the same customer
     *
     * @param array $params
     */
    public function hookActionPaymentConfirmation(array $params)
    {
        $dateTimeNow = date('Y-m-d H:i:s');
        $order = new Order($params['id_order']);
        $cartId = $order->id_cart;
        $specialOffersInCart = SpecialOfferCartRelationRepository::getSpecialOffersInCartByCartId($cartId);
        $settings = SettingsRepository::getSettings();
        $sameProductToSameClient = $settings['SUA_SAME_OFFER_TO_SAME_CLIENT'];

        // Add one to times used
        if (!empty($specialOffersInCart)) {
            foreach ($specialOffersInCart as $specialOfferInCart) {
                SpecialOfferCartRelationRepository::incrementTimesUsed($specialOfferInCart['id_special_offer']);
            }
            if ($sameProductToSameClient === 'show') {
                // Delete specific price and cart relation
                SpecificPrice::deleteByIdCart($cartId);
                SpecialOfferCartRelationRepository::removeSpecialOfferRelation($cartId);
            }
            if ($sameProductToSameClient === 'dont_show') {
                // Add 100 years from today to date expires
                //@todo use DateTime objects
                $dateTimeExpires = date('Y-m-d H:i:s', strtotime($dateTimeNow . ' +100 year'));
                SpecialOfferCartRelationRepository::updateDateExpires($cartId, $dateTimeExpires);
            }
            if ($sameProductToSameClient === 'dont_show_month') {
                // Add a month from today to date expires
                $dateTimeExpires = date('Y-m-d H:i:s', strtotime($dateTimeNow . ' +1 month'));
                SpecialOfferCartRelationRepository::updateDateExpires($cartId, $dateTimeExpires);
            }
            if ($sameProductToSameClient === 'dont_show_year') {
                // Add a year from today to date expires
                $dateTimeExpires = date('Y-m-d H:i:s', strtotime($dateTimeNow . ' +1 year'));
                SpecialOfferCartRelationRepository::updateDateExpires($cartId, $dateTimeExpires);
            }
        }
    }

    /**
     * Get a service from DI container.
     *
     * @param string $id
     * PHP Lint.    *
     * @return mixed
     */
    public function getService($id)
    {
        return $this->moduleContainer->get($id);
    }

    /**
     * Include autoloader path
     */
    private function autoload()
    {
        require_once $this->getLocalPath() . 'vendor/autoload.php';
    }

    /**
     * Adds Cache To DI Container.
     */
    private function compile()
    {
        $containerCache = $this->getLocalPath() . 'var/cache/container.php';
        $containerConfigCache = new ConfigCache($containerCache, self::DISABLE_CACHE);
        $containerClass = get_class($this) . 'Container';
        if (!$containerConfigCache->isFresh()) {
            $containerBuilder = new ContainerBuilder();
            $locator = new FileLocator($this->getLocalPath() . 'config');
            $loader = new YamlFileLoader($containerBuilder, $locator);
            $loader->load('services.yml');
            $containerBuilder->compile();
            $dumper = new PhpDumper($containerBuilder);
            $containerConfigCache->write(
                $dumper->dump(['class' => $containerClass]),
                $containerBuilder->getResources()
            );
        }
        require_once $containerCache;
        $this->moduleContainer = new $containerClass();
    }

    /**
     * Show user feedback message
     *
     * @return string
     */
    public function getFeedbackMessage()
    {
        $query = new DbQuery();
        $query->select('date_add');
        $query->from('module_history');
        $query->where('id_module = '.$this->id);
        $result = Db::getInstance()->getValue($query);
        if ($result) {
            $date1 = new DateTime($result);
            $date2 = new DateTime(date('Y-m-d H:i:s'));
            $interval = date_diff($date1, $date2);
            $feedbackTime = $interval->m + ($interval->y * 12) < 3;
            if (!Configuration::get(SmartUpsellAdvanced::FEEDBACK_CONFIGURATION) && $feedbackTime) {
                return $this->context->smarty->fetch($this->getLocalPath().'views/templates/admin/feedback.tpl');
            }
        }
        return '';
    }
}
