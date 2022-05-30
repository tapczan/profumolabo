<?php
/**
 * @author Check AUTHORS file.
 * @copyright TBD
 * @license TBD
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once dirname(__FILE__).'/vendor/autoload.php';

use Spark\EParagony\CartPreferenceManager;
use Spark\EParagony\ConfigurationHolder;
use Spark\EParagony\ConfigHelper;
use Spark\EParagony\Constants;
use Spark\EParagony\DocumentsManager;
use Spark\EParagony\RawDbActions;
use Spark\EParagony\SupplementaryAdmin\AdminOrderDisplay;
use Spark\EParagony\SupplementaryAdmin\FormFieldGenerator;
use Spark\EParagony\SupplementaryFront\FrontDisplaySupport;
use PrestaShopBundle\Controller\Admin\Sell\Order\ActionsBarButton;

class EParagony extends Module
{
    const SWITCH_VALUES = [
        ['value' => 1, 'id' => 'active_on'],
        ['value' => 0, 'id' => 'active_off'],
    ];

    public function __construct()
    {
        /* The name value should be of the same case as the name of this file. */
        $this->name = 'eparagony';
        $this->tab = 'front_office_features';
        $this->version = '0.1.6';
        $this->author = 'Spark';
        $this->need_instance = 1;
        $this->ps_versions_compliancy = [
            'min' => '1.7.8',
            'max' => '1.7.99',
        ];
        $this->bootstrap = true;


        parent::__construct();

        $this->displayName = $this->l('Dokumenty fiskalne: E-Paragony i E-Faktury');
        $this->description = $this->l('Electronic receipt and invoice subsystem.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall? It is not supported.');

        if ($this->version !== Constants::PLUGIN_VERSION) {
            throw new LogicException('Fix plugin version constant.');
        }
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function install()
    {
        return
            parent::install()
            && $this->registerHooks()
            && RawDbActions::createAllTables()
        ;
    }

    public function registerHooks()
    {
        return $this->registerHook('actionOrderHistoryAddAfter')
            && $this->registerHook('actionAdminControllerSetMedia')
            && $this->registerHook('actionValidateOrder')
            && $this->registerHook('displayAdminOrderTop')
            && $this->registerHook('actionGetAdminOrderButtons')
            && $this->registerHook('displayPaymentTop')
            && $this->registerHook('displayAdminOrderTabLink')
            && $this->registerHook('displayAdminOrderTabContent')
        ;
    }

    public function hookActionAdminControllerSetMedia($params)
    {
        $script = $this->getPathUri() . 'views/js/admin.js?v=' . Constants::ADMIN_SCRIPT_VERSION;
        $this->context->controller->addJS($script);
    }

    public function hookActionOrderHistoryAddAfter($params)
    {
        $orderHistory = $params['order_history'];
        assert($orderHistory instanceof OrderHistory);
        $documentsManager = $this->get(DocumentsManager::class);
        assert($documentsManager instanceof DocumentsManager);
        $documentsManager->handleHistoryChange($orderHistory);
    }

    public function hookActionValidateOrder($params)
    {
        $manager = $this->get(CartPreferenceManager::class);
        assert($manager instanceof CartPreferenceManager);
        $manager->tryPopulatePhone($params['cart'], $params['order']);
    }

    public function hookDisplayAdminOrderTop($params)
    {
        $aod = $this->get(AdminOrderDisplay::class);
        assert($aod instanceof AdminOrderDisplay);

        return $aod->getTopContent($params['id_order']);
    }

    public function hookActionGetAdminOrderButtons(array $params)
    {
        $aod = $this->get(AdminOrderDisplay::class);
        assert($aod instanceof AdminOrderDisplay);
        $btnOptions = $aod->getButton($params['id_order']);

        if ($btnOptions['display']) {
            $bar = $params['actions_bar_buttons_collection'];
            switch ($btnOptions['code']) {
                case $aod::BTN_CODE_ISSUE:
                    $label = $this->trans('Prepare e-receipt', [], 'Modules.Eparagony.Eparagony');
                    break;
                case $aod::BTN_CODE_ISSUING:
                    $label = $this->trans('E-receipt is being issued', [], 'Modules.Eparagony.Eparagony');
                    break;
                case $aod::BTN_CODE_ISSUED:
                    $label = $this->trans('E-receipt has been issued', [], 'Modules.Eparagony.Eparagony');
                    break;
                case $aod::BTN_CODE_ERROR:
                    $label = $this->trans('Error preparing e-receipt', [], 'Modules.Eparagony.Eparagony');
                default:
                    $label = $btnOptions['code'];

            }
            $class = $btnOptions['grayed'] ? 'btn-secondary' : 'btn-primary';
            if ($btnOptions['additionalClass']) {
                $class .= ' ' . $btnOptions['additionalClass'];
            }
            $properties = [];
            if ($btnOptions['url']) {
                $properties['href'] = $btnOptions['url'];
            }
            $bar->add(
                new ActionsBarButton($class, $properties, $label)
            );
        }
    }

    public function hookDisplayAdminOrderTabLink($params)
    {
        $aod = $this->get(AdminOrderDisplay::class);
        assert($aod instanceof AdminOrderDisplay);

        return $aod->getTabLink($params['id_order']);
    }

    public function hookDisplayAdminOrderTabContent($params)
    {
        $aod = $this->get(AdminOrderDisplay::class);
        assert($aod instanceof AdminOrderDisplay);

        return $aod->getTabContent($params['id_order']);
    }

    public function hookDisplayPaymentTop($params)
    {
        $support = $this->get(FrontDisplaySupport::class);
        assert($support instanceof FrontDisplaySupport);
        $vars = $support->displayOnPayment($this->name, $this->context);
        if ($vars) {
            $this->smarty->assign($vars);

            return $this->display(__FILE__, 'display_payment_top.tpl');
        } else {
            return '';
        }
    }

    private function generateForm(ConfigurationHolder $holder)
    {
        $formSpark = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Settings for Spark'),
                ],
                'input' => [
                    [
                        'type' => 'text',
                        'label' => $this->l('POS ID'),
                        'name' => ConfigurationHolder::POS_ID,
                        'size' => 20,
                        'required' => true,
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Store NIP'),
                        'name' => ConfigurationHolder::STORE_NIP,
                        'size' => 20,
                        'required' => true,
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Client ID'),
                        'name' => ConfigurationHolder::CLIENT_ID,
                        'size' => 20,
                        'required' => true,
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Client secret'),
                        'name' => ConfigurationHolder::CLIENT_SECRET,
                        'size' => 20,
                        'required' => true,
                    ],
                    [
                        'label' => $this->l('Address for web hook'),
                        'type' => 'html',
                        'name' => Context::getContext()->link->getModuleLink($this->name, 'spark'),
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Webhook secret'),
                        'name' => ConfigurationHolder::WEBHOOK_SECRET,
                        'size' => 20,
                        'required' => true,
                    ],
                    [
                        'type' => 'switch',
                        'values' => self::SWITCH_VALUES,
                        'label' => $this->l('Test environment'),
                        'name' => ConfigurationHolder::TEST_MODE,
                        'required' => true,
                    ],
                    [
                        'type' => 'switch',
                        'values' => self::SWITCH_VALUES,
                        'label' => $this->l('Log Spark requests'),
                        'name' => ConfigurationHolder::LOG_SPARK_REQUESTS,
                        'required' => true,
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Return policy URL'),
                        'name' => ConfigurationHolder::RETURN_POLICY_SPARK,
                        'size' => 20,
                        'required' => false,
                    ],
                ],
            ],
        ];
        $formPrinter = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Settings for print server'),
                ],
                'input' => [
                    [
                        'label' => $this->l('Address for print server'),
                        'type' => 'html',
                        'name' => Context::getContext()->link->getModuleLink($this->name, 'printerapi'),
                    ],
                    [
                        'type' => 'select',
                        /* Yes, this has to look like this. */
                        'options' => [
                            'query' => [
                                ['name' => 'NOVITUS', 'id' => 'novitus'],
                                ['name' => 'POSNET', 'id' => 'posnet'],
                            ],
                            'id' => 'id',
                            'name' => 'name',
                        ],
                        'label' => $this->l('Printer type'),
                        'name' => ConfigurationHolder::PRINTER_TYPE,
                        'required' => true,
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Username'),
                        'name' => ConfigurationHolder::PRINTER_USERNAME,
                        'size' => 20,
                        'required' => true,
                    ],
                    [
                        'label' => $this->l('Password'),
                        'type' => 'html',
                        'name' => FormFieldGenerator::password(
                            ConfigurationHolder::PRINTER_PASSWORD,
                            $holder->printer_password
                        ),
                        'required' => true,
                    ],
                ],
            ],
        ];
        $formTax = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Tax letters'),
                ],
                'input' => [
                    [
                        'type' => 'text',
                        'label' => $this->l('Tax A'),
                        'name' => ConfigurationHolder::TAX_A,
                        'size' => 10,
                        'required' => true,
                        'suffix' => '%',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Tax B'),
                        'name' => ConfigurationHolder::TAX_B,
                        'size' => 10,
                        'required' => true,
                        'suffix' => '%',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Tax C'),
                        'name' => ConfigurationHolder::TAX_C,
                        'size' => 10,
                        'required' => true,
                        'suffix' => '%',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Tax D'),
                        'name' => ConfigurationHolder::TAX_D,
                        'size' => 10,
                        'required' => true,
                        'suffix' => '%',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Tax E'),
                        'name' => ConfigurationHolder::TAX_E,
                        'size' => 10,
                        'required' => true,
                        'suffix' => '%',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Tax F'),
                        'name' => ConfigurationHolder::TAX_F,
                        'size' => 10,
                        'required' => true,
                        'suffix' => '%',
                    ],
                    [
                        'type' => 'text',
                        'label' => $this->l('Tax G'),
                        'name' => ConfigurationHolder::TAX_G,
                        'size' => 10,
                        'required' => true,
                        'suffix' => '%',
                    ],
                ],
            ],
        ];
        $formOther = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Other settings'),
                ],
                'input' => [
                    [
                        'type' => 'switch',
                        'values' => self::SWITCH_VALUES,
                        'label' => $this->l('Ask for phone if not provided but e-receipt is expected.'),
                        'name' => ConfigurationHolder::ASK_FOR_PHONE,
                        'required' => true,
                    ],
                ],
            ],
        ];
        $formSave = [
            'form' => [
                'submit' => [
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right',
                ],
            ],
        ];

        $helper = new HelperForm();
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&' . http_build_query(['configure' => $this->name]);
        $helper->submit_action = 'submit_' . $this->name;
        $helper->fields_value = (array)$holder;

        FormFieldGenerator::addJS($this->context->controller, $this->getPathUri());

        return $helper->generateForm([
            $formSpark,
            $formPrinter,
            $formTax,
            $formOther,
            $formSave,
        ]);
    }

    public function getContent($wombat = null)
    {
        $output = '';

        if (Tools::isSubmit('submit_' . $this->name)) {
            $holder = new ConfigurationHolder();
            $holder->pos_id = (string) Tools::getValue(ConfigurationHolder::POS_ID);
            $holder->store_nip = (string) Tools::getValue(ConfigurationHolder::STORE_NIP);
            $holder->client_id = (string) Tools::getValue(ConfigurationHolder::CLIENT_ID);
            $holder->client_secret = (string) Tools::getValue(ConfigurationHolder::CLIENT_SECRET);
            $holder->webhook_secret = (string) Tools::getValue(ConfigurationHolder::WEBHOOK_SECRET);
            $holder->test_mode = (bool)Tools::getValue(ConfigurationHolder::TEST_MODE);
            $holder->log_spark_requests = (bool)Tools::getValue(ConfigurationHolder::LOG_SPARK_REQUESTS);
            $holder->return_policy_spark = Tools::getValue(ConfigurationHolder::RETURN_POLICY_SPARK);

            $holder->tax_a = Tools::getValue(ConfigurationHolder::TAX_A);
            $holder->tax_b = Tools::getValue(ConfigurationHolder::TAX_B);
            $holder->tax_c = Tools::getValue(ConfigurationHolder::TAX_C);
            $holder->tax_d = Tools::getValue(ConfigurationHolder::TAX_D);
            $holder->tax_e = Tools::getValue(ConfigurationHolder::TAX_E);
            $holder->tax_f = Tools::getValue(ConfigurationHolder::TAX_F);
            $holder->tax_g = Tools::getValue(ConfigurationHolder::TAX_G);

            $holder->printer_type = (string) Tools::getValue(ConfigurationHolder::PRINTER_TYPE);
            $holder->printer_username = (string) Tools::getValue(ConfigurationHolder::PRINTER_USERNAME);
            $holder->printer_password = (string) Tools::getValue(ConfigurationHolder::PRINTER_PASSWORD);

            $holder->ask_for_phone = (bool)Tools::getValue(ConfigurationHolder::ASK_FOR_PHONE);

            if (!$holder->isValid($errors)) {
                $output = $this->displayError($this->l('Invalid config.') . ' ' . Constants::E_CONFIG);
                if ($errors['tax_values']) {
                    $output .= $this->displayError($this->l('Invalid tax values.') . ' ' . Constants::E_CONFIG_TAX);
                }
            } else {
                ConfigHelper::saveConfig($holder);
                $output = $this->displayConfirmation($this->l('Config updated.'));
            }
        } else {
            $holder = ConfigHelper::getSavedConfig();
        }

        return $output . $this->generateForm($holder);
    }
}
