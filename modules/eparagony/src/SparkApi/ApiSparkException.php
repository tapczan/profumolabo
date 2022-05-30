<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony\SparkApi;

use RuntimeException;

class ApiSparkException extends RuntimeException
{
    const CODE_COMMAND_FAILED = 8;
    const CODE_UNKNOWN_TAX = 16;
    const CODE_CANNOT_AUTHENTICATE = 32;
}
