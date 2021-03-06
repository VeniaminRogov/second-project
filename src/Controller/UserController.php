<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SortUserFormType;
use App\Form\UserFormType;
use App\Objects\SortUserObject;
use App\Services\UserService;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $manager,
        private PaginatorInterface $paginator,
        private UserService $userService,
        private TranslatorInterface $translator,
    )
    {}

    public function index(Request $request): Response
    {
        $sortObject = new SortUserObject();

        $form = $this->createForm(SortUserFormType::class, $sortObject);
        $form->handleRequest($request);

        $userSort = $this->manager->getRepository(User::class)->sortUser($sortObject);
        $allUsers = $this->manager->getRepository(User::class)->findAll();

        if ($form->isSubmitted())
        {
            $pagination = $this->paginator->paginate(
                $userSort,
                $sortObject->getPage(),
                3
            );
            return $this->render('admin/index.html.twig', [
                'sortForm' => $form->createView(),
                'users' => $pagination
            ]);
        }

        $pagination = $this->paginator->paginate(
            $allUsers,
            $sortObject->getPage(),
            3
        );

        return $this->render('admin/index.html.twig',[
            'sortForm' => $form->createView(),
            'users' => $pagination,
        ]);
    }

    public function createAndUpdate(Request $request, ?int $id): Response
    {
        $user = $this->userService->checkUserId($id);

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
            $password = $form->get('password')->getData();
            $user = $this->userService->createAndUpdate($user, $password);
            return $this->redirectToRoute('admin_edit_user', [
                'id' => $user->getId()
            ]);
        }

        return $this->renderForm('admin/form_user.html.twig', [
            'form' => $form,
            'title' => $id ? $this->translator->trans('users.title.update', [], 'admin') : $this->translator->trans('users.title.new', [], 'admin')
        ]);
    }

    public function delete($id): RedirectResponse
    {
        $user = $this->userService->checkUserId($id);
        $this->userService->delete($user);

        return $this->redirectToRoute('admin_user_list');
    }
}