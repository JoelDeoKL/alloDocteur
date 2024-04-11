<?php

namespace App\Entity;

use App\Repository\FicheRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FicheRepository::class)]
class Fiche
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'fiches')]
    private ?Dossier $dossier = null;

    #[ORM\Column(length: 255)]
    private ?string $date_naissance = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse_patient = null;

    #[ORM\Column(length: 255)]
    private ?string $date_entre = null;

    #[ORM\Column(length: 255)]
    private ?string $date_sortie = null;

    #[ORM\ManyToOne(inversedBy: 'fiches')]
    private ?Patient $patient = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom_conjoint = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $service = null;

    #[ORM\ManyToOne(inversedBy: 'fiches')]
    private ?Personnel $personnel = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $observation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_creation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDossier(): ?Dossier
    {
        return $this->dossier;
    }

    public function setDossier(?Dossier $dossier): static
    {
        $this->dossier = $dossier;

        return $this;
    }

    public function getDateNaissance(): ?string
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(string $date_naissance): static
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    public function getAdressePatient(): ?string
    {
        return $this->adresse_patient;
    }

    public function setAdressePatient(string $adresse_patient): static
    {
        $this->adresse_patient = $adresse_patient;

        return $this;
    }

    public function getDateEntre(): ?string
    {
        return $this->date_entre;
    }

    public function setDateEntre(string $date_entre): static
    {
        $this->date_entre = $date_entre;

        return $this;
    }

    public function getDateSortie(): ?string
    {
        return $this->date_sortie;
    }

    public function setDateSortie(string $date_sortie): static
    {
        $this->date_sortie = $date_sortie;

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

    public function getNomConjoint(): ?string
    {
        return $this->nom_conjoint;
    }

    public function setNomConjoint(?string $nom_conjoint): static
    {
        $this->nom_conjoint = $nom_conjoint;

        return $this;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(?string $service): static
    {
        $this->service = $service;

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

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(string $observation): static
    {
        $this->observation = $observation;

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
}
