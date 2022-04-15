<?php
/**
 * @author Check AUTHORS file.
 * @copyright TBD
 * @license TBD
 */

namespace ASoftwareHouse\EParagony\Spark;

class SparkProduct
{
    public $id; /* It is only an ordinal number per recipe. */
    public $name;
    public $quantity;
    public $unitPrice; /* Use 1/100 of main currency. */
    public $value; /* Use 1/100 of main currency. */
    public $taxRate; /* A, B, C */
    public $taxRateValue; /* In percents. */
    public $SKU;
    public $EAN;
    public $databaseId;
}
