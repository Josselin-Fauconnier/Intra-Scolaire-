<?php

namespace App\Service;

use App\Entity\Notifications;
use App\Entity\NotificationRecipients;
use App\Entity\User;
use App\Enum\NotificationType;
use Doctrine\ORM\EntityManagerInterface;

class NotificationService
{
    public function __construct(private EntityManagerInterface $em) {}

    public function notify(string $title, string $message, NotificationType $type, User ...$recipients): void
    {
        if (empty($recipients)) {
            return;
        }

        $notification = new Notifications();
        $notification->setTitle($title);
        $notification->setMessage($message);
        $notification->setType($type);
        $this->em->persist($notification);

        foreach ($recipients as $user) {
            $recipient = new NotificationRecipients();
            $recipient->setNotificationId($notification);
            $recipient->setUserId($user);
            $recipient->setIsRead(false);
            $this->em->persist($recipient);
        }
    }
}
