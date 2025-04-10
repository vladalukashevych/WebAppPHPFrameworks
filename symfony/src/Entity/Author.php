<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
#[ORM\Table(name: "symfony_authors")]
class Author implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $bio = null;

    #[ORM\OneToMany(mappedBy: "author", targetEntity: Book::class)]
    private Collection $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

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

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): static
    {
        $this->bio = $bio;
        return $this;
    }

    public function getBooks(): Collection
    {
        return $this->books;
    }

    #[ArrayShape([
        'id'   => "int|null",
        'name' => "null|string",
        'bio'  => "null|string",
    ])]
    public function jsonSerialize(): mixed
    {
        return [
            'id'   => $this->getId(),
            'name' => $this->getName(),
            'bio'  => $this->getBio(),
        ];
    }
}
