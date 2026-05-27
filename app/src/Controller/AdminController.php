<?php

namespace App\Controller;

use App\Entity\Promotions;
use App\Entity\PromotionUsers;
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
    #[IsGranted('ROLE_TEACHER')]
    public function users(
        UserRepository $userRepository,
        PromotionsRepository $promotionsRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $currentUser = $this->getUser();
        $isAdmin     = $this->isGranted('ROLE_ADMIN');

        $search      = trim($request->query->getString('search', ''));
        $promoParam  = $request->query->get('promotion', '');
        $promotionId = ($promoParam !== '' && ctype_digit($promoParam)) ? (int) $promoParam : null;

        /* $teacher = $isAdmin ? null : $currentUser; */ //plus necessaire car on veut une liste compléte d'utilisateurs, pas seuelment ceux inscrits
        /*  $data     = $userRepository->findStudents($teacher, $search, $promotionId); */

        $data     = $userRepository->findStudents();
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
    #[IsGranted('ROLE_TEACHER')]
    public function show(User $user, PromotionsRepository $promotionsRepository): Response
    {
        return $this->render('admin/student_show.html.twig', [
            'student' => $user,
            'promotions' => $promotionsRepository->findAll()
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

    #[Route('/users/{id}/promo', name: 'app_admin_set_promo', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function setPromo(Request $request, User $user, PromotionsRepository $promotions, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('set_promo_' . $user->getId(), $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException();
        }

        $chosenPromotions = $request->getPayload()->all('promotion');

        foreach ($user->getPromotionUsers() as $oldLink) {
            $em->remove($oldLink);
        }

        $selectedPromotions = $promotions->findBy(['id' => $chosenPromotions]);

        foreach ($selectedPromotions as $p) {
            $promotionUser = new PromotionUsers();
            $promotionUser->setUser($user);
            $promotionUser->setPromotion($p);
            $em->persist($promotionUser);
        }
        $em->flush();

        $promoNames = array_map(fn($p) => $p->getName(), $selectedPromotions);
        $this->addFlash('success', sprintf(
            'Promotions mises à jour pour %s : %s',
            $user->getFirstname(),
            empty($promoNames) ? 'Aucune' : implode(', ', $promoNames)
        ));

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

        $redirect = $request->getPayload()->getString('redirect', '');
        return $this->redirectToRoute($redirect === 'members' ? 'app_admin_members' : 'app_admin_users');
    }
}
