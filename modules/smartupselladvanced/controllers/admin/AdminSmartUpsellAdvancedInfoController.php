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

use Invertus\SmartUpsellAdvanced\Controller\AdminSmartUpsellAbstractController;

/**
 * Class AdminAttributesListInfoController
 */
class AdminSmartUpsellAdvancedInfoController extends AdminSmartUpsellAbstractController
{
    /**
     * AdminSmartUpsellAdvancedInfoController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->content = $this->module->getFeedbackMessage();
    }

    /**
     * @var bool module design is compatible with Bootstrap
     */
    public $bootstrap = true;

    /**
     * Loads CSS and JS files
     */
    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        $this->addCSS($this->module->getLocalPath().'/views/css/info.css');
    }

    /**
     * Main controller function responsible for page actions
     */
    public function postProcess()
    {
        parent::postProcess();

        $this->displayContent();
    }

    /**
     * Assigns variables into Smarty and also assigns needed template to page content
     */
    private function displayContent()
    {
        $manual_uri = __PS_BASE_URI__.'modules/smartupselladvanced/manual/manual-en.pdf';

        if ($this->context->language->iso_code === 'fr') {
            $manual_uri = __PS_BASE_URI__.'modules/smartupselladvanced/manual/manual-fr.pdf';
        }
        $this->context->smarty->assign([
            'images_uri' => __PS_BASE_URI__.'modules/smartupselladvanced/views/img/',
            'manual_uri' => $manual_uri,
        ]);

        $this->content .= $this->context->smarty->fetch(
            $this->module->getLocalPath().'views/templates/admin/info.tpl'
        );
    }
}
