<?php

namespace App\Entity;

use App\Repository\ExamenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExamenRepository::class)]
class Examen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_examen = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description_examen = null;

    #[ORM\ManyToOne(inversedBy: 'examens')]
    private ?Personnel $personnel = null;

    #[ORM\ManyToOne(inversedBy: 'examens')]
    private ?Patient $patient = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_examen = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomExamen(): ?string
    {
        return $this->nom_examen;
    }

    public function setNomExamen(string $nom_examen): static
    {
        $this->nom_examen = $nom_examen;

        return $this;
    }

    public function getDescriptionExamen(): ?string
    {
        return $this->description_examen;
    }

    public function setDescriptionExamen(string $description_examen): static
    {
        $this->description_examen = $description_examen;

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

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getDateExamen(): ?\DateTimeInterface
    {
        return $this->date_examen;
    }

    public function setDateExamen(\DateTimeInterface $date_examen): static
    {
        $this->date_examen = $date_examen;

        return $this;
    }
}
