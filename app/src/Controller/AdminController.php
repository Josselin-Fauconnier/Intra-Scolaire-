<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\NewMemberType;
use App\Repository\PromotionsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
final class AdminController extends AbstractController
{
    #[Route('/users', name: 'app_admin_users', methods: ['GET'])]

    public function users(
        UserRepository $userRepository,
        PromotionsRepository $promotionsRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        if (!$this->isGranted('ROLE_TEACHER') && !$this->isGranted('ROLE_VISITOR')) {
            throw $this->createAccessDeniedException("Accès refusé.");
        }
        $currentUser = $this->getUser();
        $isAdmin     = $this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_VISITOR');

        $search      = trim($request->query->getString('search', ''));
        $promoParam  = $request->query->get('promotion', '');
        $promotionId = ($promoParam !== '' && ctype_digit($promoParam)) ? (int) $promoParam : null;

        $teacher = $isAdmin ? null : $currentUser;

        $data     = $userRepository->findStudents($teacher, $search, $promotionId);
        $students = $paginator->paginate($data, $request->query->getInt('page', 1), 20);

        $promotions = $isAdmin
            ? $promotionsRepository->findAll()
            : $promotionsRepository->findByTeacher($currentUser);

        return $this->render('admin/users.html.twig', [
            'students'   => $students,
            'promotions' => $promotions,
            'search'     => $search,
            'selectedPromotion' => $promotionId,
        ]);
    }

    #[Route('/users/{id}', name: 'app_students_show', methods: ['GET'])]

    public function show(User $user): Response
    {
        if (!$this->isGranted('ROLE_TEACHER') && !$this->isGranted('ROLE_VISITOR')) {
            throw $this->createAccessDeniedException("Accès refusé.");
        }
        return $this->render('admin/student_show.html.twig', [
            'student' => $user,
        ]);
    }

    #[Route('/users/{id}/role', name: 'app_admin_set_role', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function setRole(Request $request, User $user, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('set_role_' . $user->getId(), $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $allowedRoles = ['ROLE_STUDENT', 'ROLE_TEACHER', 'ROLE_ADMIN'];
        $role = $request->getPayload()->getString('role');

        if (!in_array($role, $allowedRoles, true)) {
            throw $this->createNotFoundException('Rôle invalide.');
        }

        $user->setRoles([$role]);
        $em->flush();

        $this->addFlash('success', $user->getFirstname() . ' ' . $user->getLastname() . ' → ' . $role);

        $redirect = $request->getPayload()->getString('redirect', '');
        return $this->redirectToRoute($redirect === 'members' ? 'app_admin_members' : 'app_admin_users');
    }

    #[Route('/members', name: 'app_admin_members', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function members(UserRepository $userRepository): Response
    {
        return $this->render('admin/members.html.twig', [
            'members' => $userRepository->findNonStudents(),
        ]);
    }

    #[Route('/members/new', name: 'app_admin_members_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function newMember(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(NewMemberType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($hasher->hashPassword($user, $form->get('plainPassword')->getData()));
            $user->setRoles([$form->get('role')->getData()]);
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', $user->getFirstname() . ' ' . $user->getLastname() . ' a été ajouté.');
            return $this->redirectToRoute('app_admin_members');
        }

        return $this->render('admin/member_new.html.twig', [
            'form' => $form,
        ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
    }
}
