<?php

declare(strict_types=1);

namespace PrestaShop\Module\CreateitInspirationfield\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use PrestaShopBundle\Entity\Lang;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PrestaShop\Module\CreateitInspirationfield\Repository\CreateitInspirationfieldRepository")
 */
class CreateitInspirationfield
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="id_createit_inspirationfield", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_product", type="integer")
     */
    private $productId;

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

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     * @return CreateitInspirationfield
     */
    public function setProductId(int $productId): CreateitInspirationfield
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @return Lang
     */
    public function getLang(): Lang
    {
        return $this->lang;
    }

    /**
     * @param Lang $lang
     * @return CreateitInspirationfield
     */
    public function setLang(Lang $lang): CreateitInspirationfield
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return CreateitInspirationfield
     */
    public function setContent(string $content): CreateitInspirationfield
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     * @return CreateitInspirationfield
     */
    public function setCreatedAt(DateTime $createdAt): CreateitInspirationfield
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     * @return CreateitInspirationfield
     */
    public function setUpdatedAt(DateTime $updatedAt): CreateitInspirationfield
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}