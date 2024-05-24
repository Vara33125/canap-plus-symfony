<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Impossible de créer un compte avec cet email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank (message: "Le prénom est obligatoire.")]
    #[Assert\Length(
        max: 50,
        maxMessage:'Le prénom ne doit pas dépaser de {{ limit }} caractères',
    )]
    #[Assert\Regex(
        pattern: "/^[0-9a-zA-Z-_' áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]+$/i",
        match: true,
        message:"Seuls les lettres, les chiffres, l'undescore et tiret sont autorisés pour le prénom")]
    #[ORM\Column(length: 50)]
    private ?string $prenom = null;
    

    #[Assert\NotBlank (message: "Le nom est obligatoire.")]
    #[Assert\Length(
        max: 50,
        maxMessage:'Le nom ne doit pas dépaser {{ limit }} caractères',
    )]
    #[Assert\Regex(
        pattern: "/^[0-9a-zA-Z-_' áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]+$/i",
        match: true,
        message:"Seuls les lettres, les chiffres, l'undescore et tiret sont autorisés pour le nom")]
    #[ORM\Column(length: 50)]
    private ?string $nom = null;
    
    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[Assert\NotBlank (message: "Le mot de passe est obligatoire.")]
    #[Assert\Length(
        min: 12,
        max: 255,
        minMessage: "Le mot de pass doit contenir au minimun {{ limit }} caractères",
        maxMessage: "Le mot de pass doit contenir au maximum {{ limit }} caractères",
    )]
    #[Assert\Regex(
        pattern: "/^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ỳ])(?=.*[0-9])(?=.*[^a-zà-ÿA-ZÀ-Ỳ0-9]).{11,255}$/",
        match: true,
        message: "Le mot de passe doit contentir au moins une lettre miniscule, majuscule, un chiffre et un caractère spécial.",
    )]
    #[Assert\NotCompromisedPassword(message:'Votre mot de passe est facilement piratable! Veuillez en choisir un autre.')]
    #[ORM\Column]
    private ?string $password = null;

    #[Assert\NotBlank (message: "L'email est obligatoire.")]
    #[Assert\Length(
        max: 180,
        maxMessage:"L'email ne doit pas dépaser {{ limit }} caractères",
    )]
    #[Assert\Email(message: "L'email {{ value }} est invalied")]
    #[ORM\Column(length: 180)]
    private ?string $email = null;
    
    #[Assert\Length(
        max: 50,
        maxMessage: 'Le numéro de téléphone ne doit pas depasser de {{ limit }} caractères',
    )]
    #[Assert\NotBlank (message: "Le téléphone est obligatoire.")]
    #[ORM\Column(length: 50)]
    private ?string $telephone = null;
    
    #[Assert\Length(
        max: 255,
        maxMessage: 'L\'adresse ne doit pas depasser de {{ limit }} caractères',
    )]
    #[Assert\NotBlank (message: "L'adresse est obligatoire.")]
    #[ORM\Column(length: 255)]
    private ?string $adresse = null;
    
    
    #[Assert\Length(
        max: 50,
        maxMessage: 'Le nom de la ville ne doit pas depasser de {{ limit }} caractères',
    )]
    #[Assert\NotBlank (message: "Le nom de la ville est obligatoire.")]
    #[ORM\Column(length: 50)]
    private ?string $ville = null;
     
    #[Assert\NotBlank (message: "Le code postal est obligatoire.")]
    #[ORM\Column]
    private ?int $cp = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $verifiedAt = null;

    public function __construct()
    {
        $this->roles[] = 'ROLE_USER';
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
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCp(): ?int
    {
        return $this->cp;
    }

    public function setCp(int $cp): static
    {
        $this->cp = $cp;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->verifiedAt;
    }

    public function setVerifiedAt(?\DateTimeImmutable $verifiedAt): static
    {
        $this->verifiedAt = $verifiedAt;

        return $this;
    }
}
