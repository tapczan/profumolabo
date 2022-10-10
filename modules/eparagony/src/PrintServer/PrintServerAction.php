<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony\PrintServer;

use Spark\EParagony\DatabaseError;
use Spark\EParagony\DocumentsManager;
use Spark\EParagony\Entity\EparagonyDocumentStatus;
use Spark\EParagony\PrinterLogManager;
use Throwable;

/**
 * This class works as advanced factory.
 */
class PrintServerAction
{
    const ACTION_LOGIN = 'login';
    const ACTION_POLL = 'poll';
    const ACTION_STATUS = 'status';

    const PRINT_STATUS_FAILURE = 'failure';
    const PRINT_STATUS_SUCCESS = 'success';

    const PRINT_STATUS_TYPE_COMM_ERROR = 'comm_error';
    const PRINT_STATUS_TYPE_FORMAT_ERROR = 'format_error';
    const PRINT_STATUS_TYPE_TIME_SKEW = 'time_skew';
    const PRINT_STATUS_TYPE_PROTOCOL_ERROR = 'protocol_error';
    const PRINT_STATUS_TYPE_ALREADY_PRINTED = 'already_printed';

    const QUEUE_REPORT_CANCELLED = 'cancelled';
    const QUEUE_REPORT_ERROR = 'error';
    const QUEUE_REPORT_IGNORED = 'ignored';
    const QUEUE_REPORT_MOVED_TO_END = 'moved_to_end';
    const QUEUE_REPORT_NOT_FOUND = 'not_found';
    const QUEUE_REPORT_REMOVED = 'removed'; /* This is success. */

    const API_STATUS_FAILURE = 'failure';
    const API_TYPE_AUTH_ERROR = 'auth_error';
    const API_TYPE_OTHER_ERROR = 'other_error';

    private $loginService;
    private $dm;
    private $lm;

    public function __construct(
        PrintServerLogin $loginService,
        DocumentsManager $dm,
        PrinterLogManager $lm
    ) {
        $this->loginService = $loginService;
        $this->dm = $dm;
        $this->lm = $lm;
    }

    public function runAction($action, $payload, $token)
    {
        switch ($action) {
            case self::ACTION_STATUS:
                list($code, $ret) = $this->actionStatus($payload, $token);
                break;
            case self::ACTION_POLL:
                list($code, $ret) = $this->actionPool($payload, $token);
                break;
            case self::ACTION_LOGIN:
                list($code, $ret) = $this->actionLogin($payload);
                break;
            default:
                $ret = [
                    'status' => self::API_STATUS_FAILURE,
                    'type' => self::API_TYPE_OTHER_ERROR,
                    'description' => 'Invalid action.',
                ];
                $code = 404;
        }

        return [$code, $ret];
    }

    private static function isPrintStatusAlternativeSuccess($status)
    {
        switch ($status) {
            case self::PRINT_STATUS_TYPE_ALREADY_PRINTED:
                return true;
            default:
                return false;
        }
    }

    private static function isPrintStatusTemporaryFailure($status, $errorCode)
    {
        #Temporal section.
        if (!$errorCode) {
            return true;
        }

        switch ($status) {
            case self::PRINT_STATUS_TYPE_COMM_ERROR:
            case self::PRINT_STATUS_TYPE_TIME_SKEW:
                return true;
            default:
                return false;
        }
    }

    private function actionStatus($payload, $token)
    {
        $privileges = $this->loginService->getPrivileges($token);
        if ($privileges) {
            $this->lm->addLog($payload);

            $tag = $payload['RID'] ?? null;
            $printStatus = $payload['status'] ?? null;
            $document = $this->dm->getByTag($tag);
            if ($document) {
                try {
                    if ($printStatus === self::PRINT_STATUS_SUCCESS) {
                        $this->dm->finishQueue($document);
                        return [200, [
                            'RID' => $tag,
                            'status' => self::QUEUE_REPORT_REMOVED,
                        ]];
                    } elseif ($printStatus === self::PRINT_STATUS_FAILURE) {
                        $printStatusType = $payload['type'] ?? null;
                        $printErrorCode = $payload['errorCode'] ?? $payload['errorcode'] ?? null;
                        if (self::isPrintStatusAlternativeSuccess($printStatusType)) {
                            $this->dm->finishQueue($document);
                            return [200, [
                                'RID' => $tag,
                                'status' => self::QUEUE_REPORT_REMOVED,
                            ]];
                        } elseif (self::isPrintStatusTemporaryFailure($printStatusType, $printErrorCode)) {
                            $this->dm->repeatQueue($document);
                            return [200, [
                                'RID' => $tag,
                                'status' => self::QUEUE_REPORT_MOVED_TO_END,
                            ]];
                        } else {
                            $this->dm->cancelQueue($document);
                            return [200, [
                                'RID' => $tag,
                                'status' => self::QUEUE_REPORT_CANCELLED,
                            ]];
                        }
                    } else {
                        return [409, [
                            'RID' => $tag,
                            'status' => self::QUEUE_REPORT_IGNORED,
                        ]];
                    }
                } catch (DatabaseError $ex) {
                    return [503, [
                        'RID' => $tag,
                        'status' => self::QUEUE_REPORT_ERROR,
                    ]];
                }
            } else {
                return [404, [
                    'RID' => $tag,
                    'status' => self::QUEUE_REPORT_NOT_FOUND,
                ]];
            }
        } else {
            return [401, [
                'status' => self::API_STATUS_FAILURE,
                'type' => self::API_TYPE_AUTH_ERROR,
                'description' => 'Invalid token.'
            ]];
        }
    }

    private function actionPool($payload, $token)
    {
        $privileges = $this->loginService->getPrivileges($token);
        if ($privileges) {
            $status = $this->dm->getFromQueue();
            if (!$status) {
                /* Repeat once. */
                $status = $this->dm->getFromQueue();
            }
            if ($status) {
                $payload = $this->formatPrinterCommand($status);
                return [200, $payload];
            } else {
                return [204, null];
            }
        } else {
            return [401, [
                'status' => self::API_STATUS_FAILURE,
                'type' => self::API_TYPE_AUTH_ERROR,
                'description' => 'Invalid token.'
            ]];
        }
    }

    private function formatPrinterCommand(EparagonyDocumentStatus $documentStatus)
    {
        $rest = $documentStatus->getRest();
        $decoded = json_decode($rest, true);

        return [
            'RID' => $documentStatus->getTextId(),
            'timestamp' => time(),
            'protocol' => $decoded['printer'],
            'format' => 'hex',
            'printerData' => $decoded['command'],
        ];
    }

    private function actionLogin($payload)
    {
        $username = $payload['username'] ?? null;
        $password = $payload['password'] ?? null;
        $code = 401;
        $content = [
            'error' => 'Invalid credentials.',
        ];
        if ($this->loginService->checkUsernameAndPassword($username, $password)) {
            try {
                $token = $this->loginService->logIn($username, $password);
                $now = time();
                $validTo = $token->getValidTo()->getTimestamp();
                $code = 200;
                $content = [
                    'access_token' => $token->getToken(),
                    'expires_in' => $validTo - $now,
                ];
            } catch (Throwable $ex) {
                $code = 500;
                $content = [
                    'error' => 'Cannot generate token.',
                ];
            }
        }

        return [$code, $content];
    }
}
