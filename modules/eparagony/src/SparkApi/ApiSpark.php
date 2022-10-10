<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony\SparkApi;

use Spark\EParagony\Constants;
use Spark\EParagony\MoneyTool;
use Spark\EParagony\TelephoneTool;
use LogicException;
use PrestaShop\PrestaShop\Adapter\Entity\Address;
use PrestaShop\PrestaShop\Adapter\Entity\Currency;
use PrestaShop\PrestaShop\Adapter\Entity\Order;
use PrestaShop\PrestaShop\Adapter\Entity\Product;

class ApiSpark
{
    private $posId;
    private $storeNip;
    private $token;
    private $url;
    private $logRequests;
    private $logDirectory;
    private $logFileNames;
    private $returnPolicySpark;
    private $fiscalizationUrl;
    private $taxHelper;
    private $orderId;

    public function __construct(
        string $posId,
        string $storeNip,
        string $token,
        string $url,
        bool $logRequests,
        string $logDirectory,
        string $returnPolicySpark,
        string $fiscalizationUrl,
        TaxHelperForSpark $taxHelper
    ) {
        $this->posId = $posId;
        $this->storeNip = $storeNip;
        $this->token = $token;
        $this->url = $url;
        $this->logRequests = $logRequests;
        $this->logDirectory = $logDirectory;
        $this->returnPolicySpark = $returnPolicySpark;
        $this->fiscalizationUrl = $fiscalizationUrl;
        $this->taxHelper = $taxHelper;

        $this->logFileNames = array();
    }

    private function getRawProducts(Order $order)
    {
        $prestaProducts = $order->getProducts();
        $rawProducts = [];
        foreach ($prestaProducts as $product) {
            /* The tax_rate field is not reliable. */
            $taxRate = round(($product['unit_price_tax_incl'] / $product['unit_price_tax_excl'] - 1) * 100);

            $product['our_tax_rate'] = (int)$taxRate;

            /* We care only for totals. The rebate is per line, not per item. */

            $quantity = $product['product_quantity'];
            $totalPrice = round($product['total_price_tax_incl'], 2);
            $totalPriceNett = round($product['total_price_tax_excl'], 2);

            $product['our_total_price'] = $totalPrice;
            $product['our_total_price_nett'] = $totalPriceNett;

            /* We assume the gross price is more correct. */

            $orgPrice = round($product['original_product_price'] * (1 + $taxRate/100), 2);
            $orgPriceNett = round($orgPrice / (1 + $taxRate / 100), 2);
            $totalOrgPrice = round($orgPrice * $quantity, 2);
            $totalOrgPriceNett = round($orgPriceNett * $quantity, 2);

            $product['our_org_price_nett'] = $orgPriceNett;
            $product['our_org_price'] = $orgPrice;
            $product['our_org_total_price'] = $totalOrgPrice;
            $product['our_org_total_price_nett'] = $totalOrgPriceNett;

            $rawProducts[] = $product;
        }

        return $rawProducts;
    }

    private function getShipping(Order $order)
    {
        $shippingNett = round($order->total_shipping_tax_excl, 2);
        $taxRate = $order->carrier_tax_rate;
        $shipping = round($shippingNett * (1 + $taxRate/100), 2);

        return [
            'shipping_nett' => $shippingNett,
            'shipping' => $shipping,
            'tax_rate' => $taxRate,
        ];
    }

    public function getRecipeCommand(Order $order, $session)
    {
        if (!$this->checkIfPln($order)) {
            throw new LogicException('Only PLN is supported. It should be checked earlier.');
        }

        /* Useful for debug. */
        $this->orderId = $order->id;

        $rawProducts = $this->getRawProducts($order);
        $rawShipping = $this->getShipping($order);
        $response = $this->registerTransaction($order, $rawProducts, $rawShipping, $session);
        if (!$response) {
            throw new ApiSparkException('Cannot register transaction.', ApiSparkException::CODE_COMMAND_FAILED);
        }
        list($receipeToken, $receipeUrl) = $response;
        $command = $this->finalRequest($receipeToken, $order, $rawProducts, $rawShipping);
        if (!$command) {
            throw new ApiSparkException('Cannot get commoand.', ApiSparkException::CODE_COMMAND_FAILED);
        }

        return [$command, $receipeUrl, $receipeToken];
    }

    private function checkIfPln(Order $order)
    {
        $currency = new Currency($order->id_currency);

        return $currency->iso_code === 'PLN';
    }

