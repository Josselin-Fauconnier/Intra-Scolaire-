<?php

namespace App\Controller;

use App\Repository\NotificationRecipientsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(NotificationRecipientsRepository $repo): Response
    {
        $user = $this->getUser();
        $unreadCount = count(array_filter(
            $repo->findByUser($user),
            fn($r) => !$r->isRead()
        ));

        return $this->render('dashboard/index.html.twig', [
            'user' => $user,
            'unreadCount' => $unreadCount,
        ]);
    }
}
