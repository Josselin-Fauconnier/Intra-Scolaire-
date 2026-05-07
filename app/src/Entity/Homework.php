<?php

namespace App\Entity;

use App\Repository\HomeworkRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HomeworkRepository::class)]
class Homework
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?classes $class_id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTime $due_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClassId(): ?classes
    {
        return $this->class_id;
    }

    public function setClassId(?classes $class_id): static
    {
        $this->class_id = $class_id;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDueDate(): ?\DateTime
    {
        return $this->due_date;
    }

    public function setDueDate(\DateTime $due_date): static
    {
        $this->due_date = $due_date;

        return $this;
    }
}
