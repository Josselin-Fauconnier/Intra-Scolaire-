<?php

namespace App\Entity;

use App\Repository\ProjectsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProjectsRepository::class)]
#[UniqueEntity(fields: ['promotion', 'title'], message: "Ce projet existe déjà pour cette promotion.", errorPath: 'title')]
class Projects
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Promotions $promotion = null;

    #[ORM\Column(length: 255)]
    private string $title = '';

    #[ORM\Column]
    private bool $visibility = False;

    #[ORM\Column(type: Types::TEXT)]
    private string $description = '';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $google_drive = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $due_date = null;

    /**
     * @var Collection<int, Grades>
     */
    #[ORM\OneToMany(targetEntity: Grades::class, mappedBy: 'project', orphanRemoval: true)]
    private Collection $grades;

    public function __construct()
    {
        $this->grades = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrmotionId(): ?Promotions
    {
        return $this->promotion;
    }

    public function setPrmotionId(?Promotions $promotion): static
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function isVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(bool $visibility): static
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getGoogleDrive(): ?string
    {
        return $this->google_drive;
    }

    public function setGoogleDrive(?string $google_drive): static
    {
        $this->google_drive = $google_drive;

        return $this;
    }

    public function getDueDate(): ?\DateTime
    {
        return $this->due_date;
    }

    public function setDueDate(?\DateTime $due_date): static
    {
        $this->due_date = $due_date;

        return $this;
    }

    /**
     * @return Collection<int, Grades>
     */
    public function getGrades(): Collection
    {
        return $this->grades;
    }

    public function addGrade(Grades $grade): static
    {
        if (!$this->grades->contains($grade)) {
            $this->grades->add($grade);
            $grade->setProjectId($this);
        }

        return $this;
    }

    public function removeGrade(Grades $grade): static
    {
        if ($this->grades->removeElement($grade)) {
            // set the owning side to null (unless already changed)
            if ($grade->getProjectId() === $this) {
                $grade->setProjectId(null);
            }
        }

        return $this;
    }
}
