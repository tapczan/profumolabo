<?php
/**
 * @author Przelewy24
 * @copyright Przelewy24
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 */

/**
 * Class Przelewy24ReastCard
 */
class Przelewy24RestCard extends Przelewy24RestAbstract implements Przelewy24RestCardInterface
{
    /**
     * Charge with3ds.
     *
     * @param $token
     * @return array
     */
    public function chargeWith3ds($token)
    {
        $path = '/card/chargeWith3ds';
        $payload = array(
            'token' => $token,
        );
        $ret = $this->call($path, $payload, 'POST');

        return $ret;
    }

    /**
     * Card info.
     *
     * @param string $orderId
     * @return array
     */
    public function cardInfo($orderId)
    {
        $orderId = urlencode($orderId);
        $path = '/card/info/' . $orderId;
        $ret = $this->call($path, null, 'GET');

        return $ret;
    }
}
