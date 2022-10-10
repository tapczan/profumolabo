<?php
/**
 * @author Check AUTHORS file.
 * @copyright TBD
 * @license TBD
 */

use Spark\EParagony\SparkApi\WebHookSpark;

class EparagonySparkModuleFrontController extends ModuleFrontController
{
    private $payload;
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
        $rawPayload = Tools::file_get_contents('php://input');
        $decoded = json_decode($rawPayload, true);
        if (!is_array($decoded)) {
            /* Force array. */
            $decoded = [];
        }
        $this->payload = $decoded;
    }

    public function process()
    {
        parent::process();
        $service = $this->get(WebHookSpark::class);
        assert($service instanceof WebHookSpark);
        list($this->code, $this->response) = $service->handle($this->payload);
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
