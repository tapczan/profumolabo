<?php
/**
 * @author Check AUTHORS file.
 * @copyright TBD
 * @license TBD
 */

namespace ASoftwareHouse\EParagony\Spark;

use ASoftwareHouse\EParagony\ConfigHelper;
use ASoftwareHouse\EParagony\DocumentsManager;

class WebHookSpark
{
    const NOT_FOUND = 'Not found.';
    const NOT_CONFIGURED = 'The store plugin is not configured.';
    const INVALID_SIGN = 'Invalid sign.';
    const ACCEPTED ='Accepted.';
    const ACCEPTED_CLOSED ='Accepted and closed.';

    const WEBHOOK_CLOSED = 'CLOSED';

    private $dm;
    private $secret;
    private $logRequests;
    private $logDirectory;

    public function __construct(DocumentsManager $dm, ConfigHelper $configHelper, $logDir)
    {
        $this->dm = $dm;
        $config = $configHelper::getSavedConfig();
        $this->secret = $config->webhook_secret;
        $this->logRequests = $config->log_spark_requests;
        $this->logDirectory = $logDir;
    }

    public function handle(array $payload)
    {
        if (!$this->secret) {
            return [500, [
                'status' => self::NOT_CONFIGURED,
            ]];
        }

        $this->tryCreateLogRequest($payload);

        $payloadToken = $payload['token'] ?? '';
        if (!$payloadToken) {
            return [404, [
                'status' => self::NOT_FOUND,
            ]];
        }
        $payloadTag = $payload['sessionId'] ?? '';
        if (!$payloadTag) {
            return [404, [
                'status' => self::NOT_FOUND,
            ]];
        }

        $documentStatus = $this->dm->getByTag($payloadTag);
        if (!$documentStatus) {
            return [404, [
                'status' => self::NOT_FOUND,
            ]];
        }

        $restRaw = $documentStatus->getRest();
        $rest = json_decode($restRaw, true);
        $dbToken = $rest['sparkToken'] ?? '';
        if ($dbToken !== $payloadToken) {
            return [404, [
                'status' => self::NOT_FOUND,
            ]];
        }

        if (!$this->checkSign($payload)) {
            return [403, [
                'status' => self::INVALID_SIGN,
            ]];
        }

        $webHookStatus = $payload['status'] ?? 'blank';
        if (self::WEBHOOK_CLOSED === $webHookStatus) {
            $this->dm->confirmQueue($documentStatus);
            return [200, [
                'status' => self::ACCEPTED_CLOSED,
            ]];
        } else {
            return [200, [
                'status' => self::ACCEPTED,
            ]];
        }
    }

    private function checkSign(array $payload)
    {
        $toCheck = [
            'status' => (string)($payload['status'] ?? ''),
            'token' => (string)($payload['token'] ?? ''),
            'sessionId' => (string)($payload['sessionId'] ?? ''),
        ];
        $toCheckString = json_encode($toCheck);
        $computedHash = hash_hmac('sha512', $toCheckString, $this->secret);

        return ($computedHash === (string)($payload['sign'] ?? ''));
    }

    private function tryCreateLogRequest($payload)
    {
        if (!$this->logRequests) {
            return;
        }
        $random = bin2hex(random_bytes(3));
        $fileName = 'eparagony_webhook_spark_' . date('Y-m-d_H-i-s') . '_' . $random;
        $path = $this->logDirectory . DIRECTORY_SEPARATOR . $fileName;
        $payload = json_encode($payload, JSON_PRETTY_PRINT) . "\n";
        file_put_contents($path, $payload);
    }
}
