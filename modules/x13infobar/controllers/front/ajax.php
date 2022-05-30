<?php
require_once _PS_MODULE_DIR_ . 'x13infobar/x13infobar.php';

class X13InfoBarAjaxModuleFrontController extends ModuleFrontController
{
    public function init()
    {
        parent::init();
    }

    public function postProcess()
    {
        parent::postProcess();
    }

    public function displayAjax()
    {
        if (!$this->isTokenValid()) {
            die('Access denied');
        }

        if (Tools::getValue('closeInformationBar', 0)) {
            if (setCookie('x13infobar_'.(int) Tools::getValue('id_information_bar'), 1, time() + (10 * 365 * 24 * 60 * 60), '/')) {
                die(
                    json_encode(
                        array(
                            'hasError' => false,
                            'status' => 'success',
                        )
                    )
                );
            }
        } else {
            die(
                json_encode(
                    array(
                        'hasError' => false,
                        'status' => 'success',
                    )
                )
            );
        }
    }
}
