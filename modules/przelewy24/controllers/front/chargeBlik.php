<?php
/**
 * Class przelewy24chargeBlikModuleFrontController
 *
 * @author Przelewy24
 * @copyright Przelewy24
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 *
 */

/**
 * Class Przelewy24chargeBlikModuleFrontController
 */
class Przelewy24chargeBlikModuleFrontController extends ModuleFrontController
{
    /**
     * Init contant and dispatch actions.
     *
     * @throws Exception
     */
    public function initContent()
    {
        parent::initContent();

        $actionName = Tools::getValue('action');
        switch ($actionName) {
            case 'executePaymentByBlikCode':
                $this->executePaymentByBlikCodeAction();
                break;
            case 'trnRegister':
                $this->trnRegisterAction();
                break;
            default:
                $this->ajaxDie();
        }
    }

    /**
     * Action to execute payment by BLIK code.
     *
     * @return string
     */
    private function executePaymentByBlikCodeAction()
    {
        $data = array(
            'success' => false,
            'order_id' => null,
        );

        $currencySuffix = Tools::getValue('currencySuffix');
        $token = Tools::getValue('token');
        $blikCode = Tools::getValue('blikCode');

        if ($token && $blikCode) {
            $restBlik = Przelewy24RestBlikFactory::buildForSuffix($currencySuffix);
            $response = $restBlik->executePaymentByBlikCode($token, $blikCode);

            if (isset($response['data']['orderId']) && $response['data']['orderId']) {
                $this->context->cookie->id_cart = null;
                $data['success'] = true;
                $data['order_id'] = $response['data']['orderId'];
            }
        }

        $this->ajaxDie(json_encode($data));
    }

    /**
     * First steep to pay by BLIK.
     *
     * Prepare transaction on remote server.
     *
     * @throws Exception
     */
    private function trnRegisterAction()
    {
        $success = false;
        $token = null;
        $currencySuffix = null;

        $cart = $this->tryGetCartFromId();
        if (!$cart) {
            $cart = Context::getContext()->cart;
        }

        if ($cart && $cart->id) {
            $przelewy24 = new Przelewy24();
            $paymentData = new Przelewy24PaymentData($cart);
            $currency = $paymentData->getCurrency();
            $currencySuffix = ('PLN' === $currency->iso_code) ? '' : '_' . $currency->iso_code;
            $customer = new Customer((int)($cart->id_customer));

            if (!$paymentData->orderExists()) {
                if ('0' !== Configuration::get('P24_VERIFYORDER' . $currencySuffix)) {
                    /* This amount is without extracharge. */
                    $prestaAmount = $paymentData->getTotalAmountWithoutExtraCharge();
                    $prestaAmount = $paymentData->formatAmount($prestaAmount);
                    $orderBeginningState = Configuration::get('P24_ORDER_STATE_1');
                    $przelewy24->validateOrder(
                        $cart->id,
                        (int)$orderBeginningState,
                        (float)($prestaAmount / 100),
                        'Przelewy24',
                        null,
                        array(),
                        null,
                        false,
                        $customer->secure_key
                    );
                }
            }

            $token = $this->registerBlikTransaction($paymentData);
            if ($token) {
                $success = true;
            }
        }

        $data = array(
            'success' => $success,
            'token' => $token,
            'currencySuffix' => $currencySuffix,
        );

        $this->ajaxDie(json_encode($data));
    }

    /**
     * Register blik transaction.
     *
     * @return string
     */
    private function registerBlikTransaction(Przelewy24PaymentData $paymentData)
    {
        $cart = $paymentData->getCart();
        $currency = $paymentData->getCurrency();
        $suffix = ('PLN' === $currency->iso_code) ? '' : '_' . $currency->iso_code;
        $restApi = Przelewy24RestTransactionFactory::buildForSuffix($suffix);

        $amountFloat = $paymentData->getTotalAmountWithExtraCharge();
        $amount = $paymentData->formatAmount($amountFloat);

        $addressHelper = new Przelewy24AddressHelper($cart);
        $address = new Address((int)$addressHelper->getBillingAddress()['id_address']);

        $customer = new Customer((int)($cart->id_customer));

        $customerName = $customer->firstname . ' ' . $customer->lastname;

        if ($paymentData->orderExists()) {
            /* There may be few orders for one payment. Choose first. */
            $description = $this->module->l('Order') . ' ' . $paymentData->getFirstOrderId();
        } else {
            $description = $this->module->l('Cart') . ': ' . $cart->id;
        }

        $successUrl = Context::getContext()->link->getModuleLink(
            'przelewy24',
            'paymentSuccess',
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
        $payload->sessionId = $cart->id . '|' . md5(time());
        $payload->amount = (int)$amount;
        $payload->currency = $currency->iso_code;
        $payload->description = (string)$description;
        $payload->email = (string)$customer->email;
        $payload->client = $customerName;
        $payload->address = $address->address1 . " " . $address->address2;
        $payload->zip = (string)$address->postcode;
        $payload->city = (string)$address->city;
        $payload->country = Country::getIsoById((int)($address->id_country));
        $payload->language = $this->context->language->iso_code;
        $payload->urlReturn = $successUrl;
        $payload->urlStatus = $statusUrl;
        $payload->shipping = 0;

        $token = $restApi->registerRawToken($payload);

        return $token;
    }

    /**
     * Get cart based on id in post.
     *
     * @return Cart|null
     */
    private function tryGetCartFromId()
    {
        if (!Tools::getValue('cartId')) {
            return null;
        }

        $cartId = (int) Tools::getValue('cartId');
        $cart = new Cart($cartId);
        if (!$cart->id) {
            return null;
        }

        $customer = $this->context->customer;
        if (!Przelewy24Tools::checkCartForCustomer($customer, $cart)) {
            return null;
        }

        return $cart;
    }
}
