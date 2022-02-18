<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Products;
use App\Form\ProductsFormType;
use App\Form\SortCategoryFormType;
use App\Objects\SortCategoryObject;
use App\Services\ProductsService;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductsController extends AbstractController
{

    public function __construct(
        private ManagerRegistry $manager,
        private PaginatorInterface $paginator,
        private ProductsService $productsService,
    )
    {}

    public function index(Request $request): Response
    {
        $sortObject = new SortCategoryObject();
        $form = $this->createForm(SortCategoryFormType::class, $sortObject);

        $form->handleRequest($request);

        $productsByCategories = $this->manager->getRepository(Products::class)->sortByCategories($sortObject);
        $products = $this->manager->getRepository(Products::class)->findAll();

        if ($form->isSubmitted())
        {
            $pagination = $this->paginator->paginate(
                $productsByCategories,
                $sortObject->getPage(),
                5
            );
            return $this->render('products/index.html.twig', [
                'sortForm' => $form->createView(),
                'pagination' => $pagination
            ]);
        }

        $pagination = $this->paginator->paginate(
            $products,
            $sortObject->getPage(),
            5
        );

        return $this->render('products/index.html.twig', [
            'sortForm' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }

    public function createAndUpdate(Request $request, ?int $id): Response
    {
        $products = $this->productsService->checkProductId($id);

        $form = $this->createForm(ProductsFormType::class, $products);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $products = $form->getData();
            $image = $form->get('image')->getData();
            $products = $this->productsService->createAndUpdate($products, $image);
            return $this->redirectToRoute('manager_edit_products', [
                'id' => $products->getId()
            ]);
        }

        return $this->renderForm('products/form_products.html.twig', [
            'form' => $form,
        ]);
    }

    public function delete(int $id): RedirectResponse
    {
        $products = $this->productsService->checkProductId($id);
        $this->productsService->delete($products);

        return $this->redirectToRoute('manager_products_list');
    }
}