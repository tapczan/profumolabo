<?php

namespace PrestaShop\Module\CreateITCustomField\Form;

use PrestaShop\Module\CreateITCustomField\Entity\CreateitProductCustomfield;
use PrestaShop\Module\CreateITCustomField\Repository\CreateitProductCustomfieldRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;

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

    public function __construct(
        CreateitProductCustomfieldRepository $createitProductCustomfieldRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->createitProductCustomfieldRepository = $createitProductCustomfieldRepository;
        $this->entityManager = $entityManager;
    }

    public function create(array $data)
    {

        $customField = new CreateitProductCustomfield();
        $customField->setFieldName($data['field_name']);
        $customField->setFieldType($data['field_type']);
        $customField->setLabelName($data['label_name']);

        $this->entityManager->persist($customField);

        $this->entityManager->flush();

        return $customField->getId();

    }

    public function update($id, array $data)
    {


        $customField = $this->createitProductCustomfieldRepository->findOneById($id);

        $customField->setFieldName($data['field_name']);
        $customField->setFieldType($data['field_type']);
        $customField->setLabelName($data['label_name']);

        $this->entityManager->flush();

        return $customField->getId();

    }
}