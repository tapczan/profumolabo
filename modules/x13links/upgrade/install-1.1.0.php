<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once (dirname(__FILE__) . '/../x13links.php');

function upgrade_module_1_1_0($module)
{
	$module->uninstallOverrides();
    $module->installOverrides();
			
	Configuration::updateValue('SEOURL_PRODUCT_RULE', x13linksModel::$configNames['SEOURL_PRODUCT_RULE']['default']);
	Configuration::updateValue('SEOURL_CATEGORY_RULE', x13linksModel::$configNames['SEOURL_CATEGORY_RULE']['default']);
	Configuration::updateValue('SEOURL_SUPPLIER_RULE', x13linksModel::$configNames['SEOURL_SUPPLIER_RULE']['default']);
	Configuration::updateValue('SEOURL_MANUFACTURER_RULE', x13linksModel::$configNames['SEOURL_MANUFACTURER_RULE']['default']);
	Configuration::updateValue('SEOURL_CMS_CATEGORY_RULE', x13linksModel::$configNames['SEOURL_CMS_CATEGORY_RULE']['default']);
	Configuration::updateValue('SEOURL_CMS_RULE', x13linksModel::$configNames['SEOURL_CMS_RULE']['default']);

    return true;
}
