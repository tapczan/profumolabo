<?php
/**
 * Interface Przelewy24ClassInterface
 *
 * @author Przelewy24
 * @copyright Przelewy24
 * @license https://www.gnu.org/licenses/lgpl-3.0.en.html
 *
 */

/**
 * Interface Przelewy24ClassInterface
 */
interface Przelewy24ClassInterface
{
    /**
     * Returns host URL.
     *
     * @return string
     */
    public function getHost();

    /**
     * Returns URL for direct request (trnDirect).
     *
     * @return string
     */
    public function trnDirectUrl();

    /**
     * Adds value do post request.
     *
     * @param string $name Argument name.
     * @param int|string|bool $value Argument value.
     */
    public function addValue($name, $value);

    /**
     * Return direct sign.
     *
     * @param array $data
     *
     * @return string
     */
    public function trnDirectSign($data);
}
