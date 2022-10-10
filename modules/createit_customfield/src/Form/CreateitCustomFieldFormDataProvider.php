<?php

namespace PrestaShop\Module\CreateITCustomField\Form;

use PrestaShop\Module\CreateITCustomField\Entity\CreateitCustomfield;
use PrestaShop\Module\CreateITCustomField\Entity\CreateitProductCustomfield;
use PrestaShop\Module\CreateITCustomField\Entity\CreateitProductCustomfieldLabelLang;
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

        $data['field_name'] = $customField->getFieldName();
        $data['field_type'] = $customField->getFieldType();

        /**
         * @var $labelLang CreateitProductCustomfieldLabelLang
         */
        foreach ($customField->getCreateitProductCustomfieldLabelLang() as $labelLang)
        {
            $data['label_name'][$labelLang->getLang()->getId()] =  $labelLang->getContent();
        }

        return $data;
    }

    /**
     * @return string[]
     */
    public function getDefaultData()
    {
        return [
            'field_name' => '',
            'field_type' => '',
        ];
    }
}