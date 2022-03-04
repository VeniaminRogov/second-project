<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Products;
use App\Form\AddToCartFormType;
use App\Model\CartModel;
use App\Objects\AddToCartObject;
use App\Objects\ProductDTO;
use App\Services\CategoriesService;
use App\Services\ProductsService;
use App\Services\RedisService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class StoreController extends AbstractController
{
    public function __construct(
        private ManagerRegistry   $doctrine,
        private ProductsService   $productsService,
        private CategoriesService $categoriesService,
        private CartModel         $cartService,
        private RedisService      $redisService,
        private ProductDTO        $DTO,
    )
    {}

    public function index(?string $slug): Response
    {
        $products = $this->doctrine->getRepository(Products::class)->findAll();

        if($slug)
        {
            $categoryBySlug = $this->doctrine->getRepository(Category::class)->findBySlug($slug);
            $categoryProduct = $categoryBySlug->getProducts();

            return $this->render('store/index.html.twig', [
                'products' => $categoryProduct,
            ]);
        }

        return $this->render('store/index.html.twig', [
           'products' => $products,
        ]);
    }


    public function productPage(?int $id, ?string $slug = null, Request $request): Response
    {
        $product = $this->redisService->cacheProduct($id);

        $randProducts = $this->doctrine->getRepository(Products::class)->getRandomEntitiesBySlug(4, $slug, $product->id);




        $addToCart = new AddToCartObject();
        $form = $this->createForm(AddToCartFormType::class, $addToCart);
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $quantity = $form->get('quantity')->getData();
            $this->cartService->addToCart($product->id, $quantity);
        }

        return $this->render('store/product_page.html.twig', [
            'product' => $product,
            'category_products' => $randProducts,
            'form' => $form->createView()
        ]);
    }
}