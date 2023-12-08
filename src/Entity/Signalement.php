<?php

namespace App\Entity;

use App\Repository\SignalementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SignalementRepository::class)]
class Signalement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'signalements')]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(inversedBy: 'signalements')]
    private ?Personnel $personnel = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $observation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_signal = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateSignal(): ?\DateTimeInterface
    {
        return $this->date_signal;
    }

    public function setDateSignal(\DateTimeInterface $date_signal): static
    {
        $this->date_signal = $date_signal;

        return $this;
    }
}
