<?php

namespace App\Repository;

use App\Entity\Categories;
use App\Entity\Products;
use App\Objects\SortCategoryObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }

    public function sortByCategories(SortCategoryObject $data)
    {
        if(!$data){
            return $this->findAll();
        }

        $req = $this->createQueryBuilder('p');
        $req->leftJoin('p.category_id', 'pc');
        if($data->getCategory())
        {
            $req->andWhere('pc.id = :category')
                ->setParameter('category', $data->getCategory());
        }
//        dd($req->getQuery()->getArrayResult());
        return $req->getQuery()->getResult();
    }

    // /**
    //  * @return Products[] Returns an array of Products objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Products
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}