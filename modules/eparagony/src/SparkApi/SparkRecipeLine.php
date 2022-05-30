<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony\SparkApi;

class SparkRecipeLine
{
    public $productOrServiceName;
    public $ID;
    public $SKU;
    public $quantity; /* Float as string. */
    public $unitPrice; /* Float as string. */
    public $totalLineValue; /* Float as string. */
    public $taxRate; /* A, B, C, D... */
    public $rebatesMarkups; /* Array or null. */
}
