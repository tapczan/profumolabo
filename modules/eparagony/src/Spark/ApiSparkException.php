<?php
/**
 * @author Check AUTHORS file.
 * @copyright TBD
 * @license TBD
 */

namespace ASoftwareHouse\EParagony\Spark;

use RuntimeException;

class ApiSparkException extends RuntimeException
{
    const CODE_COMMAND_FAILED = 8;
    const CODE_UNKNOWN_TAX = 16;
    const CODE_CANNOT_AUTHENTICATE = 32;
}
