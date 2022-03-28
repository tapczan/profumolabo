<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once (dirname(__FILE__) . '/../x13links.php');

function upgrade_module_1_3_0($module)
{
	$r = true;
	$r &= $module->registerHook('displayBackOfficeHeader');
	$r &= $module->registerHook('actionAdminControllerSetMedia');
	$r &= $module->registerHook('displayHome');
	
	$module->uninstallOverrides();
    $module->installOverrides();
	
	return $r;
}
