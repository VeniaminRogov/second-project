<?php

namespace App\Model;

use App\Objects\AddToCartObject;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartModel
{
    private SessionInterface $session;
    public function __construct(
        RequestStack $requestStack
    )
    {
        $this->session = $requestStack->getSession();
    }


    public function addToCart(int $productId, int $quantity)
    {
        $cartObject = new AddToCartObject();

        $cartObject->setProductId($productId);
        $cartObject->setQuantity($quantity);

        $cart = $this->session->get('cart', []);

        if(!empty($cart)) {
            foreach ($cart as $value) {
                if ($value->getProductId() == $cartObject->getProductId()) {
                    $newQuantity = $value->getQuantity() + $cartObject->getQuantity();
                    $value->setQuantity($newQuantity);
                }
            }
        }
        $cart[] = $cartObject;
        $this->session->set('cart', $cart);
    }

    public function deleteFromCart(?int $productId)
    {
        $cart = $this->session->get('cart', []);
            foreach ($cart as $index => $value)
            {
                if($productId){
                    if($value->getProductId() == $productId)
                    {
                        unset($cart[$index]);
                        $this->session->set('cart', $cart);
                    }
                } else {
                    unset($cart[$index]);
                    $this->session->set('cart', $cart);
                }
            }

    }
}