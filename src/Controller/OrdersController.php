<?php

namespace App\Controller;

use App\Entity\OrderItems;
use App\Entity\Orders;
use App\Entity\Products;
use App\Model\CartModel;
use App\Services\OrderService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class OrdersController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $doctrine,
        private CartModel $cartService,
        private OrderService $orderService,
    )
    {
    }

    public function createOrder(): Response
    {
        $user = $this->getUser();
        $this->orderService->setOrder($user);

        return $this->redirectToRoute('order');
    }

    public function orders(): Response
    {
        $ordersList = $this->doctrine->getRepository(Orders::class)->findAll();

        return $this->render('order/index.html.twig', [
            'orders' => $ordersList
        ]);
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

    public function deleteFromCart(?int $id): RedirectResponse
    {
        $this->cartService->deleteFromCart($id);

        return $this->redirectToRoute('cart');
    }


}