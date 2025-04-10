<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ORM\Table(name: "symfony_books")]
class Book implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(nullable: true)]
    private ?int $publicationYear = null;

    #[ORM\ManyToOne(targetEntity: Author::class, inversedBy: "books")]
    #[ORM\JoinColumn(nullable: false)]
    private Author $author;

    #[ORM\ManyToOne(targetEntity: Genre::class, inversedBy: "books")]
    #[ORM\JoinColumn(nullable: false)]
    private Genre $genre;

    #[ORM\ManyToOne(targetEntity: Publisher::class, inversedBy: "books")]
    #[ORM\JoinColumn(nullable: true)]
    private ?Publisher $publisher = null;

    #[ORM\OneToMany(mappedBy: "book", targetEntity: Loan::class)]
    private Collection $loans;

    public function __construct()
    {
        $this->loans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getPublicationYear(): ?int
    {
        return $this->publicationYear;
    }

    public function setPublicationYear(?int $publicationYear): static
    {
        $this->publicationYear = $publicationYear;
        return $this;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }

    public function setAuthor(Author $author): static
    {
        $this->author = $author;
        return $this;
    }

    public function getGenre(): Genre
    {
        return $this->genre;
    }

    public function setGenre(Genre $genre): static
    {
        $this->genre = $genre;
        return $this;
    }

    public function getPublisher(): ?Publisher
    {
        return $this->publisher;
    }

    public function setPublisher(?Publisher $publisher): static
    {
        $this->publisher = $publisher;
        return $this;
    }

    public function getLoans(): Collection
    {
        return $this->loans;
    }

    #[ArrayShape([
        'id'              => "int|null",
        'title'           => "null|string",
        'publicationYear' => "int|null",
        'author'          => "array",
        'genre'           => "array",
        'publisher'       => "array|null"
    ])]
    public function jsonSerialize(): mixed
    {
        return [
            'id'              => $this->getId(),
            'title'           => $this->getTitle(),
            'publicationYear' => $this->getPublicationYear(),
            'author'          => $this->getAuthor()->jsonSerialize(),
            'genre'           => $this->getGenre()->jsonSerialize(),
            'publisher'       => $this->getPublisher() ? $this->getPublisher()->jsonSerialize() : null,
        ];
    }
}
