<?php

namespace App\Services;

use App\Entity\OrderItems;
use App\Entity\Orders;
use App\Entity\Products;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

class CartService
{
    private $doctrine;
    public function __construct(
        ManagerRegistry $doctrine,
        private Security $security
    )
    {
        $this->doctrine = $doctrine->getManager();
    }

    public function addToCart(Products $product)
    {
        $user = $this->security->getUser();

        $order = new Orders();
        $order->setUser($user);

        $cartItem = new OrderItems();
        $cartItem->addProduct($product);

        if ($cartItem->getCount() == 0)
        {
            $cartItem->setCount(1);
        }

        $cartItem->setCount($cartItem->getCount() + 1);

        $order->addOrderItem($cartItem);

        $this->doctrine->persist($order);
        $this->doctrine->flush();

        return $order;
    }
}