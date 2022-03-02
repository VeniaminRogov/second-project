<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\LanguageFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SidebarController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $doctrine
    )
    {
    }

    public function index(Request $request): Response
    {
        $user = $this->getUser();

        $categories = $this->doctrine->getRepository(Category::class)->findAll();
        return $this->render('sidebar/sidebar.html.twig', [
            'categories' => $categories,
            'user' => $user,
        ]);
    }
}