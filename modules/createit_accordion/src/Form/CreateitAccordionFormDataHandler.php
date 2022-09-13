<?php

declare(strict_types=1);

namespace PrestaShop\Module\CreateitAccordion\Form;

use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\Module\CreateitAccordion\Entity\CreateitAccordion;
use PrestaShop\Module\CreateitAccordion\Entity\CreateitAccordionHeader;
use PrestaShop\Module\CreateitAccordion\Repository\CreateitAccordionRepository;
use PrestaShop\PrestaShop\Adapter\Entity\Context;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;
use PrestaShop\PrestaShop\Core\Language\LanguageRepositoryInterface;

class CreateitAccordionFormDataHandler implements FormDataHandlerInterface
{
    /**
     * @var CreateitAccordionRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LanguageRepositoryInterface
     */
    private $languageRepository;


    public function __construct(
        CreateitAccordionRepository $repository,
        LanguageRepositoryInterface $languageRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->languageRepository = $languageRepository;
    }

    public function create(array $data)
    {
        $createitAccordion = new CreateitAccordion();
        $createitAccordion->setFieldName($data['field_name']);
        foreach ($data['label_name'] as $langId => $langContent) {
            $lang = $this->languageRepository->findOneById($langId);
            $createitAccordionHeader = new CreateitAccordionHeader();
            $createitAccordionHeader->setContent($langContent);
            $createitAccordionHeader->setLang($lang);

            $createitAccordion->addCreateitAccordionHeaders($createitAccordionHeader);
        }
        $context = Context::getContext();
        $createitAccordion->setShopId($context->shop->id);

        $this->entityManager->persist($createitAccordion);
        $this->entityManager->flush();

        return $createitAccordion->getId();
    }

    public function update($id, array $data)
    {
        /**
         * @var $createitAccordion CreateitAccordion
         */
        $createitAccordion = $this->repository->findOneById($id);
        $createitAccordion->setFieldName($data['field_name']);

        foreach ($data['label_name'] as $langId => $content) {
            $createitAccordionHeader = $createitAccordion->getCreateitAccordionHeaderByLang($langId);
            if(null === $createitAccordionHeader){
                continue;
            }
            $createitAccordionHeader->setContent($content);
        }
        $this->entityManager->flush();

        return $createitAccordion->getId();
    }
}