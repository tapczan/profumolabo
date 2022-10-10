<?php
/**
 * Class Przelewy24OrderDescriptionHelper
 *
 * @author    Przelewy24
 * @copyright Przelewy24
 * @license   https://www.gnu.org/licenses/lgpl-3.0.en.html
 */

/**
 * Class Przelewy24OrderDescriptionHelper
 */
class Przelewy24OrderDescriptionHelper
{
    /**
     * @param string                $orderTranslated
     * @param string                $suffix
     * @param Przelewy24PaymentData $paymentData
     * @param string                $fallbackName fallback when order is made after payment
     *
     * @return string
     */
    public static function buildDescription($orderTranslated, $suffix, $paymentData, $fallbackName = '')
    {
        $orderReference = $paymentData->getOrderReference();
        $getFirstOrderId = $paymentData->getFirstOrderId();
        $orderNumberSuffix = ('1' === Configuration::get('P24_ORDER_TITLE_ID'.$suffix))
            ? $paymentData->getOrderReference()
            : $paymentData->getFirstOrderId();

        if (empty($orderReference) && empty($getFirstOrderId)) {
            return $fallbackName;
        }

        return $orderTranslated.' '.$orderNumberSuffix;
    }
}
