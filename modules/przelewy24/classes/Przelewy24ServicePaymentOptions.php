<?php
/**
 * @author Przelewy24
 * @copyright Przelewy24
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 *
 */

use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

/**
 * Class Przelewy24ServicePaymentOptions
 */
class Przelewy24ServicePaymentOptions extends Przelewy24Service
{
    const BASE_URL_LOGO_P24 = 'https://secure.przelewy24.pl/template/201312/bank/';

    /**
     * Rendered html with information about payment amounts.
     *
     * @var string|null
     */
    private $additionalInformation;

    /**
     * hookPaymentOptions implementation.
     *
     * This code display things to consumer.
     *
     * @param array $params
     * @param string $text
     * @param Przelewy24CachedPaymentList|null $cachedList
     *
     * @return array
     * @throws Exception
     */
    public function execute($params, $text, $cachedList = null)
    {
        $cart = Context::getContext()->cart;
        $currency = new Currency($cart->id_currency);
        $suffix = Przelewy24Helper::getSuffix($currency->iso_code);
        if (!$this->getPrzelewy24()->active || (int)Configuration::get('P24_CONFIGURATION_VALID' . $suffix) < 1) {
            return array();
        }

        $templateVars = $this->getPrzelewy24()->getTemplateVars();
        $amountTotal = $templateVars['total'];

        $templateVars['extracharge'] = $this->getExtracharge($amountTotal, $suffix);
        $templateVars['logo_url'] = $this->getPrzelewy24()->getPathUri() . 'views/img/logo.png';
        if ($templateVars['extracharge'] > 0) {
            $templateVars['checkTotal'] = Tools::displayPrice(($templateVars['extracharge'] + $templateVars['total']));
            $templateVars['extracharge_formatted'] = Tools::displayPrice($templateVars['extracharge']);
        }
        $templateVars['enable_intro'] = !(int)(Configuration::get('P24_INTRO_DISABLED' . $suffix));
        $templateVars['enable_now'] = (bool)(Configuration::get('P24_NOW_ENABLED' . $suffix));
        $templateVars['promote_now'] = (bool)(Configuration::get('P24_NOW_PROMOTE' . $suffix));
        $this->getPrzelewy24()->getSmarty()->assign(
            $templateVars
        );
        $this->additionalInformation = $this->getPrzelewy24()->fetch(
            'module:przelewy24/views/templates/front/payment_option.tpl'
        );
        $newOptions = array();
        $newOption = new PaymentOption();
        $newOption->setCallToActionText($text)
            ->setLogo($this->getPrzelewy24()->getPathUri() . 'views/img/logo_mini.png')
            ->setAction(
                $this->getPrzelewy24()->getContext()->link->getModuleLink(
                    $this->getPrzelewy24()->name,
                    'paymentConfirmation',
                    array(),
                    true
                )
            )
            ->setAdditionalInformation($this->additionalInformation);
        $newOptions[] = $newOption;
        $newOptions = array_merge($newOptions, $this->getPromotedPayments($params, $cachedList));

        return $newOptions;
    }

    /**
     * Legacy function to get extracharge.
     *
     * @param float $amount
     * @param string $suffix
     * @deprecated Newer code should use static version.
     *
     * @return float|int
     */
    public function getExtracharge($amount, $suffix = '')
    {
        return self::getExtrachargeStatic($amount, $suffix);
    }

    /**
     * Get extracharge.
     *
     * @param float $amount
     * @param string $suffix
     *
     * @return float|int
     */
    public static function getExtrachargeStatic($amount, $suffix = '')
    {
        $extracharge = 0;
        $p24ExtraChangeEnabled = (int)Configuration::get('P24_EXTRA_CHARGE_ENABLED' . $suffix);
        $p24ExtraChangePercent = (float)(str_replace(
            ',',
            '.',
            Configuration::get('P24_EXTRA_CHARGE_PERCENT' . $suffix)
        ));
        $p24ExtraChangAmount = (float)(str_replace(',', '.', Configuration::get('P24_EXTRA_CHARGE_AMOUNT' . $suffix)));

        if (1 === $p24ExtraChangeEnabled) {
            $extracharge = $p24ExtraChangAmount;
            $amountPercent = round(($amount * ((100 + $p24ExtraChangePercent) / 100)) - $amount, 2);

            if ($amountPercent > $p24ExtraChangAmount) {
                $extracharge = round($amountPercent, 2);
            }
        }

        return $extracharge;
    }

    /**
     * Set extracharge.
     *
     * @param Order $order
     */
    public function setExtracharge($order)
    {
        if (!$order instanceof Order) {
            return;
        }

        if ($this->hasExtrachargeOrder($order->id)) {
            return;
        }

        $cart = new Cart($order->id_cart);

        $paymentData = new Przelewy24PaymentData($cart);

        if ($paymentData->hasExtracharge()) {
            return;
        }

        $currency = new Currency($cart->id_currency);
        $suffix = Przelewy24Helper::getSuffix($currency->iso_code);
        $extracharge = number_format($this->getExtracharge($order->total_paid, $suffix), 2);

        $order->extra_charge_amount = round($extracharge * 100);
        $order->total_paid += $extracharge;
        $order->total_paid_tax_excl += $extracharge;
        $order->total_paid_tax_incl += $extracharge;
        $order->save();

        $extracharge = Przelewy24Extracharge::prepareByOrderId($order->id);
        $extracharge->extra_charge_amount = $order->extra_charge_amount;
        $extracharge->save();
    }

