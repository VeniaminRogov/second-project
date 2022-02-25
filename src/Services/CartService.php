<?php

namespace App\Services;

use App\Entity\OrderItems;
use App\Entity\Orders;
use App\Entity\Products;
use App\Entity\User;
use App\Objects\AddToCartObject;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class CartService
{
    public function __construct(
        private RequestStack $requestStack,
        private ManagerRegistry $doctrine
    )
    {}

    public function addToCart(int $productId, int $quantity)
    {
        $cartObject = new AddToCartObject();

        $cartObject->setProductId($productId);
        $cartObject->setQuantity($quantity);

        $session = $this->requestStack->getSession();

        $cart = $session->get('cart', []);

        if(empty($cart))
        {
            $cart[] = $cartObject;
        } else {
            foreach ($cart as $value)
            {
                if($value->getProductId() == $cartObject->getProductId())
                {
                    $newQuantity = $value->getQuantity() + $cartObject->getQuantity();
                    $value->setQuantity($newQuantity);
                } else {
                    $cart[] = $cartObject;
                }
                $session->set('cart', $cart);
            }
        }
    }

    public function deleteFromCart($cart)
    {

        foreach ($cart as $value)
        {
            if($value->getProduictId())
            {
//                $this->requestStack->getSession()->;
            }
        }
    }
}