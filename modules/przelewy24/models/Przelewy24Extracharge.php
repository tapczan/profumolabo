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
 * Class Przelewy24Extracharge
 */
class Przelewy24Extracharge extends ObjectModel
{
    /**
     * Extracharge id.
     *
     * @var int
     */
    public $id_extracharge;

    /**
     * Order id (ps order).
     *
     * @var int
     */
    public $id_order;

    /**
     * Amount in grosz`s (1 grosz === 1/100 PLN)
     *
     * @var int
     */
    public $extra_charge_amount;

    const TABLE = 'przelewy24_extra_charges';

    const ID_EXTRACHARGE = 'id_extracharge';
    const ID_ORDER = 'id_order';
    const EXTRA_CHARGE_AMOUNT = 'extra_charge_amount';

    /**
     * Model definition.
     *
     * @var array
     */
    public static $definition = array(
        'table' => self::TABLE,
        'primary' => self::ID_EXTRACHARGE,
        'fields' => array(
            self::ID_EXTRACHARGE => array('type' => self::TYPE_INT),
            self::ID_ORDER => array('type' => self::TYPE_INT, 'required' => true),
            self::EXTRA_CHARGE_AMOUNT => array('type' => self::TYPE_INT, 'required' => true),
        ),
    );

    /**
     * Returns first entry matching for order id.
     *
     * PretaShopCollection does not apply limit to db query on its own
     * (when using getFirst). It selects ALL entries from DB and then returns first of them.
     * That is why line:
     *  ->setPageSize(1)
     * had to be added.
     *
     * @param int $orderId
     *
     * @return Przelewy24ExtraCharge
     */
    public static function findOneByOrderId($orderId)
    {
        $queryBuilder = new PrestaShopCollection(self::class);

        return $queryBuilder
            ->where('id_order', '=', $orderId)
            ->setPageSize(1)
            ->getFirst();
    }

    /**
     * Gets or creates extra charge object by its order id.
     * @param int $orderId
     *
     * @return Przelewy24ExtraCharge
     */
    public static function prepareByOrderId($orderId)
    {
        $extracharge = self::findOneByOrderId($orderId);
        if (!($extracharge instanceof ObjectModel)) {
            $extracharge = new Przelewy24ExtraCharge();
        }
        $extracharge->id_order = $orderId;

        return $extracharge;
    }
}
