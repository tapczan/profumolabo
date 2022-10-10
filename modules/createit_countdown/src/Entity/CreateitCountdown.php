<?php

declare(strict_types=1);

namespace PrestaShop\Module\CreateitCountdown\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PrestaShop\Module\CreateitCountdown\Repository\CreateitCountdownRepository")
 */
class CreateitCountdown
{

    public const AMOUNT_VALUE = 'amount_value';
    public const BACKGROUND_COLOR = 'background_color';
    public const BORDER_COLOR = 'border_color';
    public const TEXT_COLOR = 'text_color';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id_createit_countdown", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var string
     * @ORM\Column(name="setting", type="string", length=255)
     */
    private $setting;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updated_at;

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
    public function getSetting(): ?string
    {
        return $this->setting;
    }

    /**
     * @param string $setting
     */
    public function setSetting(string $setting): void
    {
        $this->setting = $setting;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updated_at;
    }

    /**
     * @param \DateTime $updated_at
     */
    public function setUpdatedAt(\DateTime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }

    public function __construct()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    public function setAmountValue($data)
    {
        $this->setSetting(CreateitCountdown::AMOUNT_VALUE);
        $this->setValue((string)$data['amount_value']);
    }

    public function setBackgroundColor($data)
    {
        $this->setSetting(CreateitCountdown::BACKGROUND_COLOR);
        $this->setValue($data['background_color']);
    }

    public function setBorderColor($data)
    {
        $this->setSetting(CreateitCountdown::BORDER_COLOR);
        $this->setValue($data['border_color']);
    }

    public function setTextColor($data)
    {
        $this->setSetting(CreateitCountdown::TEXT_COLOR);
        $this->setValue($data['text_color']);
    }
}