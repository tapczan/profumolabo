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
class EparagonyPrinterToken
{
    const PRIVILEGE_ALL = 'all';

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
     * @ORM\Column(name="token", type="string", unique=true)
     */
    private $token;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="valid_to", type="datetime")
     */
    private $validTo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="privileges", type="text")
     */
    private $privileges;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return EparagonyPrinterToken
     */
    public function setId(?int $id): EparagonyPrinterToken
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     * @return EparagonyPrinterToken
     */
    public function setToken(?string $token): EparagonyPrinterToken
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getCreated(): ?DateTime
    {
        return $this->created ? clone $this->created : null;
    }

    /**
     * @param DateTime|null $created
     * @return EparagonyPrinterToken
     */
    public function setCreated(?DateTime $created): EparagonyPrinterToken
    {
        $this->created = $created ? clone $created : null;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getValidTo(): ?DateTime
    {
        return $this->validTo ? clone $this->validTo : null;
    }

    /**
     * @param DateTime|null $validTo
     * @return EparagonyPrinterToken
     */
    public function setValidTo(?DateTime $validTo): EparagonyPrinterToken
    {
        $this->validTo = $validTo ? clone $validTo : null;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrivileges(): ?string
    {
        return $this->privileges;
    }

    /**
     * @param string|null $privileges
     * @return EparagonyPrinterToken
     */
    public function setPrivileges(?string $privileges): EparagonyPrinterToken
    {
        $this->privileges = $privileges;
        return $this;
    }
}
