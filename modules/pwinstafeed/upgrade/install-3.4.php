<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_3_4($object)
{
    $pwdatabase = Db::getInstance()->execute(
        'DROP TABLE IF EXISTS '._DB_PREFIX_.'pwinstafeed_stats'
    );

    return true;
}
