<?php

class FrontController extends FrontControllerCore
{

    protected function smartyOutputContent($content)
    {
        if (Module::isEnabled('pshowlazyimg')) {
            ob_start();
            parent::smartyOutputContent($content);
            $html = ob_get_clean();
            ob_flush();

            /** @var PShowLazyImg $module */
            $module = Module::getInstanceByName('pshowlazyimg');
            echo $module->parseOutputContent($html);
            return;
        }

        parent::smartyOutputContent($content);
    }
}
