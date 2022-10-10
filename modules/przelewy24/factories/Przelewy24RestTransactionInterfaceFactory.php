<?php
/**
 * Class Przelewy24RestTransactionInterfaceFactory
 *
 * @author Przelewy24
 * @copyright Przelewy24
 * @license https://www.gnu.org/licenses/gpl.html
 *
 */

/**
 * One of factories for Przelewy24 plugin.
 *
 */
class Przelewy24RestTransactionInterfaceFactory
{
    /**
     * Create instance of Przelewy24RestTransactionInterface.
     *
     * @param string $suffix Money suffix.
     * @return Przelewy24RestTransactionInterface
     */
    public static function buildForSuffix($suffix)
    {
        return Przelewy24RestTransactionFactory::buildForSuffix($suffix);
    }
}
