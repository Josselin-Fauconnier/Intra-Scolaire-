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
    public function index(NotificationRecipientsRepository $repo): Response
    {
        $recipients = $repo->findByUser($this->getUser());

        return $this->render('notifications/index.html.twig', [
            'recipients' => $recipients,
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

        if ($recipient->getUserId() !== $this->getUser()) {
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
        $form = $this->createForm(NotificationsType::class, $notification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($notification);

            foreach ($userRepository->findAll() as $user) {
                $recipient = new NotificationRecipients();
                $recipient->setNotificationId($notification);
                $recipient->setUserId($user);
                $recipient->setIsRead(false);
                $entityManager->persist($recipient);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_notifications_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('notifications/new.html.twig', [
            'notification' => $notification,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_notifications_show', methods: ['GET'])]
    public function show(Notifications $notification): Response
    {
        return $this->render('notifications/show.html.twig', [
            'notification' => $notification,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_notifications_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_TEACHER')]
    public function edit(Request $request, Notifications $notification, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NotificationsType::class, $notification);
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
