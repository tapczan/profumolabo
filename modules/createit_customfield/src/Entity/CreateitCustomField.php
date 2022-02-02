<?php

namespace PrestaShop\Module\CreateitCustomField\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table()
 * @ORM\Entity()
 */

class CreateitCustomField
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id_createit_customfield", type="integer")
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
     * @ORM\Column(name="id_shop", type="integer")
     */
    private $shopeId;

    /**
     * @var int
     *
     * @ORM\Column(name="id_lang", type="integer")
     */
    private $langId;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    /**
     * @return int
     */
    public function getShopeId()
    {
        return $this->shopeId;
    }

    /**
     * @param int $shopeId
     */
    public function setShopeId($shopeId)
    {
        $this->shopeId = $shopeId;
    }

    /**
     * @return int
     */
    public function getLangId()
    {
        return $this->langId;
    }

    /**
     * @param int $langId
     */
    public function setLangId($langId)
    {
        $this->langId = $langId;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id_product' => $this->getProductId(),
            'id_createit_customfield' => $this->getId(),
            'id_shop' => $this->getShopeId(),
            'id_lang' => $this->getLangId(),
            'content' => $this->getContent(),
            'created_at' => $this->createdAt->format(\DateTime::ATOM),
            'updatedAt' => $this->createdAt->format(\DateTime::ATOM)
        ];
    }

}