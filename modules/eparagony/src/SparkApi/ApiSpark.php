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
    private $printerType;
    private $posId;
    private $storeNip;
    private $token;
    private $url;
    private $logRequests;
    private $logDirectory;
    private $logFileName;
    private $returnPolicySpark;
    private $webhookUrl;
    private $taxHelper;

    public function __construct(
        string $printerType,
        string $posId,
        string $storeNip,
        string $token,
        string $url,
        bool $logRequests,
        string $logDirectory,
        string $returnPolicySpark,
        string $webhookUrl,
        TaxHelperForSpark $taxHelper
    ) {
        $this->printerType = $printerType;
        $this->posId = $posId;
        $this->storeNip = $storeNip;
        $this->token = $token;
        $this->url = $url;
        $this->logRequests = $logRequests;
        $this->logDirectory = $logDirectory;
        $this->returnPolicySpark = $returnPolicySpark;
        $this->webhookUrl = $webhookUrl;
        $this->taxHelper = $taxHelper;
    }

    private function getRawProducts(Order $order)
    {
        $prestaProducts = $order->getProducts();
        $rawProducts = [];
        foreach ($prestaProducts as $product) {
            /* The tax_rate field is not reliable. */
            $taxRate = round(($product['unit_price_tax_incl'] / $product['unit_price_tax_excl'] - 1) * 100);

            $unitPriceNett = round($product['unit_price_tax_excl'], 2);
            $quantity = $product['product_quantity'];
            /* Our gross is from the rounded net price. */
            $unitPrice = round($unitPriceNett * (1 + $taxRate/100), 2);
            $totalPrice = round($unitPrice * $quantity, 2);
            $totalPriceNett = round($unitPriceNett * $quantity, 2);

            $product['our_unit_price_nett'] = $unitPriceNett;
            $product['our_unit_price'] = $unitPrice;
            $product['our_total_price'] = $totalPrice;
            $product['our_total_price_nett'] = $totalPriceNett;

            $orgPriceNett = round($product['original_product_price'], 2);
            $orgPrice = round($orgPriceNett * (1 + $taxRate/100), 2);
            $totalOrgPrice = round($orgPrice * $quantity, 2);
            $totalOrgPriceNett = round($orgPriceNett * $quantity, 2);

            $product['our_org_price_nett'] = $orgPriceNett;
            $product['our_org_price'] = $orgPrice;
            $product['our_org_total_price'] = $totalOrgPrice;
            $product['our_org_total_price_nett'] = $totalOrgPriceNett;

            $product['our_tax_rate'] = (int)$taxRate;

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

        return [$command, $receipeUrl, $receipeToken, $this->printerType];
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
            $product->unitPrice = MoneyTool::roundToCentile($rawProduct['our_unit_price']);
            $product->value = MoneyTool::roundToCentile($rawProduct['our_total_price']);
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
            'statusUrl' => $this->webhookUrl,
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
            CURLOPT_USERAGENT => Constants::USER_AGENT . '/' . Constants::PLUGIN_VERSION,
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

    private function getRecipeLines($rawProducts, $rawShipping)
    {
        $lines = [];
        /* Count from 1. */
        $id = 1;
        foreach ($rawProducts as $rawProduct) {
            $line = new SparkRecipeLine();
            $line->productOrServiceName = (string)$rawProduct['product_name'];
            $line->ID = $id;
            $line->SKU = (string)$rawProduct['reference'] ?: 'x-' . $rawProduct['id_product'];
            $line->quantity = (string)$rawProduct['product_quantity']; /* Yes, string. */
            $line->unitPrice = MoneyTool::displayMoneyWithDot($rawProduct['our_org_price']);
            $line->totalLineValue = MoneyTool::displayMoneyWithDot($rawProduct['our_org_total_price']);
            $line->taxRate =  $this->taxHelper->decodeToLetter($rawProduct['our_tax_rate']);
            if ($rawProduct['our_org_price'] !== $rawProduct['our_unit_price']) {
                /* Binary floats. */
                $value = round($rawProduct['our_org_price'] - $rawProduct['our_unit_price'], 2);
                $value = round($value * $rawProduct['product_quantity'], 2);
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
            $line = new SparkRecipeLine();
            $line->productOrServiceName = 'Transport';
            $line->ID = $id;
            $line->quantity = '1';
            $line->unitPrice = MoneyTool::displayMoneyWithDot($rawShipping['shipping']);
            $line->totalLineValue = MoneyTool::displayMoneyWithDot($rawShipping['shipping']);
            $line->taxRate =  $this->taxHelper->decodeToLetter($rawShipping['tax_rate']);
            $lines[] = $this->nullFilter($line);
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

        $rawPayload = [
            'token' => $recipeToken,
            'printStatus' => false,
            'eReceipt' => [
                'metadata' => [
                    'orderId' => (string)$order->getUniqReference(),
                    'globalReturnPolicy' => (string)$this->returnPolicySpark,
                    'TIN' => (string)$this->storeNip,
                    'nextDocumentID' => '', /* Required but unused. */
                    'taxRates' => $taxRates,
                    'subtotalsByTaxRate' => $this->getSubtotalsByTaxRate($rawProducts, $rawShipping),
                    'taxValue' => $this->getTaxValue($rawProducts, $rawShipping),
                    'taxTotalValue' => $this->getTaxTotalValue($rawProducts, $rawShipping),
                    'grossSaleValue' => $grossSaleValue,
                    'description' => 'DO ZAPŁATY',
                    'nextReceiptNumber' => '', /* Required but unused. */
                    #TODO Set line below to empty string when ready.
                    'cashRegisterID' => '0', /* Required but unused. */
                    #TODO Set line below to empty string when ready.
                    'cashierID' => '0', /* Required but unused. */
                    'endTime' => $nowAtom,
                    'documentDigitalSignature' => '', /* Required but unused. */
                    'uniqueCashRegisterID' => '', /* Required but unused. */
                ],
                'lines' => $this->getRecipeLines($rawProducts, $rawShipping),
                'payment' => [
                    'payments' => [ /* Yes, array with single child. */
                        [
                            'paymentName' => $order->payment ?: '(b.d.)',
                            'paymentForm' => 'Przelew',
                            'paidThisForm' => $paid,
                        ]
                    ],
                    'totalPaid' => $paid,
                ],
            ],
            'currentTimePOS' => $nowAtom,
        ];

        /* Floating point zero as string may be true in PHP. */
        $totalDiscounts = (float)$order->total_discounts;
        if ($totalDiscounts) {
            $rebate = MoneyTool::displayMoneyWithDot(-$totalDiscounts); /* Negate value. */
            $rawPayload['eReceipt']['metadata']['rebatesMarkups'] = [[
                'type' => 'OBNIŻKA',
                'value' => $rebate,
                'name' => 'Zniżka',
            ]];
        }
        if (!$this->returnPolicySpark) {
            unset($rawPayload['eReceipt']['metada']['globalReturnPolicy']);
        }

        return $rawPayload;
    }

    private function finalRequest(string $recipeToken, Order $order, array $rawProducts, array $rawShipping)
    {
        $rawPayload = $this->getFinalRequestData($order, $recipeToken, $rawProducts, $rawShipping);
        $query = http_build_query([
            'commandProtocol' => $this->printerType
        ]);

        $payload = json_encode($rawPayload);
        $fullUrl = $this->url . '/transactions/' . $recipeToken . '/receipt?' . $query;
        $this->tryCreateLogRequest($fullUrl, $payload);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_URL => $fullUrl,
            CURLOPT_USERAGENT => Constants::USER_AGENT . '/' . Constants::PLUGIN_VERSION,
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
                return $response['command'];
            }
        }

        return null;
    }

    private function tryCreateLogRequest($url, $payload)
    {
        if (!$this->logRequests) {
            return;
        }
        if (!$this->logFileName) {
            $random = bin2hex(random_bytes(3));
            $this->logFileName = 'eparagony_spark_' . date('Y-m-d_H-i-s') . '_' . $random;
        }
        $path = $this->logDirectory . DIRECTORY_SEPARATOR . $this->logFileName;
        $content = "\n". $url . "\n" . $payload . "\n";
        file_put_contents($path, $content, FILE_APPEND);
    }
}
