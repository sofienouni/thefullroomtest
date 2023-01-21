<?php

namespace App\Controller;

use App\Entity\Conges;
use App\Entity\User;
use App\Form\CongesType;
use App\Repository\CongesRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home_index', methods: ['GET'])]
    public function index(CongesRepository $congesRepository): Response
    {

        if (in_array('ROLE_USER', $this->getUser()->getRoles(), true)) {
            $conges = $congesRepository->findBy(['user'=> $this->getUser()]);
        }elseif (in_array('ROLE_USER', $this->getUser()->getRoles(), true)){
            $conges = $congesRepository->findAll();
        }

        return $this->render('home/index.html.twig', [
            'conges' => $conges,
        ]);
    }

    #[Route('/new', name: 'app_home_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CongesRepository $congesRepository): Response
    {
        $conge = new Conges();
        $form = $this->createForm(CongesType::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $congesRepository->save($conge, true);

            return $this->redirectToRoute('app_home_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('home/new.html.twig', [
            'conge' => $conge,
            'form' => $form,
        ]);
    }

    #[Route('/users', name: 'app_user_index')]
    public function show(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('home/user_list.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/list_conges_user', name: 'app_conge_list', methods: ['GET', 'POST'])]
    public function list_conges_user(UserRepository $userRepository, CongesRepository $congesRepository): Response
    {

        $user = $userRepository->find($_GET["id"]);
        $conges = $congesRepository->findBy(['user'=> $user]);

        return $this->render('home/index.html.twig', [
            'conges' => $conges,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_home_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Conges $conge, CongesRepository $congesRepository): Response
    {
        $form = $this->createForm(CongesType::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $congesRepository->save($conge, true);

            return $this->redirectToRoute('app_home_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('home/edit.html.twig', [
            'conge' => $conge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_home_delete', methods: ['POST'])]
    public function delete(Request $request, Conges $conge, CongesRepository $congesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$conge->getId(), $request->request->get('_token'))) {
            $congesRepository->remove($conge, true);
        }

        return $this->redirectToRoute('app_home_index', [], Response::HTTP_SEE_OTHER);
    }
}
