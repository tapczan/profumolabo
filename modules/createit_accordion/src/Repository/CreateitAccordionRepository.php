<?php

declare(strict_types=1);

namespace PrestaShop\Module\CreateitAccordion\Repository;

use Doctrine\ORM\EntityRepository;

class CreateitAccordionRepository extends EntityRepository
{
//    public function findOneCustomFieldByFieldName($product, $shop, $fieldName)
//    {
//        return $this->createQueryBuilder('cf')
//            ->leftJoin('cf.createitProductsCustomfield', 'cp')
//            ->where('cf.productId = :productId')
//            ->andWhere('cf.shopeId = :shopeId')
//            ->andWhere('cf.shopeId = :shopeId')
//            ->andWhere('cp.fieldName = :fieldName')
//            ->setParameter('productId', $product)
//            ->setParameter('shopeId', $shop)
//            ->setParameter('fieldName', $fieldName)
//            ->getQuery()
//            ->getOneOrNullResult()
//            ;
//    }

    public function findOneByProductId($product_id)
    {

    }

}