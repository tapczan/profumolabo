<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony\SparkApi;

use Spark\EParagony\ConfigHelper;
use Spark\EParagony\ConfigurationHolder;
use Spark\EParagony\Constants;
use Spark\EParagony\MagicBox;
use LogicException;

class ApiSparkFactory
{
    private $config;
    private $magicBox;
    private $logDir;

    public function __construct(ConfigHelper $configHelper, MagicBox $magicBox, $logDir)
    {
        $this->config = $configHelper::getSavedConfig();
        $this->magicBox = $magicBox;
        $this->logDir = $logDir;
    }

    /*
     * Get api proxy.
     *
     * This method is too fragile to put in autogenerated code.
     */
    public function getApiClass() : ApiSpark
    {
        $config = $this->config;
        $url = self::getLoginUrl($config);
        $payload = http_build_query([
            'grant_type' => 'client_credentials',
            'client_secret' => $config->client_secret,
            'client_id' => $config->client_id,
            'scope' => 'ecommerce',
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_POST => true,
            CURLOPT_URL => $url . '/auth/token',
            CURLOPT_USERAGENT => Constants::getUserAgentWithVersion(),
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
        ]);
        $rawResponse = curl_exec($curl);
        $response = json_decode($rawResponse, true);
        if (isset($response['access_token'])) {
            $token = $response['access_token'];
        } else {
            throw new ApiSparkException('Cannot connect to API Spark.', ApiSparkException::CODE_CANNOT_AUTHENTICATE);
        }

        $taxHelper = new TaxHelperForSpark(
            $config->tax_a,
            $config->tax_b,
            $config->tax_c,
            $config->tax_d,
            $config->tax_e,
            $config->tax_f,
            $config->tax_g
        );

        return new ApiSpark(
            $config->pos_id,
            $config->store_nip,
            $token,
            self::getMainUrl($config),
            $config->log_spark_requests,
            $this->logDir,
            $config->return_policy_spark,
            $this->magicBox::getModuleLink('fiscalization'),
            $taxHelper
        );
    }

    private static function getLoginUrl(ConfigurationHolder $config)
    {
        if ($config->test_mode) {
            return 'https://login.sandbox.spark.pl';
        } else {
            return 'https://login.spark.pl';
        }
    }

    private static function getMainUrl(ConfigurationHolder $config)
    {
        if ($config->test_mode) {
            return 'https://sandbox.spark.pl';
        } else {
            return 'https://api.spark.pl';
        }
    }
}
