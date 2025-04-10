<?php

namespace App\Entity;

use App\Repository\LoanRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;

#[ORM\Table(name:"symfony_loans")]
#[ORM\Entity(repositoryClass: LoanRepository::class)]
class Loan implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type:"date")]
    private \DateTimeInterface $loanDate;

    #[ORM\Column(type:"date", nullable:true)]
    private ?\DateTimeInterface $returnDate = null;

    #[ORM\Column(length: 255)]
    private ?string $borrowerName = null;

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: "loans")]
    #[ORM\JoinColumn(nullable: false)]
    private Book $book;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLoanDate(): \DateTimeInterface
    {
        return $this->loanDate;
    }

    public function setLoanDate(\DateTimeInterface $loanDate): static
    {
        $this->loanDate = $loanDate;
        return $this;
    }

    public function getReturnDate(): ?\DateTimeInterface
    {
        return $this->returnDate;
    }

    public function setReturnDate(?\DateTimeInterface $returnDate): static
    {
        $this->returnDate = $returnDate;
        return $this;
    }

    public function getBorrowerName(): ?string
    {
        return $this->borrowerName;
    }

    public function setBorrowerName(string $borrowerName): static
    {
        $this->borrowerName = $borrowerName;
        return $this;
    }

    public function getBook(): Book
    {
        return $this->book;
    }

    public function setBook(Book $book): static
    {
        $this->book = $book;
        return $this;
    }

    #[ArrayShape([
        'id'           => "int|null",
        'loanDate'     => "string",
        'returnDate'   => "string|null",
        'borrowerName' => "null|string",
        'book'         => "array"
    ])]
    public function jsonSerialize(): mixed
    {
        return [
            'id'           => $this->getId(),
            'loanDate'     => $this->getLoanDate()->format('Y-m-d'),
            'returnDate'   => $this->getReturnDate() ? $this->getReturnDate()->format('Y-m-d') : null,
            'borrowerName' => $this->getBorrowerName(),
            // Here we provide minimal book info to avoid recursion.
            'book'         => [
                'id'    => $this->getBook()->getId(),
                'title' => $this->getBook()->getTitle(),
            ],
        ];
    }
}
