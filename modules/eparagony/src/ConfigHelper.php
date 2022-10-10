<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony;

use Configuration;

class ConfigHelper
{
    const CONFIG_STRING = 'EPARAGONY_CONFIG';

    private static function propagateDefaultvalues(ConfigurationHolder $configuration)
    {
        /* Empty for now. */
    }

    public static function getSavedConfig(): ConfigurationHolder
    {
        $raw = Configuration::get(self::CONFIG_STRING);
        if ($raw) {
            $config = json_decode($raw, true);
            $holder = ConfigurationHolder::fromJson($config);
        } else {
            $holder = new ConfigurationHolder();
        }
        self::propagateDefaultvalues($holder);

        return $holder;
    }

    public static function saveConfig(ConfigurationHolder $configuration)
    {
        self::propagateDefaultvalues($configuration);
        $toStore = json_encode($configuration);
        Configuration::updateValue(self::CONFIG_STRING, $toStore);
    }
}
