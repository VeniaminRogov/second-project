<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesFormType;
use App\Services\CategoriesService;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoriesController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $manager,
        private PaginatorInterface $paginator,
        private CategoriesService $service,
        private ?int $id = null
    )
    {}

    public function index(): Response
    {
        $category = $this->manager->getRepository(Categories::class)->findAll();
        $pagination = $this->paginator->paginate(
            $category,
            1,
            10
        );
        return $this->render('products/index.html.twig', [
            'category' => $pagination
        ]);
    }

    public function createAndUpdate(Request $request): Response
    {
        $category = $this->service->checkCategoryId($this->id);

        $form = $this->createForm(CategoriesFormType::class, $category);
        $req = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $category = $form->getData();
            $category = $this->service->createAndUpdate($category);
        }

        return $this->renderForm('categories/form_categories.html.twig', [
            'form' => $form
        ]);
    }
}