<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony\SparkApi;


class TaxHelperForSpark
{
    private $taxes;

    public function __construct($a, $b, $c, $d, $e, $f, $g)
    {
        $this->taxes = [
            'A' => (int)round((float)$a),
            'B' => (int)round((float)$b),
            'C' => (int)round((float)$c),
            'D' => (int)round((float)$d),
            'E' => (int)round((float)$e),
            'F' => (int)round((float)$f),
            'G' => (int)round((float)$g),
        ];
    }

    public function decodeToLetter($amount)
    {
        $amount = (int)round($amount);
        foreach ($this->taxes as $letter => $tax) {
            if ($tax === $amount) {
                return $letter;
            }
        }

        return null;
    }

    public function getTable()
    {
        /* The PHP pass arrays by value. */
        return $this->taxes;
    }
}
