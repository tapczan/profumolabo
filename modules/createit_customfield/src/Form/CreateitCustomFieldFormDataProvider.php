<?php

namespace PrestaShop\Module\CreateITCustomField\Form;

use PrestaShop\Module\CreateITCustomField\Entity\CreateitProductCustomfield;
use PrestaShop\Module\CreateITCustomField\Repository\CreateitProductCustomfieldRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

class CreateitCustomFieldFormDataProvider implements FormDataProviderInterface
{

    /**
     * @var CreateitProductCustomfieldRepository
     */
    private $createitProductCustomfieldRepository;

    public function __construct(CreateitProductCustomfieldRepository $createitProductCustomfieldRepository)
    {
        $this->createitProductCustomfieldRepository = $createitProductCustomfieldRepository;
    }

    /**
     * @param int $id
     * @return mixed|object|null
     */
    public function getData($id)
    {

        /**
         * @var $customField CreateitProductCustomfield
         */
        $customField = $this->createitProductCustomfieldRepository->findOneById($id);

        return [
            'field_name' => $customField->getFieldName(),
            'field_type' => $customField->getFieldType(),
            'label_name' => $customField->getLabelName()
        ];

    }

    /**
     * @return string[]
     */
    public function getDefaultData()
    {


        return [
            'field_name' => '',
            'field_type' => '',
            'label_name' => ''
        ];
    }
}