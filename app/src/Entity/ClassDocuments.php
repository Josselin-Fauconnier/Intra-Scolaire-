<?php

namespace App\Entity;

use App\Repository\ClassDocumentsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassDocumentsRepository::class)]
class ClassDocuments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?classes $class_id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?documents $document_id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?bool $visibility = null;

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

    public function getDocumentId(): ?documents
    {
        return $this->document_id;
    }

    public function setDocumentId(?documents $document_id): static
    {
        $this->document_id = $document_id;

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
}
