<?php

namespace App\Entity;

use App\Repository\PromotionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PromotionsRepository::class)]
class Promotions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'promotions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $professor = null;

    /**
     * @var Collection<int, PromotionUsers>
     */
    #[ORM\OneToMany(targetEntity: PromotionUsers::class, mappedBy: 'promotion_id')]
    private Collection $promotionUsers;

    /**
     * @var Collection<int, Projects>
     */
    #[ORM\OneToMany(targetEntity: Projects::class, mappedBy: 'prmotion_id')]
    private Collection $projects;

    public function __construct()
    {
        $this->promotionUsers = new ArrayCollection();
        $this->projects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getProfessorId(): ?User
    {
        return $this->professor;
    }

    public function setProfessorId(?User $professor): static
    {
        $this->professor = $professor;

        return $this;
    }

    /**
     * @return Collection<int, PromotionUsers>
     */
    public function getpromotionUsers(): Collection
    {
        return $this->promotionUsers;
    }

    public function addpromotionUser(promotionUsers $promotionUser): static
    {
        if (!$this->promotionUsers->contains($promotionUser)) {
            $this->promotionUsers->add($promotionUser);
            $promotionUser->setPromotionId($this);
        }

        return $this;
    }

    public function removepromotionUser(promotionUsers $promotionUser): static
    {
        if ($this->promotionUsers->removeElement($promotionUser)) {
            // set the owning side to null (unless already changed)
            if ($promotionUser->getPromotionId() === $this) {
                $promotionUser->setPromotionId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Projects>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Projects $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setPrmotionId($this);
        }

        return $this;
    }

    public function removeProject(Projects $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getPrmotionId() === $this) {
                $project->setPrmotionId(null);
            }
        }

        return $this;
    }
}
