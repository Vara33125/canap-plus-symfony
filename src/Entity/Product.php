<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;


#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le nom ne doit pas depasser {{ limit }} caractères',
    )]
    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    #[ORM\Column(length: 255)]
    private ?string $name = null;
    
    #[Gedmo\Slug(fields: ['name'])]
    #[ORM\Column(length: 255)]
    private ?string $slug = null;


    #[Assert\File(
        maxSize: '4000k',
        extensions: ['jpeg', 'png', 'jpg', 'webp'],
        extensionsMessage: 'Veuillez selectionner une image dont le format est "png, jpg, jpeg ou webp"',
    )]
    #[Vich\UploadableField(mapping: 'products', fileNameProperty: 'picture')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 500)]
    private ?string $picture = null;
    
    #[Assert\NotBlank(message: "Le prix de vente est obligatoire.")]
    #[Assert\Positive(message: "Le prix de vente doit être un nombre positif.")]
    #[Assert\Regex(
            pattern: "/^\d+(.\d{1,2})?$/",
            message: "Le prix de vente doit être un entier ou un nombre décimal avec au plus deux décimales."
    )]
    #[Assert\Range(
            min: 0.1,
            max: 9999999,
            notInRangeMessage: 'Le prix de vente doit être compris entre {{ min }} euros {{ max }} euros.',
    )]
    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $price = null;
    
    #[Assert\Length(
        max: 255,
        maxMessage: 'La descriptio, ne doit pas depasser {{ limit }} caractères',
    )]
    #[Assert\NotBlank(message: 'La description est obligatoire')]
    #[ORM\Column(length: 500)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $isPublished = false;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $Category = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            
        }
    }


    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }


    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setPublished(bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->Category;
    }

    public function setCategory(?Category $Category): static
    {
        $this->Category = $Category;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
