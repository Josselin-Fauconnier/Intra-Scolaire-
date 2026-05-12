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
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'promtionUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Promotions $promotion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user;
    }

    public function setUserId(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPromotionId(): ?Promotions
    {
        return $this->promotion;
    }

    public function setPromotionId(?Promotions $promotion): static
    {
        $this->promotion = $promotion;

        return $this;
    }
}
