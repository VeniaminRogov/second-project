<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class OrdersController extends AbstractController
{
    public function index(): Response
    {


        return $this->render('cart/index.html.twig');
    }

}