    private function nullFilter($object): array
    {
        $array = (array)$object;
        $array = array_filter(
            $array,
            function ($x) {
                return isset($x);
            }
        );
        return $array;
    }

    private function getProducts($rawProducts)
    {
        $id = 0;
        $products = [];
        foreach ($rawProducts as $rawProduct) {
            /* Count from 1. */
            $id++;
            $product = new SparkProduct();
            $product->id = (string)$id; /* Yes, string. */
            $product->name = (string)$rawProduct['product_name'];
            $product->quantity = (string)$rawProduct['product_quantity']; /* Yes, string. */
            $product->unitPrice = MoneyTool::roundToCentile($rawProduct['our_org_price']);
            $product->value = MoneyTool::roundToCentile($rawProduct['our_org_total_price']);
            $product->taxRate = $this->taxHelper->decodeToLetter($rawProduct['our_tax_rate']);
            $product->taxRateValue = (string)MoneyTool::roundTax($rawProduct['our_tax_rate']); /* Yes, string. */
            $product->SKU = (string)$rawProduct['reference'] ?: 'x-' . $rawProduct['id_product'];
            $product->EAN = ((string)($rawProduct['isbn'] ?: $rawProduct['ean13'])) ?: null;
            #TODO Uncomment when ready.
            #$product->databaseId = (int)$rawProduct['id_product'];

            $products[] = $this->nullFilter($product);
        }

        return $products;
    }

    private function extractPhone(Order $order) : ?string
    {
        $address = new Address($order->id_address_invoice);
        /* The mobile phone may be the main phone too. */
        $phone = $address->phone_mobile ?: $address->phone;

        return TelephoneTool::canonizeToPolish($phone);
    }

    private function computeTotalPrice($rawProducts, $rawShipping)
    {
        $total = 0.0;
        foreach ($rawProducts as $product) {
            $total += $product['our_total_price'];
            $total = round($total, 2);
        }
        if ($rawShipping['shipping']) {
            $total += $rawShipping['shipping'];
            $total = round($total, 2);
        }

        return $total;
    }

