<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once (dirname(__FILE__) . '/../x13links.php');

function upgrade_module_1_2_0($module)
{
	$module->uninstallOverrides();
    $module->installOverrides();
			
    return true;
}
