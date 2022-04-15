<?php
/**
 * @author Check AUTHORS file.
 * @copyright TBD
 * @license TBD
 */

namespace ASoftwareHouse\EParagony;

class MoneyTool
{
    public static function roundTax($input): int
    {
        return (int)round($input);
    }

    public static function roundToCentile($input): int
    {
        return (int)round(round($input, 2) * 100);
    }

    public static function displayMoneyWithDot($input): string
    {
        $input = round($input, 2);
        return number_format($input, 2, '.', '');
    }

    public static function displayMoneyWithDotFromCentiles($input): string
    {
        $input = round($input) / 100;
        return number_format($input, 2, '.', '');
    }
}
