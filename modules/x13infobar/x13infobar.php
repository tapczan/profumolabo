<?php
/**
* @author    Krystian Podemski for x13.pl <krystian@x13.pl>
* @copyright Copyright (c) 2019 Krystian Podemski - www.x13.pl
* @license   Commercial license, only to use on restricted domains
*/

if (!defined('X13_INFOBAR_ION_VERSION')) {
    if (PHP_VERSION_ID >= 70100) {
        $x13IonVer = '-71';
        $x13IonFolder = 'php71';
    } else if (PHP_VERSION_ID >= 70000) {
        $x13IonVer = '-7';
        $x13IonFolder = 'php70';
    } else {
        $x13IonVer = '';
        $x13IonFolder = 'php5';
    }

    if (file_exists(_PS_MODULE_DIR_.'x13infobar/dev')) {
        $x13IonVer = '';
        $x13IonFolder = 'php5';
    }

    define('X13_INFOBAR_ION_VERSION', $x13IonVer);
    define('X13_INFOBAR_FOLDER', $x13IonFolder);
}

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_.'x13infobar/classes/'.X13_INFOBAR_FOLDER.'/InformationBar.php';
require_once _PS_MODULE_DIR_.'x13infobar/classes/'.X13_INFOBAR_FOLDER.'/InformationBarRenderer.php';
require_once _PS_MODULE_DIR_.'x13infobar/x13infobar.core'. X13_INFOBAR_ION_VERSION . '.php';

class X13InfoBar extends X13InfoBarCore
{
    public $is_16;
    public $is_17;

    public function __construct()
    {
        $this->name = 'x13infobar';
        $this->tab = 'front_office_features';
        $this->version = '1.3.1';
        $this->author = 'X13.pl';
        $this->need_instance = 0;
        $this->is_configurable = 0;
        $this->ps_versions_compliancy['min'] = '1.6';
        $this->ps_versions_compliancy['max'] = _PS_VERSION_;
        $this->secure_key = Tools::encrypt($this->name);
        $this->is_16 = (version_compare(_PS_VERSION_, '1.6.0', '>=') === true && version_compare(_PS_VERSION_, '1.7.0', '<') === true) ? true : false;
        $this->is_17 = (version_compare(_PS_VERSION_, '1.7.0', '>=') === true) ? true : false;
        $this->controllers = array();
        $this->bootstrap = true;

        if (Shop::isFeatureActive()) {
            Shop::addTableAssociation('information_bar', array('type' => 'shop'));
        }

        parent::__construct();

        $this->displayName = $this->l('Information Bar');
        $this->description = $this->l('Adds an information bar at the top of your store, inform your Customers about promotions, vacations etc.');

        $this->confirmUninstall = $this->l('Are you sure you want to delete this module and all its data?');
    }

    public function getContent()
    {
        return parent::getContent();
    }

    public static function translatedCounterTexts()
    {
        $instance = self::getInstanceByName('x13infobar');
        return array(
            'seconds' => array(
                $instance->l('s'),
                $instance->l('sec.'),
                $instance->l('seconds'),
            ),
            'minutes' => array(
                $instance->l('m'),
                $instance->l('min.'),
                $instance->l('minutes'),
            ),
            'hours' => array(
                $instance->l('h'),
                $instance->l('hour.'),
                $instance->l('hours'),
            ),
            'days' => array(
                $instance->l('d'),
                $instance->l('days'),
            ),
        );
    }
}
