<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony;

use PrestaShop\PrestaShop\Adapter\Entity\Address;
use PrestaShop\PrestaShop\Adapter\Entity\Country;

class AddressChecker
{
    /* This is custom */
    public function shouldAskForPhone(Address $address) : bool
    {
        if ($address->vat_number) {
            /* It looks someone wants an invoice. No need to ask for additional data. */
            return false;
        }
        if ($address->phone || $address->phone_mobile) {
            /* There is a phone number, there is no need to ask. */
            return false;
        }

        $country = new Country($address->id_country);
        if ($country->call_prefix != 48) {
            return false;
        }

        return true;
    }
}
