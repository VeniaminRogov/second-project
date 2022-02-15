<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Services\UserService;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $manager,
        private PaginatorInterface $paginator,
        private UserService $userService,
    )
    {}

    public function index(): Response
    {
        $user = $this->manager->getRepository(User::class)->findAll();

        $pagination = $this->paginator->paginate(
            $user,
            1,
            10
        );

        return $this->render('admin/index.twig',[
            'users' => $pagination
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

        return $this->renderForm('admin/form_user.twig', [
            'form' => $form,
        ]);
    }

    public function delete($id): RedirectResponse
    {
        $user = $this->userService->checkUserId($id);
        $this->userService->delete($user);

        return $this->redirectToRoute('admin_user_list');
    }
}