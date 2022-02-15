<?php

namespace PrestaShop\Module\CreateITCustomField\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class CreateitCustomfieldRepository extends EntityRepository
{

    public function findOneCustomFieldByFieldName($product, $shop, $fieldName)
    {
        return $this->createQueryBuilder('cf')
            ->leftJoin('cf.createitProductsCustomfield', 'cp')
            ->where('cf.productId = :productId')
            ->andWhere('cf.shopeId = :shopeId')
            ->andWhere('cf.shopeId = :shopeId')
            ->andWhere('cp.fieldName = :fieldName')
            ->setParameter('productId', $product)
            ->setParameter('shopeId', $shop)
            ->setParameter('fieldName', $fieldName)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}