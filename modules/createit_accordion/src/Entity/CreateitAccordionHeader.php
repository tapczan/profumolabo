<?php

declare(strict_types=1);

namespace PrestaShop\Module\CreateitAccordion\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use PrestaShopBundle\Entity\Lang;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PrestaShop\Module\CreateitAccordion\Repository\CreateitAccordionHeaderRepository")
 */
class CreateitAccordionHeader
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id_createit_accordion_header", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var CreateitAccordion
     *
     * @ORM\ManyToOne(targetEntity="PrestaShop\Module\CreateitAccordion\Entity\CreateitAccordion")
     * @ORM\JoinColumn(name="id_createit_accordion", referencedColumnName="id_createit_accordion", nullable=false)
     */
    private $createitAccordion;

    /**
     * @var Lang
     * @ORM\ManyToOne(targetEntity="PrestaShopBundle\Entity\Lang")
     * @ORM\JoinColumn(name="id_lang", referencedColumnName="id_lang", nullable=false, onDelete="CASCADE")
     */
    private $lang;

    /**
     * @var string
     * @ORM\Column(name="content", type="text", nullable=false)
     */
    private $content;

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

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        if (null === $this->getUpdatedAt()) {
            $this->updatedAt = new \DateTime();
        }
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return CreateitAccordion
     */
    public function getCreateitAccordion(): CreateitAccordion
    {
        return $this->createitAccordion;
    }

    /**
     * @return Lang
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
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
     * @param CreateitAccordion $createitAccordion
     * @return CreateitAccordionHeader
     */
    public function setCreateitAccordion(CreateitAccordion $createitAccordion): CreateitAccordionHeader
    {
        $this->createitAccordion = $createitAccordion;

        return $this;
    }

    /**
     * @param Lang $lang
     * @return $this
     */
    public function setLang(Lang $lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * @param string $content
     * @return CreateitAccordionHeader
     */
    public function setContent(string $content): CreateitAccordionHeader
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @param DateTime $createdAt
     * @return CreateitAccordion
     */
    public function setCreatedAt(DateTime $createdAt): CreateitAccordionHeader
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @param DateTime $updatedAt
     * @return CreateitAccordion
     */
    public function setUpdatedAt(DateTime $updatedAt): CreateitAccordionHeader
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}