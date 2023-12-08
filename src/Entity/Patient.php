<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_patient = null;

    #[ORM\Column(length: 255)]
    private ?string $postnom_patient = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom_patient = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description_patient = null;

    #[ORM\Column]
    private ?int $age = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $matricule = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\ManyToOne(inversedBy: 'patients')]
    private ?Personnel $personnel = null;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Dossier::class)]
    private Collection $dossiers;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Diagnostic::class)]
    private Collection $diagnostics;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Examen::class)]
    private Collection $examens;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Ordonnance::class)]
    private Collection $ordonnances;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Fiche::class)]
    private Collection $fiches;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Signalement::class)]
    private Collection $signalements;

    public function __construct()
    {
        $this->dossiers = new ArrayCollection();
        $this->diagnostics = new ArrayCollection();
        $this->examens = new ArrayCollection();
        $this->signals = new ArrayCollection();
        $this->ordonnances = new ArrayCollection();
        $this->fiches = new ArrayCollection();
        $this->signalements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNomPatient(): ?string
    {
        return $this->nom_patient;
    }

    public function setNomPatient(string $nom_patient): static
    {
        $this->nom_patient = $nom_patient;

        return $this;
    }

    public function getPostnomPatient(): ?string
    {
        return $this->postnom_patient;
    }

    public function setPostnomPatient(string $postnom_patient): static
    {
        $this->postnom_patient = $postnom_patient;

        return $this;
    }

    public function getPrenomPatient(): ?string
    {
        return $this->prenom_patient;
    }

    public function setPrenomPatient(string $prenom_patient): static
    {
        $this->prenom_patient = $prenom_patient;

        return $this;
    }

    public function getDescriptionPatient(): ?string
    {
        return $this->description_patient;
    }

    public function setDescriptionPatient(string $description_patient): static
    {
        $this->description_patient = $description_patient;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): static
    {
        $this->matricule = $matricule;

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

    /**
     * @return Collection<int, Dossier>
     */
    public function getDossiers(): Collection
    {
        return $this->dossiers;
    }

    public function addDossier(Dossier $dossier): static
    {
        if (!$this->dossiers->contains($dossier)) {
            $this->dossiers->add($dossier);
            $dossier->setPatient($this);
        }

        return $this;
    }

    public function removeDossier(Dossier $dossier): static
    {
        if ($this->dossiers->removeElement($dossier)) {
            // set the owning side to null (unless already changed)
            if ($dossier->getPatient() === $this) {
                $dossier->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Diagnostic>
     */
    public function getDiagnostics(): Collection
    {
        return $this->diagnostics;
    }

    public function addDiagnostic(Diagnostic $diagnostic): static
    {
        if (!$this->diagnostics->contains($diagnostic)) {
            $this->diagnostics->add($diagnostic);
            $diagnostic->setPatient($this);
        }

        return $this;
    }

    public function removeDiagnostic(Diagnostic $diagnostic): static
    {
        if ($this->diagnostics->removeElement($diagnostic)) {
            // set the owning side to null (unless already changed)
            if ($diagnostic->getPatient() === $this) {
                $diagnostic->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Examen>
     */
    public function getExamens(): Collection
    {
        return $this->examens;
    }

    public function addExamen(Examen $examen): static
    {
        if (!$this->examens->contains($examen)) {
            $this->examens->add($examen);
            $examen->setPatient($this);
        }

        return $this;
    }

    public function removeExamen(Examen $examen): static
    {
        if ($this->examens->removeElement($examen)) {
            // set the owning side to null (unless already changed)
            if ($examen->getPatient() === $this) {
                $examen->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ordonnance>
     */
    public function getOrdonnances(): Collection
    {
        return $this->ordonnances;
    }

    public function addOrdonnance(Ordonnance $ordonnance): static
    {
        if (!$this->ordonnances->contains($ordonnance)) {
            $this->ordonnances->add($ordonnance);
            $ordonnance->setPatient($this);
        }

        return $this;
    }

    public function removeOrdonnance(Ordonnance $ordonnance): static
    {
        if ($this->ordonnances->removeElement($ordonnance)) {
            // set the owning side to null (unless already changed)
            if ($ordonnance->getPatient() === $this) {
                $ordonnance->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Fiche>
     */
    public function getFiches(): Collection
    {
        return $this->fiches;
    }

    public function addFich(Fiche $fich): static
    {
        if (!$this->fiches->contains($fich)) {
            $this->fiches->add($fich);
            $fich->setPatient($this);
        }

        return $this;
    }

    public function removeFich(Fiche $fich): static
    {
        if ($this->fiches->removeElement($fich)) {
            // set the owning side to null (unless already changed)
            if ($fich->getPatient() === $this) {
                $fich->setPatient(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom_patient . " " . $this->postnom_patient . " " . $this->prenom_patient;
    }

    /**
     * @return Collection<int, Signalement>
     */
    public function getSignalements(): Collection
    {
        return $this->signalements;
    }

    public function addSignalement(Signalement $signalement): static
    {
        if (!$this->signalements->contains($signalement)) {
            $this->signalements->add($signalement);
            $signalement->setPatient($this);
        }

        return $this;
    }

    public function removeSignalement(Signalement $signalement): static
    {
        if ($this->signalements->removeElement($signalement)) {
            // set the owning side to null (unless already changed)
            if ($signalement->getPatient() === $this) {
                $signalement->setPatient(null);
            }
        }

        return $this;
    }
}
