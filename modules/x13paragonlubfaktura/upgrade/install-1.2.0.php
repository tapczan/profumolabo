<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_2_0($object)
{
    Configuration::updateValue('X13_RECIEPTORINVOICE_ORDERLIST', 1);
    return $object->registerHook('actionAdminOrdersListingFieldsModifier') && $object->registerHook('displayHeader');
}
