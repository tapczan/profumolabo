<?php

namespace PrestaShop\Module\CreateitCountdown\Form;

use PrestaShop\Module\CreateitCountdown\Entity\CreateitCountdown;
use PrestaShop\Module\CreateitCountdown\Repository\CreateitCountdownRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

class CreateitCountdownFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var CreateitCountdownRepository
     */
    private $createitCountdownRepository;

    public function __construct(CreateitCountdownRepository $createitCountdownRepository)
    {
        $this->createitCountdownRepository = $createitCountdownRepository;
    }

    public function getData($id)
    {
        return $this->defaultValuesDataCountdown();
    }

    public function getDefaultData()
    {
        return $this->defaultValuesDataCountdown();
    }

    private function defaultValuesDataCountdown() : array
    {
        $defaultAmountValue = null;
        $defaultBackgroundColor = null;
        $defaultBorderColor = null;
        $defaultTextColor = null;

        /**
         * @var $countdownAmountValue CreateitCountdown
         */
        $countdownAmountValue = $this->createitCountdownRepository->findSetting(CreateitCountdown::AMOUNT_VALUE);

        /**
         * @var $countdownBackgroundColor CreateitCountdown
         */
        $countdownBackgroundColor = $this->createitCountdownRepository->findSetting(CreateitCountdown::BACKGROUND_COLOR);

        /**
         * @var $countdownBorderColor CreateitCountdown
         */
        $countdownBorderColor = $this->createitCountdownRepository->findSetting(CreateitCountdown::BORDER_COLOR);

        /**
         * @var $countdownTextColor CreateitCountdown
         */
        $countdownTextColor = $this->createitCountdownRepository->findSetting(CreateitCountdown::TEXT_COLOR);

        if(!is_null($countdownAmountValue)){
            $defaultAmountValue = $countdownAmountValue->getValue();
        }

        if(!is_null($countdownBackgroundColor)){
            $defaultBackgroundColor = $countdownBackgroundColor->getValue();
        }

        if(!is_null($countdownBorderColor)){
            $defaultBorderColor = $countdownBorderColor->getValue();
        }

        if(!is_null($countdownTextColor)){
            $defaultTextColor = $countdownTextColor->getValue();
        }

        return [
            'amount_value' => $defaultAmountValue,
            'background_color' => $defaultBackgroundColor,
            'border_color' => $defaultBorderColor,
            'text_color' => $defaultTextColor
        ];
    }


}