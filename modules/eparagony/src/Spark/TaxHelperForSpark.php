<?php
/**
 * @author Check AUTHORS file.
 * @copyright TBD
 * @license TBD
 */

namespace ASoftwareHouse\EParagony\Spark;


class TaxHelperForSpark
{
    private $taxes;

    public function __construct($a, $b, $c, $d, $e, $f, $g)
    {
        $this->taxes = [
            'A' => (int)round($a),
            'B' => (int)round($b),
            'C' => (int)round($c),
            'D' => (int)round($d),
            'E' => (int)round($e),
            'F' => (int)round($f),
            'G' => (int)round($g),
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
