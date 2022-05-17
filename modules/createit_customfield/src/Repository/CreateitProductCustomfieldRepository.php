<?php

namespace PrestaShop\Module\CreateITCustomField\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class CreateitProductCustomfieldRepository extends EntityRepository
{
    public function findAllFieldsWithLabel($id_lang)
    {
        return $this->createQueryBuilder('cf')
            ->select(['cf.id', 'cf.fieldName', 'cf.fieldType','cfl.content'])
            ->leftJoin('cf.createitProductCustomfieldLabelLangs','cfl')
            ->andWhere('cfl.lang = :lang')
            ->setParameter('lang', $id_lang)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllFieldsByProductId($product_id, $id_lang)
    {
        return $this->createQueryBuilder('cp')
            ->leftJoin('cp.createitProductCustomfieldLabelLangs','cfl')
            ->andWhere('cfl.lang = :lang')
            ->setParameter('lang', $id_lang)
            ->getQuery()
            ->getResult()
            ;
    }
}