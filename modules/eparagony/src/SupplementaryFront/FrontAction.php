<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony\SupplementaryFront;

use Spark\EParagony\CartPreferenceManager;
use Spark\EParagony\TelephoneTool;
use Context;
use PrestaShop\PrestaShop\Adapter\Entity\Address;

/**
 * This class works as advanced factory.
 */
class FrontAction
{
    const ACTION_PAYMENT_ADDRESS = 'payment_address';
    const ACTION_SET_PHONE = 'set_phone';

    private $cartManager;

    public function __construct(
        CartPreferenceManager $cartManager
    ) {
        $this->cartManager = $cartManager;
    }

    public function runAction($action, Context $context, $fullPayload)
    {
        $code = 200;
        $ret = [];

        switch ($action) {
            case self::ACTION_PAYMENT_ADDRESS:
                list($code, $ret) = $this->showAddress($context);
                break;
            case self::ACTION_SET_PHONE:
                list($code, $ret) = $this->setPhoneAction($context, $fullPayload);
                break;
        }

        return [$code, $ret];
    }

    private function showAddress(Context $context)
    {
        if (!$context->cart) {
            return [404, ['status' => 'There is no cart in this context.']];
        }
        $idAddress = $context->cart->id_address_invoice;
        if (!$idAddress) {
            return [404, ['status' => 'There is no address in this context.']];
        }
        $address = new Address($idAddress);

        $ret = [
            'country' => $address->country,
            'alias' => $address->alias,
            'company' => $address->company,
            'lastname' => $address->lastname,
            'firstname' => $address->firstname,
            'address1' => $address->address1,
            'address2' => $address->address2,
            'postcode' => $address->postcode,
            'city' => $address->city,
            'other' => $address->other,
            'phone_preferred' => $address->phone_mobile ?: $address->phone,
            'vat_number' => $address->vat_number,
        ];

        return [200, $ret];
    }

    private function setPhoneAction(Context $context, $fullPayload)
    {
        $cartId = $context->cart->id;
        if (!$cartId) {
            return [404, 'There is no cart in this context.'];
        }
        $phone = $fullPayload['telephone'] ?? null;
        $phone = TelephoneTool::canonizeToPolish($phone);
        if ($phone) {
            $this->cartManager->setPhone($cartId, $phone);
            return [202, ['status' => 'Phone accepted to update.']];
        } else {
            return [400, ['status' => 'Invalid phone.']];
        }
    }
}
