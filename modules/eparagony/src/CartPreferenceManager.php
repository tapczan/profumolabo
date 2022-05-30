<?php
/**
 * @author Check AUTHORS file.
 * @copyright Spark
 * @license proprietary
 */

namespace Spark\EParagony;

use Spark\EParagony\Entity\EparagonyCartConfig;
use Cart;
use Doctrine\ORM\EntityManagerInterface;
use Order;
use PrestaShop\PrestaShop\Adapter\Entity\Address;

class CartPreferenceManager
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function setPhone($cartId, $phone)
    {
        $repo = $this->em->getRepository(EparagonyCartConfig::class);
        $entity = $repo->findOneBy(['cartId' => $cartId]);
        if ($entity) {
            assert($entity instanceof EparagonyCartConfig);
            $rest = json_decode($entity->getRest(), true);
            if (is_array($rest)) {
                $rest['phone'] = $phone;
            } else {
                $rest = ['phone' => $phone];
            }
        } else {
            $entity = new EparagonyCartConfig();
            $rest = ['phone' => $phone];
            $entity->setCartId($cartId);
            $this->em->persist($entity);
        }

        $entity->setRest(json_encode($rest));
        $this->em->flush();
    }

    private static function copyAddress($addressId) : Address
    {
        $old = new Address($addressId);
        $new = new Address();
        $new->id_customer = $old->id_customer;
        $new->id_manufacturer = $old->id_manufacturer;
        $new->id_supplier = $old->id_supplier;
        $new->id_warehouse = $old->id_warehouse;
        $new->id_country = $old->id_country;
        $new->country = $old->country;
        $new->alias = $old->alias;
        $new->company = $old->company;
        $new->lastname = $old->lastname;
        $new->firstname = $old->firstname;
        $new->address1 = $old->address1;
        $new->address2 = $old->address2;
        $new->postcode = $old->postcode;
        $new->city = $old->city;
        $new->other = $old->other;
        $new->phone = $old->phone;
        $new->phone_mobile = $old->phone_mobile;
        $new->vat_number = $old->vat_number;
        $new->dni = $old->dni;
        /* Ignore dates. */
        /* Mark as deleted. */
        $new->deleted = true;

        return $new;
    }

    public function tryPopulatePhone(Cart $cart, Order $order)
    {
        $repo = $this->em->getRepository(EparagonyCartConfig::class);
        $entity = $repo->findOneBy(['cartId' => $cart->id]);
        if ($entity) {
            assert($entity instanceof EparagonyCartConfig);
            $rest = json_decode($entity->getRest(), true);
            if ($rest && $rest['phone']) {
                $newAddress = self::copyAddress($order->id_address_invoice);
                $newAddress->phone = $rest['phone'];
                $newAddress->add();
                $order->id_address_invoice = $newAddress->id;
                $order->update();
            }
        }
    }
}
