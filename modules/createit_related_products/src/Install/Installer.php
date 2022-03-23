<?php

declare(strict_types=1);


namespace PrestaShop\Module\CreateitRelatedProducts\Install;

use Module;

class Installer
{

    public function install(Module $module): bool
    {
        if (!$this->registerHooks($module)) {
            return false;
        }

        return true;
    }

    private function registerHooks(Module $module): bool
    {
        $hooks = [
            'displayRelatedProducts'
        ];

        return (bool) $module->registerHook($hooks);
    }

}