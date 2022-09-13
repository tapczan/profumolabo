<?php

declare(strict_types=1);

namespace PrestaShop\Module\CreateitAccordion\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PrestaShop\Module\CreateitAccordion\Repository\CreateitAccordionRepository")
 */
class CreateitAccordion
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="id_createit_accordion", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column(name="id_shop", type="integer")
     */
    private $shopId;

    /**
     * @var string
     * @ORM\Column(name="field_name", type="string", nullable=false)
     */
    private $fieldName;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="PrestaShop\Module\CreateitAccordion\Entity\CreateitAccordionHeader", cascade={"persist", "remove"}, mappedBy="createitAccordion")
     */
    private $createitAccordionHeaders;

    /**
     * @ORM\OneToMany(targetEntity="PrestaShop\Module\CreateitAccordion\Entity\CreateitAccordionContent", cascade={"persist", "remove"}, mappedBy="createitAccordion")
     */
    private $createitAccordionContents;

    public function __construct()
    {

        $this->createdAt = new \DateTime();
        if (null === $this->getUpdatedAt()) {
            $this->updatedAt = new \DateTime();
        }

        $this->createitAccordionHeaders = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getShopId(): int
    {
        return $this->shopId;
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param int $shopId
     * @return CreateitAccordion
     */
    public function setShopId(int $shopId): CreateitAccordion
    {
        $this->shopId = $shopId;

        return $this;
    }

    /**
     * @param string $fieldName
     * @return CreateitAccordion
     */
    public function setFieldName(string $fieldName): CreateitAccordion
    {
        $this->fieldName = $fieldName;

        return $this;
    }

    /**
     * @param DateTime $createdAt
     * @return CreateitAccordion
     */
    public function setCreatedAt(DateTime $createdAt): CreateitAccordion
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @param DateTime $updatedAt
     * @return CreateitAccordion
     */
    public function setUpdatedAt(DateTime $updatedAt): CreateitAccordion
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCreateitAccordionHeaders()
    {
        return $this->createitAccordionHeaders;
    }

    /**
     * @param int $langId
     * @return CreateitAccordionHeader|null
     */
    public function getCreateitAccordionHeaderByLang(int $langId)
    {
        /**
         * @var $createitAccordionHeader CreateitAccordionHeader
         */
        foreach($this->createitAccordionHeaders as $createitAccordionHeader)
        {
            if($langId === $createitAccordionHeader->getLang()->getId()){
                return $createitAccordionHeader;
            }
        }
        return null;
    }

    public function getCreateitAccordionHeaderAll()
    {
        $results = [];

        /**
         * @var $createitAccordionHeader CreateitAccordionHeader
         */
        foreach($this->createitAccordionHeaders as $createitAccordionHeader) {
            $results[$createitAccordionHeader->getLang()->getId()]['content'] = $createitAccordionHeader->getContent();
        }

        return $results;
    }

    public function getCreateitAccordionContentAll()
    {
        $results = [];
        /**
         * @var $createitAccordionContent CreateitAccordionContent
         */
        foreach ($this->createitAccordionContents as $createitAccordionContent) {
            $results[$createitAccordionContent->getLang()->getId()]['content'] = $createitAccordionContent->getContent();
        }

        return $results;
    }

    /**
     * @param CreateitAccordionHeader $accordionHeader
     * @return $this
     */
    public function addCreateitAccordionHeaders(CreateitAccordionHeader $accordionHeader)
    {
        $accordionHeader->setCreateitAccordion($this);
        $this->createitAccordionHeaders->add($accordionHeader);

        return $this;
    }
}