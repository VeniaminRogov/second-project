<?php

namespace App\Services;


use App\Entity\Categories;
use Doctrine\Persistence\ManagerRegistry;

class CategoriesService
{
    private $doctrine;
    public function __construct(
        ManagerRegistry $doctrine
    )
    {
        $this->doctrine = $doctrine->getManager();
    }

    public function checkCategoryId(?int $id = null)
    {
        if(!$id){
            return $id;
        }
        return $this->doctrine->getRepository(Categories::class)->find($id);
    }

    public function createAndUpdate(Categories $categories): Categories
    {
        $this->doctrine->persist($categories);
        $this->doctrine->flush();
        return $categories;
    }
}