<?php

namespace App\Entity;

use App\Repository\PersonnelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: PersonnelRepository::class)]
class Personnel implements UserInterface, PasswordAuthenticatedUserInterface
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
    private ?string $nom_personnel = null;

    #[ORM\Column(length: 255)]
    private ?string $postnom_personnel = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom_personnel = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description_personnel = null;

    #[ORM\Column(length: 255)]
    private ?string $fonction = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone_personnel = null;

    #[ORM\Column(length: 255)]
    private ?string $specialite = null;

    #[ORM\Column(length: 255)]
    private ?string $num_ordre = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\OneToMany(mappedBy: 'personnel', targetEntity: Patient::class)]
    private Collection $patients;

    #[ORM\OneToMany(mappedBy: 'personnel', targetEntity: Diagnostic::class)]
    private Collection $diagnostics;

    #[ORM\OneToMany(mappedBy: 'personnel', targetEntity: Examen::class)]
    private Collection $examens;

    #[ORM\OneToMany(mappedBy: 'personnel', targetEntity: Signal::class)]
    private Collection $signals;

    #[ORM\OneToMany(mappedBy: 'personnel', targetEntity: Ordonnance::class)]
    private Collection $ordonnances;

    #[ORM\OneToMany(mappedBy: 'personnel', targetEntity: Fiche::class)]
    private Collection $fiches;

    public function __construct()
    {
        $this->patients = new ArrayCollection();
        $this->diagnostics = new ArrayCollection();
        $this->examens = new ArrayCollection();
        $this->signals = new ArrayCollection();
        $this->ordonnances = new ArrayCollection();
        $this->fiches = new ArrayCollection();
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

    public function getNomPersonnel(): ?string
    {
        return $this->nom_personnel;
    }

    public function setNomPersonnel(string $nom_personnel): static
    {
        $this->nom_personnel = $nom_personnel;

        return $this;
    }

    public function getPostnomPersonnel(): ?string
    {
        return $this->postnom_personnel;
    }

    public function setPostnomPersonnel(string $postnom_personnel): static
    {
        $this->postnom_personnel = $postnom_personnel;

        return $this;
    }

    public function getPrenomPersonnel(): ?string
    {
        return $this->prenom_personnel;
    }

    public function setPrenomPersonnel(string $prenom_personnel): static
    {
        $this->prenom_personnel = $prenom_personnel;

        return $this;
    }

    public function getDescriptionPersonnel(): ?string
    {
        return $this->description_personnel;
    }

    public function setDescriptionPersonnel(string $description_personnel): static
    {
        $this->description_personnel = $description_personnel;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(string $fonction): static
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getTelephonePersonnel(): ?string
    {
        return $this->telephone_personnel;
    }

    public function setTelephonePersonnel(string $telephone_personnel): static
    {
        $this->telephone_personnel = $telephone_personnel;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): static
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getNumOrdre(): ?string
    {
        return $this->num_ordre;
    }

    public function setNumOrdre(string $num_ordre): static
    {
        $this->num_ordre = $num_ordre;

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

    /**
     * @return Collection<int, Patient>
     */
    public function getPatients(): Collection
    {
        return $this->patients;
    }

    public function addPatient(Patient $patient): static
    {
        if (!$this->patients->contains($patient)) {
            $this->patients->add($patient);
            $patient->setPersonnel($this);
        }

        return $this;
    }

    public function removePatient(Patient $patient): static
    {
        if ($this->patients->removeElement($patient)) {
            // set the owning side to null (unless already changed)
            if ($patient->getPersonnel() === $this) {
                $patient->setPersonnel(null);
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
            $diagnostic->setPersonnel($this);
        }

        return $this;
    }

    public function removeDiagnostic(Diagnostic $diagnostic): static
    {
        if ($this->diagnostics->removeElement($diagnostic)) {
            // set the owning side to null (unless already changed)
            if ($diagnostic->getPersonnel() === $this) {
                $diagnostic->setPersonnel(null);
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
            $examen->setPersonnel($this);
        }

        return $this;
    }

    public function removeExamen(Examen $examen): static
    {
        if ($this->examens->removeElement($examen)) {
            // set the owning side to null (unless already changed)
            if ($examen->getPersonnel() === $this) {
                $examen->setPersonnel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Signal>
     */
    public function getSignals(): Collection
    {
        return $this->signals;
    }

    public function addSignal(Signal $signal): static
    {
        if (!$this->signals->contains($signal)) {
            $this->signals->add($signal);
            $signal->setPersonnel($this);
        }

        return $this;
    }

    public function removeSignal(Signal $signal): static
    {
        if ($this->signals->removeElement($signal)) {
            // set the owning side to null (unless already changed)
            if ($signal->getPersonnel() === $this) {
                $signal->setPersonnel(null);
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
            $ordonnance->setPersonnel($this);
        }

        return $this;
    }

    public function removeOrdonnance(Ordonnance $ordonnance): static
    {
        if ($this->ordonnances->removeElement($ordonnance)) {
            // set the owning side to null (unless already changed)
            if ($ordonnance->getPersonnel() === $this) {
                $ordonnance->setPersonnel(null);
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
            $fich->setPersonnel($this);
        }

        return $this;
    }

    public function removeFich(Fiche $fich): static
    {
        if ($this->fiches->removeElement($fich)) {
            // set the owning side to null (unless already changed)
            if ($fich->getPersonnel() === $this) {
                $fich->setPersonnel(null);
            }
        }

        return $this;
    }
}
