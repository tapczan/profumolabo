<?php

declare(strict_types=1);

namespace PrestaShop\Module\CreateitProductfield2\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use PrestaShopBundle\Entity\Lang;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PrestaShop\Module\CreateitProductfield2\Repository\CreateitProductfield2Repository")
 */
class CreateitProductfield2
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="id_createit_productfield2", type="integer")
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
     * @var int
     *
     * @ORM\Column(name="id_product_linked", type="integer")
     */
    private $productIdLinked;

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
     * @return CreateitProductfield2
     */
    public function setId(int $id): CreateitProductfield2
    {
        $this->id = $id;

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
     * @return CreateitProductfield2
     */
    public function setCreatedAt(DateTime $createdAt): CreateitProductfield2
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
     * @return CreateitProductfield2
     */
    public function setUpdatedAt(DateTime $updatedAt): CreateitProductfield2
    {
        $this->updatedAt = $updatedAt;

        return $this;
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
     * @return CreateitProductfield2
     */
    public function setProductId(int $productId): CreateitProductfield2
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @return int
     */
    public function getProductIdLinked(): int
    {
        return $this->productIdLinked;
    }

    /**
     * @param int $productIdLinked
     * @return CreateitProductfield2
     */
    public function setProductIdLinked(int $productIdLinked): CreateitProductfield2
    {
        $this->productIdLinked = $productIdLinked;

        return $this;
    }
}