<?php

namespace App\Entity;

use App\Repository\PromotionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PromotionsRepository::class)]
#[UniqueEntity('name', message: "Cette promotion existe déjà")]
class Promotions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $name = '';

    #[ORM\ManyToOne(inversedBy: 'promotions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $professor = null;

    /**
     * @var Collection<int, PromotionUsers>
     */
    #[ORM\OneToMany(targetEntity: PromotionUsers::class, mappedBy: 'promotion')]
    private Collection $promotionUsers;

    /**
     * @var Collection<int, Projects>
     */
    #[ORM\ManyToMany(targetEntity: Projects::class, mappedBy: 'promotions')]
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

    public function getProfessor(): ?User
    {
        return $this->professor;
    }

    public function setProfessor(?User $professor): static
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
            $promotionUser->setPromotion($this);
        }

        return $this;
    }

    public function removepromotionUser(promotionUsers $promotionUser): static
    {
        if ($this->promotionUsers->removeElement($promotionUser)) {
            // set the owning side to null (unless already changed)
            if ($promotionUser->getPromotion() === $this) {
                $promotionUser->setPromotion(null);
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

    /* public function addProject(Projects $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setPromotion($this);
        }

        return $this;
    }

    public function removeProject(Projects $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getPromotion() === $this) {
                $project->setPromotion(null);
            }
        }

        return $this;
    } */

    public function addProject(Projects $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->addPromotion($this);
        }

        return $this;
    }

    public function removeProject(Projects $project): static
    {
        if ($this->projects->removeElement($project)) {
            $project->removePromotion($this);
        }

        return $this;
    }
}
