<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findArr()
    {
        $queryBuilder = $this->createQueryBuilder('product');

        $queryBuilder
            ->select(
            'product.id',
            'product.name',
            'product.description',
            'product.price',
            'product.quantity',
            'product.isAvailable',
            'product.createdAt',
            'product.updatedAt',
            'category.Name as categoryName',
            )
            ->leftJoin('product.category', 'category')
        ;

        return $queryBuilder->getQuery()->getArrayResult();
    }

    public function findArrById($id)
    {
        $queryBuilder = $this->createQueryBuilder('product');

        $product = $queryBuilder->andWhere('product.id = :id')->setParameter('id', $id)->getQuery()->getResult();

        dd($product);
    }
}