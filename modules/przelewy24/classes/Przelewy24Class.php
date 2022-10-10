<?php
/**
 * Przelewy24 comunication class
 * Communication protol version
 *
 * @author Przelewy24
 * @copyright Przelewy24
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 */

define('P24_VERSION', '3.2');
if (!class_exists('Przelewy24Class', false)) {
    class Przelewy24Class implements
        Przelewy24ClassInterface,
        Przelewy24ClassStaticInterface
    {
        /**
         * Live system URL address.
         *
         * @var string
         */
        private static $hostLive = 'https://secure.przelewy24.pl/';

        /**
         * Sandbox system URL address.
         *
         * @var string
         */
        private static $hostSandbox = 'https://sandbox.przelewy24.pl/';

        /**
         * Use Live (false) or Sandbox (true) environment.
         *
         * @var bool
         */
        private $testMode = false;

        /**
         * Merchant Id.
         *
         * @var int
         */
        private $merchantId = 0;

        /**
         * Merchant posId.
         *
         * @var int
         */
        private $posId = 0;

        /**
         * Salt to create a control sum (from P24 panel).
         *
         * @var string
         */
        private $salt = '';

        /**
         * Array of POST data.
         *
         * @var array
         */
        private $postData = array();

        /**
         * Minimal amount for single installment.
         *
         * @var int
         */
        private static $minInstallmentValue = 300;

        /**
         * Obcject constructor. Set initial parameters.
         *
         * @param int $merchantId
         * @param int $posId
         * @param string $salt
         * @param bool $testMode
         */
        public function __construct($merchantId, $posId, $salt, $testMode = false)
        {
            $this->posId = (int)trim($posId);
            $this->merchantId = (int)trim($merchantId);
            if (0 === $this->merchantId) {
                $this->merchantId = $this->posId;
            }
            $this->salt = trim($salt);
            $this->testMode = $testMode;

            $this->addValue('p24_merchant_id', $this->merchantId);
            $this->addValue('p24_pos_id', $this->posId);
            $this->addValue('p24_api_version', P24_VERSION);
        }

        /**
         * Returns host URL.
         *
         * @return string
         */
        public function getHost()
        {
            return self::getHostForEnvironment($this->testMode);
        }

        /**
         * Returns host URL For Environmen
         *
         * @param bool $isTestMode
         * @return string
         */
        public static function getHostForEnvironment($isTestMode = false)
        {
            return $isTestMode ? self::$hostSandbox : self::$hostLive;
        }

        /**
         * Get min installment value
         *
         * @return int
         */
        public static function getMinInstallmentValue()
        {
            return self::$minInstallmentValue;
        }

        /**
         * Returns URL for direct request (trnDirect).
         *
         * @return string
         */
        public function trnDirectUrl()
        {
            return $this->getHost() . 'trnDirect';
        }

        /**
         * Adds value do post request.
         *
         * @param string $name Argument name.
         * @param int|string|bool $value Argument value.
         */
        public function addValue($name, $value)
        {
            if ($this->validateField($name, $value)) {
                $this->postData[$name] = $value;
            }
        }

        /**
         * Redirects or returns URL to a P24 payment screen.
         *
         * @param string $token Token
         * @param bool $redirect If set to true redirects to P24 payment screen.
         *                       If set to false function returns URL to redirect to P24 payment screen.
         *
         * @return string URL to P24 payment screen
         */
        public function trnRequest($token, $redirect = true)
        {
            $token = Tools::substr($token, 0, 100);
            $url = $this->getHost() . 'trnRequest/' . $token;
            if ($redirect) {
                Tools::redirect($url);

                return '';
            }

            return $url;
        }

        /**
         * Validate api version.
         *
         * @param string $version
         *
         * @return bool
         */
        private function validateVersion(&$version)
        {
            if (preg_match('/^[0-9]+(?:\.[0-9]+)*(?:[\.\-][0-9a-z]+)?$/', $version)) {
                return true;
            }
            $version = '';

            return false;
        }

        /**
         * Validate email.
         *
         * @param string $email
         * @return bool
         */
        private function validateEmail(&$email)
        {
            if (($email = filter_var($email, FILTER_VALIDATE_EMAIL))) {
                return true;
            }
            $email = '';

            return false;
        }

        /**
         * Validate number.
         *
         * @param string|float|int $value
         * @param bool $min
         * @param bool $max
         *
         * @return bool
         */
        private function validateNumber(&$value, $min = false, $max = false)
        {
            if (is_numeric($value)) {
                $value = (int)$value;
                if ((false !== $min && $value < $min) || (false !== $max && $value > $max)) {
                    return false;
                }

                return true;
            }
            $value = (false !== $min ? $min : 0);

            return false;
        }

        /**
         * Validate string.
         *
         * @param string $value
         * @param int $len
         *
         * @return bool
         */
        private function validateString(&$value, $len = 0)
        {
            $len = (int)$len;
            if (preg_match("/<[^<]+>/", $value, $m) > 0) {
                return false;
            }

            if (0 === $len ^ Tools::strlen($value) <= $len) {
                return true;
            }
            $value = '';

            return false;
        }

        private function validateUrl(&$url, $len = 0)
        {
            $len = (int)$len;
            if (0 === $len ^ Tools::strlen($url) <= $len) {
                if (preg_match('@^https?://[^\s/$.?#].[^\s]*$@iS', $url)) {
                    return true;
                }
            }
            $url = '';

            return false;
        }

        /**
         * Validate enum.
         *
         * @param string $value Provided value.
         * @param string[] $haystack Array of valid values.
         *
         * @return bool
         */
        private function validateEnum(&$value, $haystack)
        {
            if (in_array(Tools::strtolower($value), $haystack)) {
                return true;
            }
            $value = $haystack[0];

            return false;
        }

        /**
         * Validate field.
         *
         * @param string $field
         * @param mixed &$value
         *
         * @return boolean
         */
        public function validateField($field, &$value)
        {
            $ret = false;
            switch ($field) {
                case 'p24_session_id':
                    $ret = $this->validateString($value, 100);
                    break;
                case 'p24_description':
                    $ret = $this->validateString($value, 1024);
                    break;
                case 'p24_address':
                    $ret = $this->validateString($value, 80);
                    break;
                case 'p24_country':
                case 'p24_language':
                    $ret = $this->validateString($value, 2);
                    break;
                case 'p24_client':
                case 'p24_city':
                    $ret = $this->validateString($value, 50);
                    break;
                case 'p24_merchant_id':
                case 'p24_pos_id':
                case 'p24_order_id':
                case 'p24_amount':
                case 'p24_method':
                case 'p24_time_limit':
                case 'p24_channel':
                case 'p24_shipping':
                    $ret = $this->validateNumber($value);
                    break;
                case 'p24_wait_for_result':
                    $ret = $this->validateNumber($value, 0, 1);
                    break;
                case 'p24_api_version':
                    $ret = $this->validateVersion($value);
                    break;
                case 'p24_sign':
                    if ((32 === Tools::strlen($value)) && ctype_xdigit($value)) {
                        $ret = true;
                    } else {
                        $value = '';
                    }
                    break;
                case 'p24_url_return':
                case 'p24_url_status':
                    $ret = $this->validateUrl($value, 250);
                    break;
                case 'p24_currency':
                    $ret = preg_match('/^[A-Z]{3}$/', $value);
                    if (!$ret) {
                        $value = '';
                    }
                    break;
                case 'p24_email':
                    $ret = $this->validateEmail($value);
                    break;
                case 'p24_encoding':
                    $ret = $this->validateEnum($value, array('iso-8859-2', 'windows-1250', 'urf-8', 'utf8'));
                    break;
                case 'p24_transfer_label':
                    $ret = $this->validateString($value, 20);
                    break;
                case 'p24_phone':
                    $ret = $this->validateString($value, 12);
                    break;
                case 'p24_zip':
                    $ret = $this->validateString($value, 10);
                    break;
                default:
                    if ((0 === strpos($field, 'p24_quantity_')) ||
                        (0 === strpos($field, 'p24_price_')) ||
                        (0 === strpos($field, 'p24_number_'))
                    ) {
                        $ret = $this->validateNumber($value);
                    } elseif ((0 === strpos($field, 'p24_name_'))
                        || (0 === strpos($field, 'p24_description_'))) {
                        $ret = $this->validateString($value, 127);
                    } else {
                        $value = '';
                    }
                    break;
            }

            return $ret;
        }

        /**
         * Filter value.
         *
         * @param string $field
         * @param string $value
         *
         * @return bool|string
         */
        private function filterValue($field, $value)
        {
            return $this->validateField($field, $value) ? addslashes($value) : false;
        }

        /**
         * Check mandatory fields for action.
         *
         * @param array $fieldsArray
         * @param string $action
         *
         * @return bool
         *
         * @throws Exception
         */
        public function checkMandatoryFieldsForAction($fieldsArray, $action)
        {
            $keys = array_keys($fieldsArray);
            $verification = ('trnVerify' === $action);
            static $mandatory = array(
                'p24_order_id',//verify
                'p24_sign',
                'p24_merchant_id',
                'p24_pos_id',
                'p24_api_version',
                'p24_session_id',
                'p24_amount',//all
                'p24_currency',
                'p24_description',
                'p24_country',
                'p24_url_return',
                'p24_currency',
                'p24_email',
            );

            for ($i = ($verification ? 0 : 1); $i < ($verification ? 4 : count($mandatory)); $i++) {
                if (!in_array($mandatory[$i], $keys)) {
                    throw new Exception('Field ' . $mandatory[$i] . ' is required for ' . $action . ' request!');
                }
            }

            return true;
        }

        /**
         * Parse and validate POST response data from Przelewy24.
         *
         * @return array - valid response | false - invalid crc | null - not a Przelewy24 response
         */
        public function parseStatusResponse()
        {
            if (Tools::getIsset(
                'p24_session_id',
                'p24_order_id',
                'p24_merchant_id',
                'p24_pos_id',
                'p24_amount',
                'p24_currency',
                'p24_method',
                'p24_sign'
            )) {
                $session_id = $this->filterValue('p24_session_id', Tools::getValue('p24_session_id'));
                $merchant_id = $this->filterValue('p24_merchant_id', Tools::getValue('p24_merchant_id'));
                $pos_id = $this->filterValue('p24_pos_id', Tools::getValue('p24_pos_id'));
                $order_id = $this->filterValue('p24_order_id', Tools::getValue('p24_order_id'));
                $amount = $this->filterValue('p24_amount', Tools::getValue('p24_amount'));
                $currency = $this->filterValue('p24_currency', Tools::getValue('p24_currency'));
                $method = $this->filterValue('p24_method', Tools::getValue('p24_method'));
                $sign = $this->filterValue('p24_sign', Tools::getValue('p24_sign'));

                if (((int)$merchant_id !== (int)$this->merchantId) ||
                    ((int)$pos_id !== (int)$this->posId) ||
                    (md5(
                        $session_id .
                            '|' .
                            $order_id .
                            '|' .
                            $amount .
                            '|' .
                            $currency .
                            '|' .
                            $this->salt
                    ) !== $sign)) {
                    return false;
                }

                return array(
                    'p24_session_id' => $session_id,
                    'p24_order_id' => $order_id,
                    'p24_amount' => $amount,
                    'p24_currency' => $currency,
                    'p24_method' => $method,
                );
            }

            return null;
        }

        /**
         * Return direct sign.
         *
         * @param array $data
         *
         * @return string
         */
        public function trnDirectSign($data)
        {
            return md5(
                $data['p24_session_id'] . '|'
                . $this->posId . '|'
                . $data['p24_amount'] . '|'
                . $data['p24_currency'] . '|'
                . $this->salt
            );
        }
    }
}
