<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Products;
use App\Services\CategoriesService;
use App\Services\ProductsService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $doctrine,
        private ProductsService $productsService,
        private CategoriesService $categoriesService
    )
    {
    }

    public function index(?int $id): Response
    {
        $products = $this->doctrine->getRepository(Products::class)->findAll();
        if($id)
        {
            $category = $this->categoriesService->checkCategoryId($id);
            $categoryProduct = $category->getProducts();

            return $this->render('store/index.html.twig', [
                'products' => $categoryProduct,
            ]);
        }

        return $this->render('store/index.html.twig', [
           'products' => $products,
        ]);
    }


    public function productPage(?string $slug): Response
    {
        $category = $this->doctrine->getRepository(Category::class)->findAll();

        
        return $this->render('store/product_page.html.twig', [
//            'product' => $product
        ]);
    }
}