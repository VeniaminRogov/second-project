<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Products;
use App\Services\ProductsService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $doctrine,
        private ProductsService $productsService,
    )
    {
    }

    public function index(): Response
    {
        $products = $this->doctrine->getRepository(Products::class)->findAll();
        $categories = $this->doctrine->getRepository(Category::class)->findAll();


        return $this->render('store/index.html.twig', [
           'products' => $products,
            'categories' => $categories
        ]);
    }

    public function productPage(?int $id): Response
    {
        $product = $this->productsService->checkProductId($id);

        dd($product);

        return $this->render('store/product_page.html.twig', [
            'product' => $product
        ]);
    }
}