<?php
/**
 * Class Przelewy24ServiceRefund
 *
 * This service has the features required for cash returns.
 * @author Przelewy24
 * @copyright Przelewy24
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 *
 */

/**
 * Class Przelewy24ServiceRefund
 */
class Przelewy24ServiceRefund extends Przelewy24Service
{
    /**
     * Currency suffix.
     *
     * @var string
     */
    private $suffix = '';

    /**
     * P24 REST api client.
     *
     * @var Przelewy24RestRefund
     */
    private $restApi;

    /**
     * Array of allowed refund statuses.
     *
     * @var array
     */
    private $status = array(
        0 => 'Refund error',
        1 => 'Refund done',
        3 => 'Awaiting for refund',
        4 => 'Refund rejected',
    );

    /**
     * Default refund status.
     *
     * @var string
     */
    private $statusDefault = 'Unknown status of refund';

    /**
     * Przelewy24ServiceRefund constructor.
     *
     * @param Przelewy24     $przelewy24
     * @param string         $suffix
     * @param Przelewy24RestRefund $restApi
     */
    public function __construct(Przelewy24 $przelewy24, $suffix, $restApi)
    {
        $this->setSuffix($suffix);
        $this->restApi = $restApi;
        parent::__construct($przelewy24);
    }

    /**
     * Set suffix.
     *
     * @param string $suffix
     */
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;
    }

    /**
     * Get status message.
     *
     * @param integer $status
     *
     * @return string
     */
    public function getStatusMessage($status)
    {
        $status = (int)$status;
        $return = $this->statusDefault;
        if (isset($this->status[$status])) {
            $return = $this->status[$status];
        }

        return $return;
    }

    private function extractDate($input)
    {
        $rx = '/^(?P<Y>\\d{4})(?P<m>\\d{2})(?P<d>\\d{2})/';
        if (preg_match($rx, $input, $m)) {
            return $m['Y'] . '-' . $m['m'] . '-' . $m['d'];
        } else {
            return '';
        }
    }

    /**
     * Check if refund is possible and returns data to refund.
     *
     * In the return:
     * 'amount' => Amount that is possible to return.
     *
     * @param int $orderId
     *
     * @return array
     *
     * @throws PrestaShopDatabaseException
     */
    public function checkIfRefundIsPossibleAndReturnDataToRefund($orderId)
    {
        $return = array();
        $dataFromDB = $this->getRefundDataFromDB($orderId);
        if ($dataFromDB) {
            $order = $this->restApi->transactionBySessionId($dataFromDB['sessionId']);
            $refunds = $this->restApi->refundByOrderId($dataFromDB['p24OrderId']);
            if (isset($order['data'])) {
                $return = array(
                    'sessionId' => (string)$order['data']['sessionId'],
                    'p24OrderId' => (int)$order['data']['orderId'],
                    'originalAmount' => (int)$order['data']['amount'],
                    'amount' => null, /* Reserve field position. */
                    'refunded' => 0,
                    'refunds' => [],

                );
                if (isset($refunds['data'])) {
                    foreach ($refunds['data']['refunds'] as $refund) {
                        $amountRefunded = (int)$refund['amount'];
                        $return['refunded'] += $amountRefunded;
                        $return['refunds'][] = array(
                            'amount_refunded' => $amountRefunded,
                            'created' => $this->extractDate($refund['date']),
                            'status' => $this->status[$refund['status']],
                        );
                    }
                }
                $return['amount'] = $return['originalAmount'] - $return['refunded'];
            }
        }

        return $return;
    }

    /**
     * Gets refund data from database.
     *
     * @param $orderId
     *
     * @return array
     *
     * @throws PrestaShopDatabaseException
     */
    public function getRefundDataFromDB($orderId)
    {
        $return = array();

        $orderId = (int)$orderId;
        $przelewy24Order = new Przelewy24Order();
        $result = $przelewy24Order->getByPshopOrderId($orderId);
        if ($result) {
            $return = array(
                'sessionId' => $result->p24_session_id,
                'p24OrderId' => $result->p24_order_id,
            );
        }

        return $return;
    }

    /**
     * Requests refund by Przelewy24.
     *
     * @param string $sessionId
     * @param int $p24OrderId
     * @param int $amountToRefund
     *
     * @return false|stdClass
     */
    public function refundProcess($sessionId, $p24OrderId, $amountToRefund)
    {
        $sessionId = (string)$sessionId;
        $p24OrderId = (int)$p24OrderId;
        $amountToRefund = (int)$amountToRefund;

        $response = $this->restApi->transactionRefund($p24OrderId, $sessionId, $amountToRefund);
        if (isset($response['data'][0])) {
            return $response['data'][0]['amount'] === $amountToRefund;
        }

        return false;
    }
}
