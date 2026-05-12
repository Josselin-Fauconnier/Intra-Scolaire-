<?php

namespace App\Entity;

use App\Repository\PromtionUsersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PromtionUsersRepository::class)]
class PromtionUsers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'promtionUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\ManyToOne(inversedBy: 'promtionUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Promotions $promotion_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getPromotionId(): ?Promotions
    {
        return $this->promotion_id;
    }

    public function setPromotionId(?Promotions $promotion_id): static
    {
        $this->promotion_id = $promotion_id;

        return $this;
    }
}
