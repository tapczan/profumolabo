<?php

namespace PrestaShop\Module\CreateitCountdown\Form;

use Configuration as ConfigurationLegacy;
use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\Module\CreateitCountdown\Entity\CreateitCountdown;
use PrestaShop\Module\CreateitCountdown\Repository\CreateitCountdownRepository;
use PrestaShop\PrestaShop\Adapter\Entity\Context;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;

class CreateitCountdownFormDataHandler implements FormDataHandlerInterface
{
    /**
     * @var CreateitCountdownRepository
     */
    private $createitCountdownRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        CreateitCountdownRepository $createitCountdownRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->createitCountdownRepository = $createitCountdownRepository;
        $this->entityManager = $entityManager;
    }

    public function create(array $data)
    {
        return $this->touchCountdownValues(null, $data);
    }

    public function update($id, array $data)
    {
        return $this->touchCountdownValues($id, $data);
    }

    private function touchCountdownValues($id, array $data)
    {
        $countdownBackgroundValue = $this->createitCountdownRepository->findSetting(CreateitCountdown::BACKGROUND_COLOR);
        $countdownBorderColor = $this->createitCountdownRepository->findSetting(CreateitCountdown::BORDER_COLOR);
        $countdownTextColor = $this->createitCountdownRepository->findSetting(CreateitCountdown::TEXT_COLOR);

        if(is_null($countdownBackgroundValue)){
            $countdownBackgroundValue = new CreateitCountdown();
        }

        if(is_null($countdownBorderColor)){
            $countdownBorderColor = new CreateitCountdown();
        }

        if(is_null($countdownTextColor)){
            $countdownTextColor = new CreateitCountdown();
        }

        ConfigurationLegacy::updateValue(
            'PS_SHIPPING_FREE_PRICE',
            (string)$data['amount_value'],
            false,
            (int)Context::getContext()->shop->id_shop_group,
            (int)Context::getContext()->shop->id
        );

        $countdownBackgroundValue->setBackgroundColor($data);
        $countdownBorderColor->setBorderColor($data);
        $countdownTextColor->setTextColor($data);

        $this->entityManager->persist($countdownBackgroundValue);
        $this->entityManager->persist($countdownBorderColor);
        $this->entityManager->persist($countdownTextColor);
        $this->entityManager->flush();

        return $countdownBackgroundValue->getId();
    }
}