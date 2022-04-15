<?php
/**
 * @author Check AUTHORS file.
 * @copyright TBD
 * @license TBD
 */

use ASoftwareHouse\EParagony\PrintServer\PrintServerAction;

class EparagonyPrinterapiModuleFrontController extends ModuleFrontController
{
    private $action;
    private $payload;
    private $token;
    private $response;
    private $code;

    public function init()
    {
        $this->ajax = true;
        parent::init();
    }

    public function postProcess()
    {
        parent::postProcess();
        $this->action = Tools::getValue('action');
        $rawPayload = Tools::file_get_contents('php://input');
        $decoded = json_decode($rawPayload, true);
        if (!is_array($decoded)) {
            /* Force array. */
            $decoded = [];
        }
        $this->payload = $decoded;
        $authorization = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
        if (preg_match('/bearer\\s+([^\\s]+)/i', $authorization, $m)) {
            $this->token = $m[1];
        } else {
            /* Fallback if server is configured to not pass authorization header. */
            $this->token = $_SERVER['HTTP_X_API_KEY'] ?? null;
        }
    }

    public function process()
    {
        parent::process();
        $service = $this->get(PrintServerAction::class);
        assert($service instanceof PrintServerAction);
        list($this->code, $this->response) = $service->runAction($this->action, $this->payload, $this->token);
    }

    public function displayAjax()
    {
        http_response_code($this->code);
        if ($this->code === 204) {
            /* Nothing to display. */
            return;
        }
        header('Content-Type: application/json;charset=utf-8');
        $this->ajaxRender(json_encode($this->response));
    }
}
