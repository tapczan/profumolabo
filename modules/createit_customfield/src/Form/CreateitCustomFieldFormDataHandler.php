<?php

namespace PrestaShop\Module\CreateITCustomField\Form;

use PrestaShop\Module\CreateITCustomField\Entity\CreateitProductCustomfield;
use PrestaShop\Module\CreateITCustomField\Entity\CreateitProductCustomfieldLabelLang;
use PrestaShop\Module\CreateITCustomField\Repository\CreateitProductCustomfieldRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\PrestaShop\Core\Language\LanguageRepositoryInterface;

class CreateitCustomFieldFormDataHandler implements FormDataHandlerInterface
{

    /**
     * @var CreateitProductCustomfieldRepository
     */
    private $createitProductCustomfieldRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LanguageRepositoryInterface
     */
    private $langRepository;

    /**
     * @param CreateitProductCustomfieldRepository $createitProductCustomfieldRepository
     * @param EntityManagerInterface $entityManager
     * @param LanguageRepositoryInterface $langRepository
     */
    public function __construct(
        CreateitProductCustomfieldRepository $createitProductCustomfieldRepository,
        EntityManagerInterface $entityManager,
        LanguageRepositoryInterface $langRepository
    )
    {
        $this->langRepository = $langRepository;
        $this->createitProductCustomfieldRepository = $createitProductCustomfieldRepository;
        $this->entityManager = $entityManager;
    }

    public function create(array $data)
    {
        $customField = new CreateitProductCustomfield();
        $customField->setFieldName($data['field_name']);
        $customField->setFieldType($data['field_type']);

        foreach ($data['label_name'] as $langId => $langLabel) {
            $lang = $this->langRepository->findOneById($langId);

            $fieldLabelLang = new CreateitProductCustomfieldLabelLang();
            $fieldLabelLang->setLang($lang);
            $fieldLabelLang->setContent($langLabel);

            $customField->addCreateitProductCustomfieldLabelLang($fieldLabelLang);
        }

        $this->entityManager->persist($customField);
        $this->entityManager->flush();

        return $customField->getId();
    }

    public function update($id, array $data)
    {
        $customField = $this->createitProductCustomfieldRepository->findOneById($id);

        /**
         * @var $customField CreateitProductCustomfield
         */
        $customField->setFieldName($data['field_name']);
        $customField->setFieldType($data['field_type']);

        foreach ($data['label_name'] as $langId => $langLabel) {
            $lang = $customField->getCreateitProductCustomfieldLabelLangByLangId($langId);

            if(null === $langLabel) {
                continue;
            }
            $lang->setContent($langLabel);
        }

        $this->entityManager->flush();

        return $customField->getId();
    }
}