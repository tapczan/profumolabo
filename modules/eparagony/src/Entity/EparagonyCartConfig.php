<?php
/**
 * @author Check AUTHORS file.
 * @copyright TBD
 * @license TBD
 */

namespace ASoftwareHouse\EParagony\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 *
 * We have to use prefix in the class name.
 */
class EparagonyCartConfig
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\Column(name="id_document_state", type="integer", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_cart", type="integer", unique=true, options={"unsigned":true} )
     */
    private $cartId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="rest", type="text")
     */
    private $rest;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return EparagonyCartConfig
     */
    public function setId(?int $id): EparagonyCartConfig
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCartId(): ?int
    {
        return $this->cartId;
    }

    /**
     * @param int|null $cartId
     * @return EparagonyCartConfig
     */
    public function setCartId(?int $cartId): EparagonyCartConfig
    {
        $this->cartId = $cartId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRest(): ?string
    {
        return $this->rest;
    }

    /**
     * @param string|null $rest
     * @return EparagonyCartConfig
     */
    public function setRest(?string $rest): EparagonyCartConfig
    {
        $this->rest = $rest;
        return $this;
    }
}
