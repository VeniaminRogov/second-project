<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SidebarController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $doctrine
    )
    {
    }

    public function index(): Response
    {
        $categories = $this->doctrine->getRepository(Category::class)->findAll();

        return $this->render('components/sidebar/sidebar.html.twig', [
            'categories' => $categories
        ]);
    }
}