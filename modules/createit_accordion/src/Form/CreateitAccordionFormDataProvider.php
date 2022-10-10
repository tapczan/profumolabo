<?php

declare(strict_types=1);

namespace PrestaShop\Module\CreateitAccordion\Form;

use PrestaShop\Module\CreateitAccordion\Entity\CreateitAccordion;
use PrestaShop\Module\CreateitAccordion\Entity\CreateitAccordionHeader;
use PrestaShop\Module\CreateitAccordion\Repository\CreateitAccordionRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

class CreateitAccordionFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var CreateitAccordionRepository
     */
    private $repository;

    public function __construct(CreateitAccordionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int $createitAccordionId
     * @return array
     */
    public function getData($createitAccordionId): array
    {
        /**
         * @var $createitAccordion CreateitAccordion
         */
        $createitAccordion = $this->repository->findOneById($createitAccordionId);

        $data = [
            'field_name' => $createitAccordion->getFieldName()
        ];

        /**
         * @var $createitAccordionHeader CreateitAccordionHeader
         */
        foreach ($createitAccordion->getCreateitAccordionHeaders() as $createitAccordionHeader){
            $data['label_name'][$createitAccordionHeader->getLang()->getId()] = $createitAccordionHeader->getContent();
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getDefaultData():array
    {
        return [
            'field_name' => '',
            'label_name' => []
        ];
    }
}