    private function registerTransaction(Order $order, $rawProducts, $rawShipping, $session)
    {
        $rawPayload = [
            'posId' => (string)$this->posId,
            'sessionId' => $session,
            'phoneNumber' => $this->extractPhone($order),
            'paymentAmount' => MoneyTool::roundToCentile($this->computeTotalPrice($rawProducts, $rawShipping)),
            'shippingAmount' => MoneyTool::roundToCentile($rawShipping['shipping']),
            'currency' => 'PLN',
            'statusUrl' => 'http://example.com', /* Unused but required. */
            'currentTimePOS' => date(DATE_ATOM),
            'products' => $this->getProducts($rawProducts),
            'ereceiptIntent' => true,
            'paymentIntent' => false,
        ];
        if (!$rawPayload['phoneNumber']) {
            unset($rawPayload['phoneNumber']);
        }
        if (!$rawPayload['shippingAmount']) {
            unset($rawPayload['shippingAmount']);
        }
        $payload = json_encode($rawPayload);
        $fullUrl = $this->url . '/transactions/register';
        $this->tryCreateLogRequest($fullUrl, $payload);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_URL => $fullUrl,
            CURLOPT_USERAGENT => Constants::getUserAgentWithVersion(),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->token,
            ],
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
        ]);
        $rawResponse = curl_exec($curl);
        $this->tryCreateLogRequest('---', $rawResponse);
        $response = json_decode($rawResponse, true);
        if (isset($response['token'])) {
            return [
                $response['token'],
                $response['ereceiptUrl'] ?? null,
            ];
        } else {
            return null;
        }
    }

    private function getSubtotalsByTaxRate($rawProducts, $rawShipping)
    {
        $subtotalsByTaxRate = [];
        foreach ($rawProducts as $rawProduct) {
            $taxLetter = $this->taxHelper->decodeToLetter($rawProduct['our_tax_rate']);
            $subtotalsByTaxRate[$taxLetter] = ($subtotalsByTaxRate[$taxLetter] ?? 0)
                + MoneyTool::roundToCentile($rawProduct['our_total_price']);
        }
        if ($rawShipping['shipping']) {
            $taxLetter = $this->taxHelper->decodeToLetter($rawShipping['tax_rate']);
            $subtotalsByTaxRate[$taxLetter] = ($subtotalsByTaxRate[$taxLetter] ?? 0)
                + MoneyTool::roundToCentile($rawShipping['shipping']);
        }

        return array_map([MoneyTool::class, 'displayMoneyWithDotFromCentiles'], $subtotalsByTaxRate);
    }

    private function getTaxValue($rawProducts, $rawShipping)
    {
        $taxValue = [];
        foreach ($rawProducts as $rawProduct) {
            $withTax = MoneyTool::roundToCentile($rawProduct['our_total_price']);
            $withoutTax = MoneyTool::roundToCentile($rawProduct['our_total_price_nett']);
            $tax = $withTax - $withoutTax;

            $taxLetter = $this->taxHelper->decodeToLetter($rawProduct['our_tax_rate']);
            $taxValue[$taxLetter] = ($taxValue[$taxLetter] ?? 0) + $tax;
        }
        if ($rawShipping['shipping']) {
            $withTax = MoneyTool::roundToCentile($rawShipping['shipping']);
            $withoutTax = MoneyTool::roundToCentile($rawShipping['shipping_nett']);
            $tax = $withTax - $withoutTax;

            $taxLetter = $this->taxHelper->decodeToLetter($rawShipping['tax_rate']);
            $taxValue[$taxLetter] = ($taxValue[$taxLetter] ?? 0) + $tax;
        }


        return array_map([MoneyTool::class, 'displayMoneyWithDotFromCentiles'], $taxValue);
    }

    private function getTaxTotalValue($rawProducts, $rawShipping)
    {
        $withTax = 0;
        $withoutTax = 0;
        foreach($rawProducts as $product) {
            $withTax += MoneyTool::roundToCentile($product['our_total_price']);
            $withoutTax += MoneyTool::roundToCentile($product['our_total_price_nett']);
        }
        if ($rawShipping) {
            $withTax += MoneyTool::roundToCentile($rawShipping['shipping']);
            $withoutTax += MoneyTool::roundToCentile($rawShipping['shipping_nett']);
        }

        return MoneyTool::displayMoneyWithDotFromCentiles($withTax - $withoutTax);
    }

    private function getTotalValue($rawProducts, $rawShipping, $discounts)
    {
        $withTax = 0;
        foreach($rawProducts as $product) {
            $withTax += MoneyTool::roundToCentile($product['our_total_price']);
        }
        $withTax += MoneyTool::roundToCentile($rawShipping['shipping']);
        $withTax -= MoneyTool::roundToCentile($discounts);

        return MoneyTool::displayMoneyWithDotFromCentiles($withTax);
    }

    private function getRecipeLines($rawProducts, $rawShipping, $totalDiscount)
    {
        $lines = [];
        /* Count from 1. */
        $id = 1;
        foreach ($rawProducts as $rawProduct) {
            $line = new SparkRecipeProductLine();
            $line->productOrServiceName = (string)$rawProduct['product_name'];
            $line->ID = $id;
            $line->SKU = (string)$rawProduct['reference'] ?: 'x-' . $rawProduct['id_product'];
            $line->quantity = (string)$rawProduct['product_quantity']; /* Yes, string. */
            $line->unitPrice = MoneyTool::displayMoneyWithDot($rawProduct['our_org_price']);
            $line->totalLineValue = MoneyTool::displayMoneyWithDot($rawProduct['our_org_total_price']);
            $line->taxRate =  $this->taxHelper->decodeToLetter($rawProduct['our_tax_rate']);
            if ($rawProduct['our_org_total_price'] !== $rawProduct['our_total_price']) {
                /* Binary floats. */
                $value = round($rawProduct['our_org_total_price'] - $rawProduct['our_total_price'], 2);
                $reductionPercent = (float)$rawProduct['reduction_percent'];
                if ($reductionPercent) {
                    $label = 'Rabat ' . $reductionPercent . '%';
                } else {
                    $label = 'Rabat ' . MoneyTool::displayMoneyWithComa($value) . ' PLN';
                }
                $line->rebatesMarkups = [[
                    'type' => 'OBNIŻKA',
                    'value' => MoneyTool::displayMoneyWithDot(-$value), /* Negate value. */
                    'name' => $label,
                    'taxRate' => $line->taxRate, /* Just copy. */
                ]];
            }
            $lines[] = $this->nullFilter($line);
            $id++;
        }
        if ($rawShipping['shipping']) {
            $taxLetter = $this->taxHelper->decodeToLetter($rawShipping['tax_rate']);
            $line = new SparkRecipeProductLine();
            $line->productOrServiceName = 'Transport ' . $taxLetter;
            $line->ID = $id;
            $line->quantity = '1';
            $line->unitPrice = MoneyTool::displayMoneyWithDot($rawShipping['shipping']);
            $line->totalLineValue = MoneyTool::displayMoneyWithDot($rawShipping['shipping']);
            $line->taxRate = $taxLetter;
            $lines[] = $this->nullFilter($line);
        }

        if ($totalDiscount) {
            $line = new SparkRecipeRebateLine();
            $line->value = MoneyTool::displayMoneyWithDot(-$totalDiscount);
            $line->name = 'Zniżka';
            $lines[] = $line;
        }

        return $lines;
    }

    private function getFinalRequestData(
        Order $order,
        string $recipeToken,
        array $rawProducts,
        array $rawShipping
    ): array {
        $taxRates = $this->taxHelper->getTable();

        $nowAtom = date(DATE_ATOM);
        $grossSaleValue = $this->getTotalValue($rawProducts, $rawShipping, $order->total_discounts);
        $paid = MoneyTool::displayMoneyWithDot($order->total_paid_tax_incl);
        /* Floating point zero as string may be true in PHP. We need to cast. */
        $totalDiscounts = (float)$order->total_discounts;

        $payment = [
            'paymentName' => $order->payment ?: null,
            'paymentForm' => 'Przelew',
            'paidThisForm' => $paid,
        ];
        if (!$payment['paymentName']) {
            unset($payment['paymentName']);
        }

        $rawPayload = [
            'token' => $recipeToken,
            'printStatus' => false,
            'eReceipt' => [
                'metadata' => [
                    'orderId' => (string)$order->getUniqReference(),
                    'TIN' => (string)$this->storeNip,
                    'nextDocumentID' => '', /* Required but unused. */
                    'taxRates' => $taxRates,
                    'subtotalsByTaxRate' => $this->getSubtotalsByTaxRate($rawProducts, $rawShipping),
                    'taxValue' => $this->getTaxValue($rawProducts, $rawShipping),
                    'taxTotalValue' => $this->getTaxTotalValue($rawProducts, $rawShipping),
                    'grossSaleValue' => $grossSaleValue,
                    'description' => 'DO ZAPŁATY',
                    'endTime' => $nowAtom,
                    'extensions' => [
                        'globalReturnPolicy' => (string)$this->returnPolicySpark,
                    ],
                ],
                'lines' => $this->getRecipeLines($rawProducts, $rawShipping, $totalDiscounts),
                'payment' => [
                    'payments' => [ /* Yes, array with single child. */
                        $payment,
                    ],
                    'totalPaid' => $paid,
                ],
            ],
            'fiscalize' => true,
            'fiscalizationStatusUrl' => $this->fiscalizationUrl,
            'currentTimePOS' => $nowAtom,
        ];

        if (!$this->returnPolicySpark) {
            /* We drop whole parent. */
            unset($rawPayload['eReceipt']['metadata']['extensions']);
        }

        return $rawPayload;
    }

    private function finalRequest(string $recipeToken, Order $order, array $rawProducts, array $rawShipping)
    {
        $rawPayload = $this->getFinalRequestData($order, $recipeToken, $rawProducts, $rawShipping);

        $payload = json_encode($rawPayload);
        $fullUrl = $this->url . '/transactions/' . $recipeToken . '/receipt';
        $this->tryCreateLogRequest($fullUrl, $payload);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_URL => $fullUrl,
            CURLOPT_USERAGENT => Constants::getUserAgentWithVersion(),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->token,
            ],
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
        ]);
        $rawResponse = curl_exec($curl);
        $this->tryCreateLogRequest('---', $rawResponse);
        $response = json_decode($rawResponse, true);
        if (is_array($response)) {
            if (($response['status'] ?? 'error') === 'saved') {
                return $response['command'] ?? 'no command';
            }
        }

        return null;
    }

    private function tryCreateLogRequest($url, $payload)
    {
        if (!$this->logRequests) {
            return;
        }
        if (!array_key_exists($this->orderId, $this->logFileNames)) {
            $random = bin2hex(random_bytes(3));
            $path = $this->logDirectory . DIRECTORY_SEPARATOR;
            $this->logFileNames[$this->orderId] = $path . 'eparagony_spark_' . date('Y-m-d_H-i-s') . '_' . $random;
            $content = "OrderId: " . $this->orderId . "\n\n";
            file_put_contents($this->logFileNames[$this->orderId], $content, FILE_APPEND);
        }
        $content = "\n". $url . "\n" . $payload . "\n";
        file_put_contents($this->logFileNames[$this->orderId], $content, FILE_APPEND);
    }
}
