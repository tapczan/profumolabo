<?php
/**
 * @author Check AUTHORS file.
 * @copyright TBD
 * @license TBD
 */

namespace ASoftwareHouse\EParagony;

use PrestaShop\PrestaShop\Adapter\Entity\Address;
use PrestaShop\PrestaShop\Adapter\Entity\Country;
use PrestaShop\PrestaShop\Adapter\Entity\Currency;
use PrestaShop\PrestaShop\Adapter\Entity\Order;
use PrestaShop\PrestaShop\Adapter\Entity\OrderState;

class OrderChecker
{
    /* We check if order state is paid. */
    public function isDocumentPossibleForState(OrderState $orderState) : bool
    {
        /* This is an internal PrestaShop flag. It is different from invoice in this plugin. */
        return (bool)$orderState->invoice;
    }

    /* This is custom */
    public function isApplicableForOrder(Order $order) : bool
    {
        /* This is an internal PrestaShop flag. It is different from invoice in this plugin. */
        $addressId = $order->id_address_invoice;

        $address = new Address($addressId);
        if (!$address->id) {
            /* The order data is incomplete. Fallback to false. */
            return false;
        }
        if ($address->vat_number) {
            /* It looks someone wants an invoice. */
            return false;
        }

        $currency = new Currency($order->id_currency);
        if ($currency->iso_code !== 'PLN') {
            /* The currency is not supported. */
            return false;
        }

        return true;
    }
}
