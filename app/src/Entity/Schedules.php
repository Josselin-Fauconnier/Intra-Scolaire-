<?php

namespace App\Entity;

use App\Enum\Weekdays;
use App\Repository\SchedulesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SchedulesRepository::class)]
class Schedules
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?classes $class_id = null;

    #[ORM\Column(enumType: Weekdays::class)]
    private ?Weekdays $day_of_week = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $start_time = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $end_time = null;

    #[ORM\Column(length: 255)]
    private ?string $room = null;

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

    public function getDayOfWeek(): ?Weekdays
    {
        return $this->day_of_week;
    }

    public function setDayOfWeek(Weekdays $day_of_week): static
    {
        $this->day_of_week = $day_of_week;

        return $this;
    }

    public function getStartTime(): ?\DateTime
    {
        return $this->start_time;
    }

    public function setStartTime(\DateTime $start_time): static
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getEnd�Time(): ?\DateTime
    {
        return $this->end_time;
    }

    public function setEnd�Time(\DateTime $end_time): static
    {
        $this->end_time = $end_time;

        return $this;
    }

    public function getRoom(): ?string
    {
        return $this->room;
    }

    public function setRoom(string $room): static
    {
        $this->room = $room;

        return $this;
    }
}
