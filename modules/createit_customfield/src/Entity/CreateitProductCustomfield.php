<?php

namespace PrestaShop\Module\CreateITCustomField\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PrestaShop\Module\CreateITCustomField\Repository\CreateitProductCustomfieldRepository")
 */
class CreateitProductCustomfield
{

    public const TEXT_TYPE = 0;

    public const TEXT_AREA_TYPE = 1;

    public const SELECT_BOX_TYPE = 2;

    public const RADIO_BUTTON_TYPE = 3;

    public const CHECK_BOX_TYPE = 4;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id_createit_products_customfield", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="field_name", type="text", unique=true)
     */
    private $fieldName;

    /**
     * @var int
     * @ORM\Column(name="field_type", type="integer")
     */
    private $fieldType;

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
     * @ORM\OneToMany(targetEntity="PrestaShop\Module\CreateITCustomField\Entity\CreateitCustomfield", cascade={"persist", "remove"}, mappedBy="createitProductsCustomfield")
     */
    private $createitCustomfield;

    /**
     * @ORM\OneToMany(targetEntity="PrestaShop\Module\CreateITCustomField\Entity\CreateitProductCustomfieldLabelLang", cascade={"persist", "remove"}, mappedBy="createitProductCustomfield")
     */
    private $createitProductCustomfieldLabelLangs;

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
     * @return string|null
     */
    public function getFieldName(): ?string
    {
        return $this->fieldName;
    }

    /**
     * @param string $fieldName
     */
    public function setFieldName(string $fieldName): void
    {
        $this->fieldName = $fieldName;
    }

    /**
     * @return int|null
     */
    public function getFieldType(): ?int
    {
        return $this->fieldType;
    }

    /**
     * @param int $fieldType
     */
    public function setFieldType(int $fieldType): void
    {
        $this->fieldType = $fieldType;
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


    public function __construct()
    {
        $this->createdAt = new \DateTime();
        if (null === $this->getUpdatedAt()) {
            $this->updatedAt = new \DateTime();
        }

        $this->createitProductCustomfieldLabelLangs = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getCreateitCustomfield()
    {
        return $this->createitCustomfield;
    }

    /**
     * @param mixed $createitCustomfield
     */
    public function setCreateitCustomfield($createitCustomfield): void
    {
        $this->createitCustomfield = $createitCustomfield;
    }

    /**
     * @return ArrayCollection
     */
    public function getCreateitProductCustomfieldLabelLang()
    {
        return $this->createitProductCustomfieldLabelLangs;
    }

    public function getCreateitProductCustomfieldContentByLang(int $langId)
    {
        /**
         * @var $field CreateitCustomfield
         */
        foreach ($this->createitCustomfield as $field)
        {
            if($langId === $field->getLangId()){
                return $field;
            }
        }
        return null;
    }

    public function getCreateitProductCustomfieldContentByProductAndLang(int $productId, int $langId)
    {
        /**
         * @var $field CreateitCustomfield
         */
        foreach ($this->createitCustomfield as $field)
        {
            if($productId === $field->getProductId() && $langId === $field->getLangId()){
                return $field;
            }
        }
        return null;
    }

    /**
     * @param int $langId
     * @return mixed|null
     */
    public function getCreateitProductCustomfieldLabelLangByLangId(int $langId)
    {
        /**
         * @var $createitProductCustomfieldLabelLang CreateitProductCustomfieldLabelLang
         */
        foreach ($this->createitProductCustomfieldLabelLangs as $createitProductCustomfieldLabelLang){
            if($langId === $createitProductCustomfieldLabelLang->getLang()->getId()){
                return $createitProductCustomfieldLabelLang;
            }
        }

        return null;
    }

    public function getCreateitProductCustomfieldLabelAllLangContent()
    {
        $result = [];

        /**
         * @var $createitProductCustomfieldLabelLang CreateitProductCustomfieldLabelLang
         */
        foreach ($this->createitProductCustomfieldLabelLangs as $createitProductCustomfieldLabelLang){
            $result[$createitProductCustomfieldLabelLang->getLang()->getId()]['content'] = $createitProductCustomfieldLabelLang->getContent();
        }

        return $result;
    }

    /**
     * @param CreateitProductCustomfieldLabelLang $createitProductCustomfieldLabelLang
     * @return $this
     */
    public function addCreateitProductCustomfieldLabelLang(CreateitProductCustomfieldLabelLang $createitProductCustomfieldLabelLang)
    {
        $createitProductCustomfieldLabelLang->setCreateitProductCustomfield($this);
        $this->createitProductCustomfieldLabelLangs->add($createitProductCustomfieldLabelLang);

        return $this;
    }

    public function toArray()
    {
        return [
            'id_createit_products_customfield' => $this->getId(),
            'field_name' => $this->getFieldName(),
            'field_type' => $this->getFieldType(),
            'created_at' => $this->createdAt->format(\DateTime::ATOM),
            'updated_at' => $this->createdAt->format(\DateTime::ATOM)
        ];
    }
}