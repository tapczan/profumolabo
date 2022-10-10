<?php
/**
 * @author Przelewy24
 * @copyright Przelewy24
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 */

class Przelewy24TransactionSupport
{
    /**
     * Register transaction.
     *
     * @param Przelewy24PaymentData $paymentData
     * @param string $description
     * @param string $languageIsoCode
     * @param int|null $method
     * @return string|null
     */
    public function registerTransaction(Przelewy24PaymentData $paymentData, $description, $languageIsoCode, $method = 0)
    {
        $currency = $paymentData->getCurrency();
        $suffix = ('PLN' === $currency->iso_code) ? '' : '_' . $currency->iso_code;
        $restApi = Przelewy24RestTransactionFactory::buildForSuffix($suffix);

        $payload = $this->getPayload($paymentData, $description, $languageIsoCode, $method);

        return $restApi->registerRawToken($payload);
    }

    /**
     * Get plain payload to register transaction.
     *
     * @param Przelewy24PaymentData $paymentData
     * @param string $description
     * @param string $languageIsoCode
     * @param int|null mixed $method
     * @return Przelewy24PayloadForRestTransaction
     */
    public function getPayload(Przelewy24PaymentData $paymentData, $description, $languageIsoCode, $method = 0)
    {
        $cart = $paymentData->getCart();
        $currency = $paymentData->getCurrency();
        $suffix = ('PLN' === $currency->iso_code) ? '' : '_' . $currency->iso_code;

        $amountFloat = $paymentData->getTotalAmountWithExtraCharge();
        $amount = $paymentData->formatAmount($amountFloat);

        $addressHelper = new Przelewy24AddressHelper($cart);
        $address = new Address((int)$addressHelper->getBillingAddress()['id_address']);

        $customer = new Customer((int)($cart->id_customer));

        $customerName = $customer->firstname . ' ' . $customer->lastname;

        $successUrl = Context::getContext()->link->getModuleLink(
            'przelewy24',
            'paymentSuccessful',
            array(),
            '1' === (string)Configuration::get('PS_SSL_ENABLED')
        );
        $statusUrl = Context::getContext()->link->getModuleLink(
            'przelewy24',
            'paymentStatus',
            array('status' => 'REST'),
            '1' === (string)Configuration::get('PS_SSL_ENABLED')
        );

        $payload = new Przelewy24PayloadForRestTransaction();
        $payload->merchantId = (int)Configuration::get('P24_MERCHANT_ID' . $suffix);
        $payload->posId = (int)Configuration::get('P24_SHOP_ID' . $suffix);
        $payload->sessionId = $cart->id . '|' . hash('sha224', rand());
        $payload->amount = (int)$amount;
        $payload->currency = $currency->iso_code;
        $payload->description = (string)$description;
        $payload->email = (string)$customer->email;
        $payload->client = $customerName;
        $payload->address = $address->address1 . " " . $address->address2;
        $payload->zip = (string)$address->postcode;
        $payload->city = (string)$address->city;
        $payload->country = Country::getIsoById((int)($address->id_country));
        $payload->language = $languageIsoCode;
        $payload->method = $method ? (int)$method : null;
        $payload->urlReturn = $successUrl;
        $payload->urlStatus = $statusUrl;
        $payload->shipping = 0;

        return $payload;
    }
}
