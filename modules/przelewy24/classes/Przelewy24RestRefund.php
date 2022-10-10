<?php
/**
 * @author Przelewy24
 * @copyright Przelewy24
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 */

/**
 * Class Przelewy24RestRefund
 */
class Przelewy24RestRefund extends Przelewy24RestAbstract
{
    public function refundByOrderId($orderId)
    {
        $orderId = urlencode($orderId);
        $path = '/refund/by/orderId/' . $orderId;
        $ret = $this->call($path, null, 'GET');

        return $ret;
    }

    public function transactionBySessionId($sessionId)
    {
        $sessionId = urlencode($sessionId);
        $path = '/transaction/by/sessionId/' . $sessionId;
        $ret = $this->call($path, null, 'GET');

        return $ret;
    }

    public function transactionRefund($p24OrderId, $sessionId, $amount)
    {
        $now = time();
        $xId = $sessionId . '_' . $now;
        /* The refunds is an array of arrays. */
        $payload = [
            'requestId' => $xId,
            'refunds' => [
                [
                    'orderId' => (int)$p24OrderId,
                    'sessionId' => (string)$sessionId,
                    'amount' => (int)$amount,
                ]
            ],
            'refundsUuid' => $xId,
        ];
        $path = '/transaction/refund';
        $ret = $this->call($path, $payload, 'POST');

        return $ret;
    }
}
