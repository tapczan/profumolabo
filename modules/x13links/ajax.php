<?php

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/x13links.php');

if (substr(Tools::encrypt('x13links/index'),0,10) != Tools::getValue('token') || !Module::isInstalled('x13links'))
	die('Bad token');

$id_lang = (int)Tools::getValue('id_lang');
if ($id_lang) {
	Context::getContext()->language = new Language($id_lang);
}

$id_shop = (int)Tools::getValue('id_shop');
if ($id_shop) {
    Context::getContext()->shop = new Shop($id_shop);
}

$x13links = new x13links();
$duplicates = $x13links->getDuplicateProductLinks();

$return = array();

if (!$duplicates) {
	$return['success'] = 1;
} else {
	$return['duplicates'] = $duplicates;
	$return['message'] = $x13links->l('There are duplicate product links', 'ajax');
	if (!$x13links->is_1_7()) {
		$return['modal'] = $x13links->renderModal();
	}
}


echo json_encode($return);
