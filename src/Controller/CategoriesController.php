<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Products;
use App\Form\AddCategoryFormType;
use App\Form\CategoriesFormType;
use App\Form\SortCategoryFormType;
use App\Objects\SortCategoryObject;
use App\Services\CategoriesService;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class CategoriesController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $manager,
        private PaginatorInterface $paginator,
        private CategoriesService $service,
        private TranslatorInterface $translator,
    )
    {}

    public function index(Request $request): Response
    {
        $sortObject = new SortCategoryObject();
        $form = $this->createForm(SortCategoryFormType::class, $sortObject);

        $form->handleRequest($request);

        $category = $this->manager->getRepository(Category::class)->findAll();

        $pagination = $this->paginator->paginate(
            $category,
            $sortObject->getPage(),
            3
        );
        return $this->render('categories/index.html.twig', [
            'categories' => $pagination,
            'form' => $form->createView()
        ]);
    }

    public function createAndUpdate(Request $request, ?int $id): Response
    {
        $category = $this->service->checkCategoryId($id);

        $form = $this->createForm(CategoriesFormType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $category = $form->getData();
            $category = $this->service->createAndUpdate($category);

            return $this->redirectToRoute('manager_edit_category', [
                'id' => $category->getId()
            ]);
        }

        $formAdd = $this->createForm(AddCategoryFormType::class, $category);
        $formAdd->handleRequest($request);
        if($formAdd->isSubmitted() && $formAdd->isValid())
        {
            $category = $formAdd->getData();
            $category = $this->service->createAndUpdate($category);

            return $this->redirectToRoute('manager_edit_category', [
                'id' => $category->getId()
            ]);
        }

        return $this->render('categories/form_categories.html.twig', [
            'form' => $form->createView(),
            'addForm' => $formAdd->createView(),
            'title' => $id ? $this->translator->trans('category.title.update', [], 'admin') : $this->translator->trans('category.title.create', [], 'admin')
        ]);
    }

    public function delete(int $id): RedirectResponse
    {
        $category = $this->service->checkCategoryId($id);
        $this->service->delete($category);

        return $this->redirectToRoute('manager_list_category');
    }
}