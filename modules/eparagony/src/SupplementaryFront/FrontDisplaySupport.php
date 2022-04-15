<?php
/**
 * @author Check AUTHORS file.
 * @copyright TBD
 * @license TBD
 */

namespace ASoftwareHouse\EParagony\SupplementaryFront;

use ASoftwareHouse\EParagony\AddressChecker;
use ASoftwareHouse\EParagony\ConfigHelper;
use Context;
use PrestaShop\PrestaShop\Adapter\Entity\Address;

class FrontDisplaySupport
{
    private $checker;
    private $config;

    public function __construct(AddressChecker $checker, ConfigHelper $configHelper)
    {
        $this->checker = $checker;
        $this->config = $configHelper::getSavedConfig();
    }

    public function displayOnPayment(string $moduleName, Context $context)
    {
        if ($context->currency->iso_code !== 'PLN') {
            /* If ISO code is not PLN, there is no point in asking. */
            return [];
        }

        if (!$this->config->ask_for_phone) {
            /* Configured to not ask. */
            return [];
        }

        $addressId = $context->cart->id_address_invoice;
        $address = new Address($addressId);
        if (!$this->checker->shouldAskForPhone($address)) {
            return [];
        }

        $urlCheck = $context->link->getModuleLink(
            $moduleName,
            'ajaj',
            [
                'action' => FrontAction::ACTION_PAYMENT_ADDRESS
            ]
        );
        $urlSetPhone = $context->link->getModuleLink(
            $moduleName,
            'ajaj',
            [
                'action' => FrontAction::ACTION_SET_PHONE
            ]
        );
        return compact('urlCheck', 'urlSetPhone');
    }
}
