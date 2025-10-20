<?php

// src/User/User.php

namespace Entity\User; // IMPORTANT : Adaptez ce namespace à votre structure !

use App\Repository\UserRepository; // Assurez-vous que le Repository est dans le bon dossier !
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')] // Assure que la table s'appelle 'user'
#[UniqueEntity(fields: ['email'], message: 'Cet email est déjà utilisé.')] // Validation côté BDD/ORM
#[UniqueEntity(fields: ['employeeId'], message: 'Ce matricule est déjà utilisé.')] // Validation pour le Matricule
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
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

    // --- Champs Étendus Définis au Niveau de la Migration ---

    #[ORM\Column(length: 255)]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    private ?string $last_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $job_title = null; // Titre du Poste

    #[ORM\Column(length: 50, nullable: true, unique: true)]
    private ?string $employee_id = null; // Matricule (ID Employé)

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo_path = null; // Photo de Profil

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $department = null; // Département

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $team = null; // Équipe

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'employees')]
    #[ORM\JoinColumn(name: 'manager_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    private ?self $manager = null; // Manager N+1 (référence à lui-même)

    #[ORM\Column(type: 'decimal', precision: 4, scale: 2, options: ['default' => 40.00])] // 40.00 heures par défaut
    private ?string $capacity_hours = null; // Capacité Hebdo (en Heures)

    #[ORM\Column]
    private bool $is_active = true; // Est Actif

    #[ORM\Column(nullable: true)]
    private ?\DateTimeInterface $last_login = null;

    // --- Relation Manager (Inversée) ---
    // On ajoute une propriété pour pouvoir faire la liaison depuis le Manager vers ses employés
    #[ORM\OneToMany(mappedBy: 'manager', targetEntity: self::class)]
    private $managedEmployees;

    public function __construct()
    {
        $this->roles = ['ROLE_USER']; // Défaut pour tout nouvel utilisateur
        $this->managedEmployees = new \Doctrine\Common\Collections\ArrayCollection(); // Initialisation pour éviter les erreurs
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
        // Add ROLE_USER by default if not already present
        if (!in_array('ROLE_USER', $roles, true)) {
            $roles[] = 'ROLE_USER';
        }
        // Add ROLE_ADMIN specifically if present, ensure it's there if this user is admin
        // NOTE: Dans la Fixture, nous avons mis ['ROLE_ADMIN', 'ROLE_USER'], donc ça devrait être bon.
        // Si vous aviez une autre logique (ex: basé sur un champ is_admin), elle irait ici.

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
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;
        return $this;
    }

    public function getJobTitle(): ?string
    {
        return $this->job_title;
    }

    public function setJobTitle(?string $job_title): static
    {
        $this->job_title = $job_title;
        return $this;
    }

    public function getEmployeeId(): ?string
    {
        return $this->employee_id;
    }

    public function setEmployeeId(?string $employee_id): static
    {
        $this->employee_id = $employee_id;
        return $this;
    }

    public function getPhotoPath(): ?string
    {
        return $this->photo_path;
    }

    public function setPhotoPath(?string $photo_path): static
    {
        $this->photo_path = $photo_path;
        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): static
    {
        $this->department = $department;
        return $this;
    }

    public function getTeam(): ?string
    {
        return $this->team;
    }

    public function setTeam(?string $team): static
    {
        $this->team = $team;
        return $this;
    }

    public function getManager(): ?self
    {
        return $this->manager;
    }

    public function setManager(?self $manager): static
    {
        $this->manager = $manager;
        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getManagedEmployees(): Collection
    {
        return $this->managedEmployees;
    }

    public function getCapacityHours(): ?string
    {
        return $this->capacity_hours;
    }

    public function setCapacityHours(string $capacity_hours): static
    {
        $this->capacity_hours = $capacity_hours;
        return $this;
    }

    public function isIsActive(): bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): static
    {
        $this->is_active = $is_active;
        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->last_login;
    }

    public function setLastLogin(?\DateTimeInterface $last_login): static
    {
        $this->last_login = $last_login;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you have persistent data that you want to erase after a login,
        // clear it here
        // $this->plainPassword = null;
    }
}
