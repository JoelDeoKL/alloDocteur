<?php

namespace App\Entity;

use App\Repository\OrdonnanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdonnanceRepository::class)]
class Ordonnance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ordonnances')]
    private ?Personnel $personnel = null;

    #[ORM\ManyToOne(inversedBy: 'ordonnances')]
    private ?Patient $patient = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ordannance = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_ordonnance = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPersonnel(): ?Personnel
    {
        return $this->personnel;
    }

    public function setPersonnel(?Personnel $personnel): static
    {
        $this->personnel = $personnel;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getOrdannance(): ?string
    {
        return $this->ordannance;
    }

    public function setOrdannance(string $ordannance): static
    {
        $this->ordannance = $ordannance;

        return $this;
    }

    public function getDateOrdonnance(): ?\DateTimeInterface
    {
        return $this->date_ordonnance;
    }

    public function setDateOrdonnance(\DateTimeInterface $date_ordonnance): static
    {
        $this->date_ordonnance = $date_ordonnance;

        return $this;
    }
}
