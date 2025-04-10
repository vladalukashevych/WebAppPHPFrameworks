<?php

namespace App\Entity;

use App\Repository\PublisherRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;

#[ORM\Entity(repositoryClass: PublisherRepository::class)]
#[ORM\Table(name: "symfony_publishers")]
class Publisher implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\OneToMany(mappedBy: "publisher", targetEntity: Book::class)]
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;
        return $this;
    }

    public function getBooks(): Collection
    {
        return $this->books;
    }

    #[ArrayShape([
        'id'      => "int|null",
        'name'    => "null|string",
        'address' => "null|string"
    ])]
    public function jsonSerialize(): mixed
    {
        return [
            'id'      => $this->getId(),
            'name'    => $this->getName(),
            'address' => $this->getAddress(),
        ];
    }
}
