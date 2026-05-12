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
    private ?User $professor_id = null;

    /**
     * @var Collection<int, PromtionUsers>
     */
    #[ORM\OneToMany(targetEntity: PromtionUsers::class, mappedBy: 'promotion_id')]
    private Collection $promtionUsers;

    /**
     * @var Collection<int, Projects>
     */
    #[ORM\OneToMany(targetEntity: Projects::class, mappedBy: 'prmotion_id')]
    private Collection $projects;

    public function __construct()
    {
        $this->promtionUsers = new ArrayCollection();
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
        return $this->professor_id;
    }

    public function setProfessorId(?User $professor_id): static
    {
        $this->professor_id = $professor_id;

        return $this;
    }

    /**
     * @return Collection<int, PromtionUsers>
     */
    public function getPromtionUsers(): Collection
    {
        return $this->promtionUsers;
    }

    public function addPromtionUser(PromtionUsers $promtionUser): static
    {
        if (!$this->promtionUsers->contains($promtionUser)) {
            $this->promtionUsers->add($promtionUser);
            $promtionUser->setPromotionId($this);
        }

        return $this;
    }

    public function removePromtionUser(PromtionUsers $promtionUser): static
    {
        if ($this->promtionUsers->removeElement($promtionUser)) {
            // set the owning side to null (unless already changed)
            if ($promtionUser->getPromotionId() === $this) {
                $promtionUser->setPromotionId(null);
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
