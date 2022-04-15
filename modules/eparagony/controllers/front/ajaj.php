<?php
/**
 * @author Check AUTHORS file.
 * @copyright TBD
 * @license TBD
 */

use ASoftwareHouse\EParagony\SupplementaryFront\FrontAction;

class EparagonyAjajModuleFrontController extends ModuleFrontController
{
    private $action;
    private $fullPayload;
    private $code;
    private $response;

    public function init()
    {
        $this->ajax = true;
        parent::init();
    }

    public function postProcess()
    {
        parent::postProcess();
        $this->action = Tools::getValue('action');
        $this->fullPayload = Tools::getAllValues();
    }

    public function process()
    {
        parent::process();
        $service = $this->get(FrontAction::class);
        assert($service instanceof FrontAction);
        list($this->code, $this->response) = $service->runAction($this->action, $this->context, $this->fullPayload);
    }

    public function displayAjax()
    {
        http_response_code($this->code);
        if ($this->code === 204) {
            /* Nothing to display. */
            return;
        }
        header('Content-Type: application/json;charset=utf-8');
        $this->ajaxRender(json_encode((object)$this->response));
    }
}
