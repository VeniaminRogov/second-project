<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Products;
use App\Entity\User;
use App\Form\AddToCartFormType;
use App\Objects\AddToCartObject;
use App\Services\CartService;
use App\Services\CategoriesService;
use App\Services\ProductsService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $doctrine,
        private ProductsService $productsService,
        private CategoriesService $categoriesService,
        private CartService $cartService,
        RequestStack $requestStack,
    )
    {
        $this->session = $requestStack->getSession();
    }

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


    public function productPage(?string $name, ?string $slug = null, Request $request): Response
    {
        $product = $this->doctrine->getRepository(Products::class)->findOneBy(['name' => $name]);

        $randProducts = $this->doctrine->getRepository(Products::class)->getRandomEntitiesBySlug(4, $slug, $product);

        $addToCart = new AddToCartObject();
        $form = $this->createForm(AddToCartFormType::class, $addToCart);
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $quantity = $form->get('quantity')->getData();
            $this->session->set('product', $product->getId());

            $product = $this->session->get('product');

            return $this->redirectToRoute('cart', [
               'session_product' => $product,
                'quantity' => $quantity
            ]);
        }

        return $this->render('store/product_page.html.twig', [
            'product' => $product,
            'category_products' => $randProducts,
            'form' => $form->createView()
        ]);
    }
}