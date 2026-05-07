<?php

namespace App\Entity;

use App\Repository\NotificationRecipientsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRecipientsRepository::class)]
class NotificationRecipients
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?notifications $notification_id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $user_id = null;

    #[ORM\Column]
    private ?bool $is_read = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $read_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNotificationId(): ?notifications
    {
        return $this->notification_id;
    }

    public function setNotificationId(?notifications $notification_id): static
    {
        $this->notification_id = $notification_id;

        return $this;
    }

    public function getUserId(): ?user
    {
        return $this->user_id;
    }

    public function setUserId(?user $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function isRead(): ?bool
    {
        return $this->is_read;
    }

    public function setIsRead(bool $is_read): static
    {
        $this->is_read = $is_read;

        return $this;
    }

    public function getReadAt(): ?\DateTimeImmutable
    {
        return $this->read_at;
    }

    public function setReadAt(?\DateTimeImmutable $read_at): static
    {
        $this->read_at = $read_at;

        return $this;
    }
}