    /**
     * @param int $orderId
     */
    public function setExtrachargeByOrderId($orderId)
    {
        $order = new Order($orderId);

        $this->setExtracharge($order);
    }

    /**
     * Get extracharge order id.
     *
     * @param int $orderId
     *
     * @return int
     */
    public function getExtrachargeOrder($orderId)
    {
        $extracharge = Przelewy24Extracharge::findOneByOrderId($orderId);
        if (!Validate::isLoadedObject($extracharge)) {
            return 0;
        }

        return $extracharge->extra_charge_amount / 100;
    }

    /**
     * Return if the order has an extracharege.
     *
     * @param int $orderId
     *
     * @return bool
     */
    public function hasExtrachargeOrder($orderId)
    {
        $extracharge = $this->getExtrachargeOrder($orderId);

        return $extracharge > 0;
    }

    /**
     * Get promoted payments.
     *
     * @param array $params
     * @param Przelewy24CachedPaymentList|null $cachedList
     *
     * @return array
     * @throws Exception
     */
    public function getPromotedPayments($params, $cachedList = null)
    {
        $currency = new Currency($params['cart']->id_currency);
        $suffix = Przelewy24Helper::getSuffix($currency->iso_code);

        if (!Configuration::get('P24_PAYMENT_METHOD_CHECKOUT_LIST' . $suffix)) {
            /* Inactive. */
            return array();
        }

        $results = array();
        $restApi = Przelewy24RestBigFactory::buildForSuffix($suffix);
        $restApi->setPaymentCache($cachedList);
        $language = Context::getContext()->language->iso_code;
        $rawList = $cachedList->getList($language);

        $promotePaymethodList = $restApi->getPromotedPaymentListForConsumer(
            $currency->iso_code
        );

        $promotePaymethodList = $this->subGetPromotedP24Now($currency, $promotePaymethodList, $rawList);
        $promotePaymethodList = $this->subGetPromotedPOneyRaty($promotePaymethodList);

        if (!empty($promotePaymethodList['p24_paymethod_list_promote'])) {
            foreach ($promotePaymethodList['p24_paymethod_list_promote'] as $key => $item) {
                $results[] = $this->getPaymentOption($item, $key, $rawList);
            }
        }

        return $results;
    }

    /**
     * Support function for P24Now in promoted payments.
     *
     * @param Currency $currency
     * @param array $promotePaymethodList
     * @param array $rawList
     * @return array
     */
    private function subGetPromotedP24Now(Currency $currency, array $promotePaymethodList, array $rawList): array
    {
        if (Configuration::get('P24_NOW_ENABLED') && (string)$currency->iso_code == "PLN"
            && (float)$this->getPrzelewy24()->getTemplateVars()['total'] < (float)10000) {
            if (!array_key_exists(266, $promotePaymethodList["p24_paymethod_list_promote"])) {
                if (array_key_exists(266, $rawList)) {
                    $description = Configuration::get("P24_PAYMENT_DESCRIPTION_266") ?: 'P24now';
                    $promotePaymethodList["p24_paymethod_list_promote"][266] = $description;
                }
            }
        } else {
            unset($promotePaymethodList["p24_paymethod_list_promote"][266]);
        }
        return $promotePaymethodList;
    }

    /**
     * Support function for OneyRaty in promoted payments.
     *
     * @param array $promotePaymethodList
     * @return array
     */
    private function subGetPromotedPOneyRaty(array $promotePaymethodList): array
    {
        $total = round((float)$this->getPrzelewy24()->getTemplateVars()['total'], 2);
        if ($total < 150 || $total > 30000) {
            /* OneyRaty is not supported. */
            if (isset($promotePaymethodList['p24_paymethod_list_promote'][294])) {
                unset($promotePaymethodList['p24_paymethod_list_promote'][294]);
            }
        }

        return $promotePaymethodList;
    }

    /**
     * Get payment option
     *
     * @param string $title
     * @param int $methodId
     * @param array $rawList
     *
     * @return PaymentOption
     */
    private function getPaymentOption($title, $methodId, $rawList)
    {
        $logoUri = $rawList[$methodId]['imgUrl'];

        $newOption = new PaymentOption();
        $newOption->setCallToActionText($title)
            ->setLogo($logoUri)
            ->setAction(
                $this->getPrzelewy24()->getContext()->link->getModuleLink(
                    $this->getPrzelewy24()->name,
                    'paymentConfirmation',
                    ['payment_method' => $methodId],
                    true
                )
            )
            ->setModuleName($this->getPrzelewy24()->name . '-method-' . $methodId)
            ->setAdditionalInformation($this->additionalInformation);

        return $newOption;
    }
}
