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
class EparagonyDocumentStatus
{
    const STATE_BLANK = 'blank';
    const STATE_COMPILED = 'compiled';
    const STATE_CONFIRMED = 'confirmed';
    const STATE_DOWNLOADING = 'downloading';
    const STATE_ERROR = 'error';
    const STATE_IN_PROGRESS = 'in_progress';
    const STATE_QUEUED = 'queued';
    const STATE_READY = 'ready';
    const STATE_UNKNOWN = 'unknown';

    const TYPE_RECEIPT = 'receipt';

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
     * @ORM\Column(name="id_order", type="integer", unique=true, options={"unsigned":true} )
     */
    private $orderId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="document_state", type="string")
     */
    private $documentState;

    /**
     * @var string|null
     *
     * @ORM\Column(name="document_type", type="string", nullable=true)
     */
    private $documentType;

    /**
     * @var string|null
     *
     * @ORM\Column(name="text_id", type="string", unique=true)
     */
    private $textId;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="checked", type="datetime")
     */
    private $checked;

    /**
     * @var string|null
     *
     * @ORM\Column(name="transitions", type="text")
     */
    private $transitions;

    /**
     * @var int|null
     *
     * @ORM\Column(name="retry_count", type="integer")
     */
    private $retryCount;

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
     * @return EparagonyDocumentStatus
     */
    public function setId(?int $id): EparagonyDocumentStatus
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    /**
     * @param int|null $orderId
     * @return EparagonyDocumentStatus
     */
    public function setOrderId(?int $orderId): EparagonyDocumentStatus
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDocumentState(): ?string
    {
        return $this->documentState;
    }

    /**
     * @param string|null $documentState
     * @return EparagonyDocumentStatus
     */
    public function setDocumentState(?string $documentState): EparagonyDocumentStatus
    {
        $this->documentState = $documentState;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDocumentType(): ?string
    {
        return $this->documentType;
    }

    /**
     * @param string|null $documentType
     * @return EparagonyDocumentStatus
     */
    public function setDocumentType(?string $documentType): EparagonyDocumentStatus
    {
        $this->documentType = $documentType;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTextId(): ?string
    {
        return $this->textId;
    }

    /**
     * @param string|null $textId
     * @return EparagonyDocumentStatus
     */
    public function setTextId(?string $textId): EparagonyDocumentStatus
    {
        $this->textId = $textId;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdated(): ?DateTime
    {
        /* Limitations of ORM. */
        return clone $this->updated;
    }

    /**
     * @param DateTime|null $updated
     * @return EparagonyDocumentStatus
     */
    public function setUpdated(?DateTime $updated): EparagonyDocumentStatus
    {
        /* Limitations of ORM. */
        $this->updated = clone $updated;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getChecked(): ?DateTime
    {
        return $this->checked;
    }

    /**
     * @param DateTime|null $checked
     * @return EparagonyDocumentStatus
     */
    public function setChecked(?DateTime $checked): EparagonyDocumentStatus
    {
        $this->checked = $checked;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTransitions(): ?string
    {
        return $this->transitions;
    }

    /**
     * @param string|null $transitions
     * @return EparagonyDocumentStatus
     */
    public function setTransitions(?string $transitions): EparagonyDocumentStatus
    {
        $this->transitions = $transitions;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getRetryCount(): ?int
    {
        return $this->retryCount;
    }

    /**
     * @param int|null $retryCount
     * @return EparagonyDocumentStatus
     */
    public function setRetryCount(?int $retryCount): EparagonyDocumentStatus
    {
        $this->retryCount = $retryCount;
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
     * @return EparagonyDocumentStatus
     */
    public function setRest(?string $rest): EparagonyDocumentStatus
    {
        $this->rest = $rest;
        return $this;
    }

    public function appendTransition(string $newTransition)
    {
        $oldTransitions = $this->getTransitions();
        $this->setTransitions($oldTransitions . $newTransition . "\n");

        return $this;
    }

    public function extractRestField($key) : ?string
    {
        $data = json_decode($this->getRest(), true);
        if (is_array($data)) {
            return $data[$key] ?? null;
        } else {
            return null;
        }
    }
}
