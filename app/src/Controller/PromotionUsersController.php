<?php

namespace App\Controller;

use App\Entity\PromotionUsers;
use App\Form\PromotionUsersType;
use App\Repository\PromotionUsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/promotion/users')]
final class PromotionUsersController extends AbstractController
{
    #[Route(name: 'app_promotion_users_index', methods: ['GET'])]
    public function index(PromotionUsersRepository $promotionUsersRepository): Response
    {
        return $this->render('promotion_users/index.html.twig', [
            'promotion_users' => $promotionUsersRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_promotion_users_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $promotionUser = new PromotionUsers();
        $form = $this->createForm(PromotionUsersType::class, $promotionUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($promotionUser);
            $entityManager->flush();

            return $this->redirectToRoute('app_promotion_users_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('promotion_users/new.html.twig', [
            'promotion_user' => $promotionUser,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_promotion_users_show', methods: ['GET'])]
    public function show(PromotionUsers $promotionUser): Response
    {
        return $this->render('promotion_users/show.html.twig', [
            'promotion_user' => $promotionUser,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_promotion_users_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PromotionUsers $promotionUser, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PromotionUsersType::class, $promotionUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_promotion_users_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('promotion_users/edit.html.twig', [
            'promotion_user' => $promotionUser,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_promotion_users_delete', methods: ['POST'])]
    public function delete(Request $request, PromotionUsers $promotionUser, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$promotionUser->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($promotionUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_promotion_users_index', [], Response::HTTP_SEE_OTHER);
    }
}
