<?php

namespace App\Entity;

use App\Repository\DiagnosticRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiagnosticRepository::class)]
class Diagnostic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_diagnostic = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description_diagnostic = null;

    #[ORM\ManyToOne(inversedBy: 'diagnostics')]
    private ?Patient $patient = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\ManyToOne(inversedBy: 'diagnostics')]
    private ?Personnel $personnel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomDiagnostic(): ?string
    {
        return $this->nom_diagnostic;
    }

    public function setNomDiagnostic(string $nom_diagnostic): static
    {
        $this->nom_diagnostic = $nom_diagnostic;

        return $this;
    }

    public function getDescriptionDiagnostic(): ?string
    {
        return $this->description_diagnostic;
    }

    public function setDescriptionDiagnostic(string $description_diagnostic): static
    {
        $this->description_diagnostic = $description_diagnostic;

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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): static
    {
        $this->date_creation = $date_creation;

        return $this;
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
}
