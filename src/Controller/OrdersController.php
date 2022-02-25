<?php

namespace App\Controller;

use App\Entity\Products;
use App\Services\CartService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class OrdersController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $doctrine,
        private CartService $cartService
    )
    {
    }

    public function cart(RequestStack $requestStack): Response
    {
        $session = $requestStack->getSession();
        $cart = $session->get('cart', []);
        $productsArr = [];

        foreach ($cart as $value)
        {
            $id = $value->getProductId();
            $quantity = $value->getQuantity();

            $product = $this->doctrine->getRepository(Products::class)->find($id);
            $product->setQuantity($quantity);

            $productsArr[] = $product;
        }
        $count = count($productsArr);

        return $this->render('cart/index.html.twig', [
            'products' => $productsArr,
            'count' => $count
        ]);
    }

}