<?php

namespace App\Controller;

use App\Entity\Notifications;
use App\Entity\NotificationRecipients;
use App\Form\NotificationsType;
use App\Repository\NotificationRecipientsRepository;
use App\Repository\NotificationsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/notifications')]
#[IsGranted('ROLE_USER')]
final class NotificationsController extends AbstractController
{
    #[Route(name: 'app_notifications_index', methods: ['GET'])]
    public function index(NotificationRecipientsRepository $repo, NotificationsRepository $notifRepo, Request $request): Response
    {
        $user = $this->getUser();

        $sent     = $notifRepo->findSentByUser($user);
        $received = $repo->findByUser($user);

        $feed = [];
        foreach ($sent as $notif) {
            $feed[] = ['sent' => true, 'notification' => $notif, 'recipient' => null, 'sort' => $notif->getCreatedAt()->getTimestamp()];
        }
        foreach ($received as $r) {
            $feed[] = ['sent' => false, 'notification' => $r->getNotificationId(), 'recipient' => $r, 'sort' => $r->getNotificationId()->getCreatedAt()->getTimestamp()];
        }
        usort($feed, fn($a, $b) => $a['sort'] <=> $b['sort']);

        return $this->render('notifications/index.html.twig', [
            'feed' => $feed,
        ]);
    }

    #[Route('/{id}/read', name: 'app_notifications_read', methods: ['POST'])]
    public function markRead(
        NotificationRecipients $recipient,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        if (!$this->isCsrfTokenValid('read' . $recipient->getId(), $request->getPayload()->getString('_token'))) {
            throw $this->createAccessDeniedException();
        }

        if ($recipient->getUserId()?->getId() !== $this->getUser()->getId()) {
            throw $this->createAccessDeniedException();
        }

        $recipient->setIsRead(true);
        $recipient->setReadAt(new \DateTimeImmutable());
        $em->flush();

        return $this->redirectToRoute('app_notifications_index');
    }

    #[Route('/new', name: 'app_notifications_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_TEACHER')]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $notification = new Notifications();
        $currentUser  = $this->getUser();
        $form = $this->createForm(NotificationsType::class, $notification, [
            'current_user' => $currentUser,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $audience  = $form->get('audience')->getData();
            $promotion = $form->get('promotion')->getData();

            // Enseignants limités à leurs propres promotions
            if (!$this->isGranted('ROLE_ADMIN')) {
                if ($audience !== 'promotion') {
                    throw $this->createAccessDeniedException();
                }
                if ($promotion !== null && $promotion->getProfessor()?->getId() !== $currentUser->getId()) {
                    throw $this->createAccessDeniedException();
                }
            }

            $notification->setSender($this->getUser());
            $entityManager->persist($notification);

            $recipients = match ($audience) {
                'students'  => $userRepository->findByRole('ROLE_STUDENT'),
                'teachers'  => $userRepository->findByRole('ROLE_TEACHER'),
                'promotion' => $promotion
                    ? array_map(fn($pu) => $pu->getUser(), $promotion->getpromotionUsers()->toArray())
                    : $userRepository->findByRole('ROLE_STUDENT'),
                default => $userRepository->findAll(),
            };

            foreach ($recipients as $user) {
                $recipient = new NotificationRecipients();
                $recipient->setNotificationId($notification);
                $recipient->setUserId($user);
                $recipient->setIsRead(false);
                $entityManager->persist($recipient);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Notification envoyée avec succès.');

            return $this->redirectToRoute('app_notifications_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('notifications/new.html.twig', [
            'notification' => $notification,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_notifications_show', methods: ['GET'])]
    public function show(Notifications $notification, NotificationRecipientsRepository $repo, EntityManagerInterface $em): Response
    {
        $user      = $this->getUser();
        $isSender  = $notification->getSender()?->getId() === $user->getId();
        $recipient = $repo->findOneBy(['notification' => $notification, 'user' => $user]);

        if (!$isSender && !$recipient) {
            throw $this->createAccessDeniedException();
        }

        if ($recipient && !$recipient->isRead()) {
            $recipient->setIsRead(true);
            $recipient->setReadAt(new \DateTimeImmutable());
            $em->flush();
        }

        return $this->render('notifications/show.html.twig', [
            'notification' => $notification,
            'isSender'     => $isSender,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_notifications_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_TEACHER')]
    public function edit(Request $request, Notifications $notification, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NotificationsType::class, $notification, [
            'current_user' => $this->getUser(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_notifications_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('notifications/edit.html.twig', [
            'notification' => $notification,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_notifications_delete', methods: ['POST'])]
    #[IsGranted('ROLE_TEACHER')]
    public function delete(Request $request, Notifications $notification, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$notification->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($notification);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_notifications_index', [], Response::HTTP_SEE_OTHER);
    }
}
