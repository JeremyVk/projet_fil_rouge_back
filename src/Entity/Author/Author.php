<?php

declare(strict_types=1);

namespace App\Entity\Author;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Articles\Book\Book;
use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:article', 'read:bookVariant', 'read:baseVariant', 'read:book', 'read:books']],
    denormalizationContext: ['groups' => ['write:books', 'write:book']],
)]
class Author
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:book', 'write:book', 'read:article'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['read:book', 'write:book', 'read:article'])]
    private string $firstname;

    #[ORM\Column]
    #[Groups(['read:book', 'write:book', 'read:article'])]
    private string $lastname;

    #[ORM\Column]
    #[Groups(['read:book', 'write:book', 'read:article'])]
    private string $language;

    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'authors', cascade: ['persist'])]
    #[ORM\JoinTable(name: 'authors_books')]
    private Collection $books;

    public function __construct() {
        $this->books = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    /**
     * @return Collection
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    /**
     * @param Collection $books
     */
    public function setBooks(Collection $books): void
    {
        $this->books = $books;
    }

    public function addBook(Book $book): void
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
        }
    }

    public function removeBook(Book $book)
    {
        $this->books->removeElement($book);
    }

    public function __toString(): string
    {
        return $this->firstname . $this->lastname;
    }
}