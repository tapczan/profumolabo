<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_1_4_0($module)
{
    if (version_compare(_PS_VERSION_, '1.7.7', '>=')) {
        $module->registerHook(
            [
                'actionOrderGridQueryBuilderModifier',
                'actionOrderGridDefinitionModifier',
                'actionAdminControllerSetMedia',
                'displayAdminOrderMain',
                'displayOrderPreview',
                'actionAdminControllerSetMedia'
            ]
        );
    }

    return $module->registerHook(
        [
            'displayBackOfficeHeader',
            'displayPaymentTop',
            'displayPDFInvoice'
        ]
    );
}
