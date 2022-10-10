<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony;

class TelephoneTool
{
    const RX_PL = '/^((\\+|00)48)?(\d{9})$/';
    const RX_PL_IDX = 3;

    public static function canonizeToPolish($number) : ?string
    {
        if (preg_match(self::RX_PL, $number, $m)) {
            return '+48'.$m[self::RX_PL_IDX];
        } else {
            return null;
        }
    }
}
