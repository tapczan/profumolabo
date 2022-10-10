<?php
/**
 * Class Przelewy24ExtraCharge
 *
 * @author Przelewy24
 * @copyright Przelewy24
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 *
 */

/**
 * Class Przelewy24Logger
 */
class Przelewy24Logger extends PrestaShopLoggerCore
{
    // Log message limit.
    const LOG_MESSAGE_LIMIT = 2 ** 14;

    /**
     * Add truncated log.
     *
     * @param string $message Log message.
     * @param string $truncMessage Comment about shortening message.
     *
     * @return bool
     */
    public static function addTruncatedLog($message, $truncMessage = 'Log message has been shortened.')
    {
        if (Tools::strlen($message) >= self::LOG_MESSAGE_LIMIT) {
            $message = mb_substr($message, 0, self::LOG_MESSAGE_LIMIT - 128, 'utf-8') . '…';
            self::addLog($truncMessage, 2);
        }

        return self::addLog($message);
    }
}
