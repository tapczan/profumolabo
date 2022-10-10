<?php
/**
 * Class Przelewy24RestBlikInterfaceFactory
 *
 * @author Przelewy24
 * @copyright Przelewy24
 * @license https://www.gnu.org/licenses/gpl.html
 *
 */

/**
 * One of factories for Przelewy24 plugin.
 */
class Przelewy24RestBlikInterfaceFactory
{
    /**
     * Create instance of Przelewy24RestBlikInterface for suffix.
     *
     * @param string $suffix Money suffix.
     * @return Przelewy24RestBlikInterface
     * @throws Exception
     */
    public static function getForSuffix($suffix)
    {
        return Przelewy24RestBlikFactory::buildForSuffix($suffix);
    }

    /**
     * Create default instance of Przelewy24RestBlikInterface.
     *
     * @return Przelewy24RestBlikInterface
     * @throws Exception
     */
    public static function getDefault()
    {
        $default = Przelewy24RestBlikFactory::buildDefault();
        if (!$default) {
            /* There is no default */
            $default = new Przelewy24RestBlikEmpty();
        }

        return $default;
    }
}
