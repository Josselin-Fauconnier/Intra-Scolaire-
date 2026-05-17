<?php

namespace App\Controller;

use App\Entity\Grades;
use App\Form\GradesType;
use App\Repository\GradesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/grades')]
#[IsGranted('ROLE_USER')]
final class GradesController extends AbstractController
{
    #[Route(name: 'app_grades_index', methods: ['GET'])]
    public function index(GradesRepository $gradesRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN')) {
            $data = $gradesRepository->findAll();
        } elseif ($this->isGranted('ROLE_TEACHER')) {
            $data = $gradesRepository->findByTeacher($user);
        } else {
            $data = $gradesRepository->findByStudent($user);
        }

        $grades = $paginator->paginate($data, $request->query->getInt('page', 1), 10);

        return $this->render('grades/index.html.twig', [
            'grades' => $grades,
        ]);
    }

    #[Route('/new', name: 'app_grades_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_TEACHER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $grade = new Grades();
        $form = $this->createForm(GradesType::class, $grade, [
            'current_user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $grade->setUpdateHistory(new \DateTime());
            $entityManager->persist($grade);
            $entityManager->flush();

            return $this->redirectToRoute('app_grades_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('grades/new.html.twig', [
            'grade' => $grade,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_grades_show', methods: ['GET'])]
    public function show(Grades $grade): Response
    {
        return $this->render('grades/show.html.twig', [
            'grade' => $grade,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_grades_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_TEACHER')]
    public function edit(Request $request, Grades $grade, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GradesType::class, $grade, [
            'current_user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $grade->setUpdateHistory(new \DateTime());
            $entityManager->flush();

            return $this->redirectToRoute('app_grades_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('grades/edit.html.twig', [
            'grade' => $grade,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_grades_delete', methods: ['POST'])]
    #[IsGranted('ROLE_TEACHER')]
    public function delete(Request $request, Grades $grade, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$grade->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($grade);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_grades_index', [], Response::HTTP_SEE_OTHER);
    }
}
