<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony;

class ConfigurationHolder
{
    const POS_ID = 'pos_id';
    const STORE_NIP = 'store_nip';
    const CLIENT_ID = 'client_id';
    const CLIENT_SECRET = 'client_secret';
    const WEBHOOK_SECRET = 'webhook_secret';
    const TEST_MODE = 'test_mode';
    const LOG_SPARK_REQUESTS = 'log_spark_requests';
    const RETURN_POLICY_SPARK = 'return_policy_spark';

    const TAX_A = 'tax_a';
    const TAX_B = 'tax_b';
    const TAX_C = 'tax_c';
    const TAX_D = 'tax_d';
    const TAX_E = 'tax_e';
    const TAX_F = 'tax_f';
    const TAX_G = 'tax_g';

    const ASK_FOR_PHONE = 'ask_for_phone';

    public $pos_id;
    public $store_nip;
    public $client_id;
    public $client_secret;
    public $webhook_secret;
    public $test_mode;
    public $log_spark_requests;
    public $return_policy_spark;

    public $tax_a;
    public $tax_b;
    public $tax_c;
    public $tax_d;
    public $tax_e;
    public $tax_f;
    public $tax_g;

    public $ask_for_phone;

    public static function fromJson(array $data) : self
    {
        $ret = new self();
        $ret->pos_id = $data[self::POS_ID] ?? '';
        $ret->store_nip = $data[self::STORE_NIP] ?? '';
        $ret->client_id = $data[self::CLIENT_ID] ?? '';
        $ret->client_secret = $data[self::CLIENT_SECRET] ?? '';
        $ret->webhook_secret = $data[self::WEBHOOK_SECRET] ?? '';
        $ret->test_mode = (bool)($data[self::TEST_MODE] ?? false);
        $ret->log_spark_requests = (bool)($data[self::LOG_SPARK_REQUESTS] ?? false);
        $ret->return_policy_spark = $data[self::RETURN_POLICY_SPARK] ?? '';

        $ret->tax_a = $data[self::TAX_A] ?? 0;
        $ret->tax_b = $data[self::TAX_B] ?? 0;
        $ret->tax_c = $data[self::TAX_C] ?? 0;
        $ret->tax_d = $data[self::TAX_D] ?? 0;
        $ret->tax_e = $data[self::TAX_E] ?? 0;
        $ret->tax_f = $data[self::TAX_F] ?? 0;
        $ret->tax_g = $data[self::TAX_G] ?? 0;

        $ret->ask_for_phone = (bool)($data[self::ASK_FOR_PHONE] ?? true);

        return $ret;
    }

    public function isValid(&$errors) : bool
    {
        $errors = [
            'tax_values' => false,
        ];

        if (!is_numeric($this->tax_a)
            || !is_numeric($this->tax_b)
            || !is_numeric($this->tax_c)
            || !is_numeric($this->tax_d)
            || !is_numeric($this->tax_e)
            || !is_numeric($this->tax_f)
            || !is_numeric($this->tax_g)
        ) {
            $errors['tax_values'] = true;
        }

        return $this->pos_id
            && $this->store_nip
            && $this->client_id
            && $this->client_secret
            && $this->webhook_secret
            && !$errors['tax_values']
        ;
    }
}
