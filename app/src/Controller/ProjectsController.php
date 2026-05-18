<?php

namespace App\Controller;

use App\Entity\Grades;
use App\Entity\Projects;
use App\Enum\GradeStatus;
use App\Form\ProjectsType;
use App\Form\GradeSubmission;
use App\Repository\GradesRepository;
use App\Repository\ProjectsRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/projects')]
#[IsGranted('ROLE_USER')]
final class ProjectsController extends AbstractController
{
    #[Route(name: 'app_projects_index', methods: ['GET'])]
    public function index(ProjectsRepository $projectsRepository): Response
    {
        $user = $this->getUser();

        /* if ($this->isGranted('ROLE_STUDENT')) {
            $projects = $projectsRepository->findByStudentPromotions($user);
        } else */
        $projects = $projectsRepository->findAll();

        return $this->render('projects/index.html.twig', [
            'projects' => $projects,
        ]);
    }

    #[Route('/new', name: 'app_projects_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_TEACHER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Projects();
        $form = $this->createForm(ProjectsType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('app_projects_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('projects/new.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_projects_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Projects $project, GradesRepository $gradesRepository, EntityManagerInterface $entityManager): Response
    {

        $grades = $gradesRepository->findBy(
            ['project' => $project],
            ['status' => 'ASC']
        );


        $grade = $gradesRepository->findOneBy([
            'project' => $project,
            'student' => $this->getUser(),
        ]);

        if (!$grade) {
            throw $this->createNotFoundException('Grade introuvable');
        }

        $form = $this->createForm(GradeSubmission::class, $grade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Projet soumis avec success.');

            return $this->redirectToRoute('app_projects_show', [
                'id' => $project->getId()
            ]);
        }


        return $this->render('projects/show.html.twig', [
            'project' => $project,
            'grades' => $grades,
            'hasGrade' => $grade,
            'submissionForm' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_projects_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_TEACHER')]
    public function edit(Request $request, Projects $project, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjectsType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_projects_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('projects/edit.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_projects_delete', methods: ['POST'])]
    #[IsGranted('ROLE_TEACHER')]
    public function delete(Request $request, Projects $project, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $project->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($project);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_projects_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}/register', name: 'app_projects_register', methods: ['POST'])]
    #[IsGranted('ROLE_STUDENT')]
    public function register(Request $request, Projects $project, EntityManagerInterface $entityManager, GradesRepository $gradesRepository): Response
    {

        // Verification si student deja inscrit
        $existingGrade = $gradesRepository->findOneBy([
            'project' => $project,
            'student' => $this->getUser(),
        ]);

        if ($existingGrade) {
            $this->addFlash('warning', 'Vous êtes déjà inscrit à ce projet.');

            return $this->redirectToRoute(
                'app_projects_show',
                ['id' => $project->getId()]
            );
        }

        // Creation grade
        $grade = new Grades();

        $grade->setProject($project);
        $grade->setStudent($this->getUser());
        $grade->setStatus(GradeStatus::PENDING);
        $grade->setGrade(null);
        $grade->setUpdateHistory(new DateTime());

        $entityManager->persist($grade);
        $entityManager->flush();

        $this->addFlash('success', 'Vous desormais inscrit à ce projet.');

        return $this->redirectToRoute(
            'app_projects_show',
            ['id' => $project->getId()],
            Response::HTTP_SEE_OTHER
        );
    }

    /*     #[Route('/project/{id}/submit', name: 'app_grades_submit', methods: ['POST'])]
    #[IsGranted('ROLE_STUDENT')]
    public function submit(
        Request $request,
        Projects $project,
        GradesRepository $gradesRepository,
        EntityManagerInterface $entityManager
    ): Response {

        $grade = $gradesRepository->findOneBy([
            'project' => $project,
            'student' => $this->getUser(),
        ]);

        if (!$grade) {
            throw $this->createNotFoundException('Grade introuvable');
        }

        $form = $this->createForm(GradeSubmission::class, $grade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Projet soumis avec success.');

            return $this->redirectToRoute('app_projects_show', [
                'id' => $project->getId()
            ]);
        }

        return $this->render('projects/show.html.twig', [
            'grade' => $grade,
            'form' => $form->createView(),
            'submissionForm' => $form->createView(),
        ]);
    } */
}
