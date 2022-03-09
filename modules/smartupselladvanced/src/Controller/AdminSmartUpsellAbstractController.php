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

namespace Invertus\SmartUpsellAdvanced\Controller;

use PrestaShop\PrestaShop\Adapter\Entity\ModuleAdminController;

/**
 * Class AdminAttributesListInfoController
 */
abstract class AdminSmartUpsellAbstractController extends ModuleAdminController
{

    /**
     * Loads CSS and JS files
     */
    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        $this->addJS(_MODULE_DIR_.'smartupselladvanced/views/js/bo_feedback.js');
    }

    /**
     * Close feedback pop up permanently
     *
     * @return bool
     */
    public function ajaxProcessCloseFeedback()
    {
        \Configuration::updateValue(\SmartUpsellAdvanced::FEEDBACK_CONFIGURATION, 1);
        return true;
    }
}
