<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony;

class Constants
{
    const ADMIN_SCRIPT_VERSION = '002';
    const PLUGIN_VERSION = '0.3.4';
    const USER_AGENT = 'Eparagony';

    /* This is as constant as possible in PHP. */
    public static function getUserAgentWithVersion()
    {
        $ret = self::USER_AGENT . '/' . self::PLUGIN_VERSION . ' (PHP ' . PHP_VERSION . ')';
        if (defined('_PS_VERSION_')) {
            $ret .= ' (PrestaShop ' . _PS_VERSION_ . ')';
        }

        return $ret;
    }
}
