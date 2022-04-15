<?php
/**
 * @author Check AUTHORS file.
 * @copyright TBD
 * @license TBD
 */

namespace ASoftwareHouse\EParagony\Entity;

use ASoftwareHouse\EParagony\PrinterLogSimple;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table()
 *
 * We have to use prefix in the class name.
 */
class EparagonyPrinterLog
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\Column(name="id_printer_log", type="integer", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="document_text_id", type="string")
     */
    private $documentTextId;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var string|null
     *
     * @ORM\Column(name="rest", type="text")
     */
    private $payload;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return EparagonyPrinterLog
     */
    public function setId(?int $id): EparagonyPrinterLog
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDocumentTextId(): ?string
    {
        return $this->documentTextId;
    }

    /**
     * @param string|null $documentTextId
     * @return EparagonyPrinterLog
     */
    public function setDocumentTextId(?string $documentTextId): EparagonyPrinterLog
    {
        $this->documentTextId = $documentTextId;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getCreated(): ?DateTime
    {
        return $this->created;
    }

    /**
     * @param DateTime|null $created
     * @return EparagonyPrinterLog
     */
    public function setCreated(?DateTime $created): EparagonyPrinterLog
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPayload(): ?string
    {
        return $this->payload;
    }

    /**
     * @param string|null $payload
     * @return EparagonyPrinterLog
     */
    public function setPayload(?string $payload): EparagonyPrinterLog
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * Get a simple representation of document.
     *
     * @return PrinterLogSimple
     */
    public function getSimpleRepresentation(): PrinterLogSimple
    {
        $ret = new PrinterLogSimple();
        $ret->documentTextId = $this->getDocumentTextId();
        $ret->created = $this->getCreated();
        $ret->payload = $this->getPayload();

        return $ret;
    }
}
