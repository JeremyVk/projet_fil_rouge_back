<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Abstract\BaseVariant;
use App\Repository\BookVariantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BookVariantRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:bookVariant', 'read:baseVariant']],
    denormalizationContext: ['groups' => ['write:bookVariant', 'write:baseVariant']]
)]
class BookVariant extends BaseVariant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:books', 'write:books', 'read:bookVariant', 'write:bookVariant'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bookVariants')]
    #[ORM\JoinColumn(nullable: false, name: 'book_id')]
    #[Groups(['read:bookVariant', 'write:bookVariant'])]
    private Book $book;

    #[ORM\Column(name: 'isbn_number', nullable: false)]
    #[Groups(['read:books', 'write:books','read:bookVariant', 'write:bookVariant'])]
    private string $isbnNumber;

    #[ORM\ManyToMany(targetEntity: BookFormat::class, mappedBy: 'books')]
    #[Groups(['read:books', 'write:books','read:bookVariant', 'write:bookVariant'])]
    private Collection $bookFormats;

    public function __construct()
    {
        $this->bookFormats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): void
    {
        $this->book = $book;
    }

    public function getIsbnNumber(): int
    {
        return $this->isbnNumber;
    }

    public function setIsbnNumber(int $isbnNumber): self
    {
        $this->isbnNumber = $isbnNumber;

        return $this;
    }

    /**
     * @return Collection<int, BookFormat>
     */
    public function getBookFormats(): Collection
    {
        return $this->bookFormats;
    }

    public function addBookFormat(BookFormat $bookFormat): self
    {
        if (!$this->bookFormats->contains($bookFormat)) {
            $this->bookFormats->add($bookFormat);
            $bookFormat->addBook($this);
        }

        return $this;
    }

    public function removeBookFormat(BookFormat $bookFormat): self
    {
        if ($this->bookFormats->removeElement($bookFormat)) {
            $bookFormat->removeBook($this);
        }

        return $this;
    }
}
