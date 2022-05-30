<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony;

use Context;

/**
 * This class act as proxy to internal PrestaShop methods.
 *
 * It is easier test.
 */
class MagicBox
{
    public static function getModuleLink($subLink, $params = [])
    {
        return Context::getContext()->link->getModuleLink('eparagony', $subLink, $params);
    }
}
