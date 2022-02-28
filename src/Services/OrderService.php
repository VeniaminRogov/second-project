<?php

namespace App\Services;

use App\Entity\OrderItems;
use App\Entity\Orders;
use App\Entity\Products;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

class OrderService
{
    private $session;
    private $doctrine;
    public function __construct(RequestStack $requestStack, ManagerRegistry $doctrine,)
    {
        $this->session = $requestStack->getSession();
        $this->doctrine = $doctrine->getManager();
    }

    public function setOrder($user)
    {
        $cart = $this->session->get('cart',[]);
        $order = new Orders();
        $order->setUser($user);
        $orderItems = new OrderItems();
        $orderItems->setCount(count($cart));
        $orderItems->setOrderId($order);
        foreach ($cart as $value)
        {
            $id = $value->getProductId();
            $quantity = $value->getQuantity();
            $product = $this->doctrine->getRepository(Products::class)->find($id);
            if($quantity > $product->getQuantity())
            {
                return;
            }
            $newQuantity = $product->getQuantity() - $quantity;
            $product->setQuantity($newQuantity);
            if ($product->getQuantity() <= 0)
            {
                $product->setIsAvailable(false);
            }
            $orderItems->addProduct($product);
            $orderItems->setCount($quantity);
        }

        $this->doctrine->persist($orderItems);
        $this->doctrine->flush();
    }

    public function removeOrder($order)
    {
        $order = $this->doctrine->getRepository(Orders::class)->find($order);

        $this->doctrine->remove($order);
        $this->doctrine->flush();
    }
}