<?php
/**
 * Class przelewy24chargeCardModuleFrontController
 *
 * @author Przelewy24
 * @copyright Przelewy24
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 *
 */

/**
 * Class Przelewy24chargeCardModuleFrontController
 */
class Przelewy24chargeCardModuleFrontController extends ModuleFrontController
{
    /**
     * Init content.
     *
     * @throws Exception
     */
    public function initContent()
    {
        parent::initContent();

        $redirect = null;

        $toolsIdCart = (int)Tools::getValue('id_cart');
        $toolsP24CardCustomerId = (int)Tools::getValue('p24_card_customer_id');

        if (!empty($toolsIdCart) && !empty($toolsP24CardCustomerId)) {
            $cartId = (int)Tools::getValue('id_cart');
            /** @var $order \PrestaShop\PrestaShop\Adapter\Entity\Order */
            $cart = new Cart($cartId);
            $currency = new Currency($cart->id_currency);
            $suffix = Przelewy24Helper::getSuffix($currency->iso_code);
            $customer = new Customer((int)($cart->id_customer));

            $cardId = (int)Tools::getValue('p24_card_customer_id');

            $creditCards = Przelewy24Recurring::findByCustomerId($customer->id);

            if (is_array($creditCards) && !empty($creditCards)) {
                foreach ($creditCards as $creditCard) {
                    if (isset($creditCard->id) && $cardId === (int)$creditCard->id) {
                        $refId = $creditCard->reference_id;
                        $token = $this->getTokenForCardTransaction($refId, $toolsIdCart, $suffix);

                        if ($token) {
                            $redirect = $this->doCardTransactionForToken($token, $suffix);
                            break;
                        }
                    }
                }
            }
        }

        if ($redirect) {
            Tools::redirect($redirect);
        } else {
            Tools::redirect(
                $this->context->link->getModuleLink(
                    'przelewy24',
                    'paymentFailed',
                    array(),
                    '1' === (string)Configuration::get('PS_SSL_ENABLED')
                )
            );
        }
    }

    /**
     * Get token for card transaction.
     *
     * @param string $ref
     * @param int $cartId
     * @param string $suffix
     * @param Przelewy24RestTransactionInterface $transactionRest
     * @return string
     * @throws RuntimeException
     */
    private function getTokenForCardTransaction($ref, $cartId, $suffix)
    {
        $restTransaction = Przelewy24RestTransactionInterfaceFactory::buildForSuffix($suffix);
        $statusUrl = $this->context->link->getModuleLink(
            'przelewy24',
            'paymentStatus',
            array('id_cart' => $cartId, 'status' => 'REST_CARD', 'XDEBUG_SESSION_START' => 'pr17'),
            '1' === Configuration::get('PS_SSL_ENABLED')
        );

        $payload = new Przelewy24PayloadForRestTransaction();
        $payload->merchantId = (int)Tools::getValue('p24_merchant_id');
        $payload->posId = (int)Tools::getValue('p24_pos_id');
        $payload->sessionId = (string)Tools::getValue('p24_session_id');
        $payload->amount = (int)Tools::getValue('p24_amount');
        $payload->currency = (string)Tools::getValue('p24_currency');
        $payload->description = (string)Tools::getValue('p24_description');
        $payload->email = (string)Tools::getValue('p24_email');
        $payload->client = (string)Tools::getValue('p24_client');
        $payload->address = (string)Tools::getValue('p24_address');
        $payload->zip = (string)Tools::getValue('p24_zip');
        $payload->city = (string)Tools::getValue('p24_city');
        $payload->country = (string)Tools::getValue('p24_country');
        $payload->language = (string)Tools::getValue('p24_language');
        $payload->urlReturn = (string)Tools::getValue('p24_url_return');
        $payload->urlStatus = (string)filter_var($statusUrl, FILTER_SANITIZE_URL);
        $payload->regulationAccept = (bool)Tools::getValue('p24_regulation_accept');
        $payload->shipping = (int)Tools::getValue('p24_shipping');
        $payload->encoding = (string)Tools::getValue('p24_encoding');
        $payload->methodRefId = (string)$ref;
        $ret = $restTransaction->register($payload);
        if (isset($ret['data']['token'])) {
            $token = $ret['data']['token'];
        } else {
            $token = '';
        }
        return $token;
    }

    /**
     * Do card transaction for token.
     *
     * @param string $token
     * @param string $suffix
     * @return string|null
     * @throws RuntimeException
     */
    private function doCardTransactionForToken($token, $suffix)
    {
        $restCard = Przelewy24RestCardInterfaceFactory::buildForSuffix($suffix);
        $ret = $restCard->chargeWith3ds($token);
        if (isset($ret['data'])) {
            $data = $ret['data'];
        } else {
            $data = array();
        }
        if (isset($data['redirectUrl'])) {
            return $data['redirectUrl'];
        } else {
            return null;
        }
    }
}
