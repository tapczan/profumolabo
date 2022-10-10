<?php
/**
 * Class Przelewy24ClassStaticInterfaceFactory
 *
 * @author Przelewy24
 * @copyright Przelewy24
 * @license https://www.gnu.org/licenses/gpl.html
 *
 */

/**
 * One of factories for Przelewy24 plugin.
 *
 * The class is aware of the whole configuration.
 *
 */
class Przelewy24ClassStaticInterfaceFactory
{
    /**
     * Create instance of Przelewy24ClassStaticInterface.
     *
     * @return Przelewy24ClassStaticInterface
     * @throws Exception
     */
    public static function getDefault()
    {
        return Przelewy24ClassFactory::buildDefault();
    }
}
