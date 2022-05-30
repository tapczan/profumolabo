<?php
/**
 * Paragon czy Faktura.
 *
 * @author    x13.pl <x13@x13.pl>
 * @copyright 2018-2020 - x13 (www.x13.pl)
 * @license   Commercial license, only to use on restricted domains
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

if (!defined('X13_PARAGONFAKTURA_ION_VERSION')) {
    if (PHP_VERSION_ID >= 70100) {
        $x13IonVer = '-71';
    } elseif (PHP_VERSION_ID >= 70000) {
        $x13IonVer = '-7';
    } else {
        $x13IonVer = '';
    }

    if (file_exists(_PS_MODULE_DIR_.'x13paragonlubfaktura/dev')) {
        $x13IonVer = '';
        $x13IonFolder = 'php5';
    }

    define('X13_PARAGONFAKTURA_ION_VERSION', $x13IonVer);
}

require_once _PS_MODULE_DIR_.'x13paragonlubfaktura/x13paragonlubfaktura.core'.X13_PARAGONFAKTURA_ION_VERSION.'.php';

class x13paragonlubfaktura extends x13paragonlubfakturaCore
{
    public function __construct()
    {
        $this->name = 'x13paragonlubfaktura';
        $this->tab = 'front_office_features';
        $this->version = '1.4.0';
        $this->author = 'X13.pl';
        $this->bootstrap = true;
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('Receipt or invoice');
        $this->description = $this->l('Allow customer to select proof of purchase (receipt or invoice)');
        $this->ps_version = substr(_PS_VERSION_, 0, 3);
    }
}
