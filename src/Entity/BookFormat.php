<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\BookFormatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BookFormatRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:bookFormats', 'read:books']],
    denormalizationContext: ['groups' => ['write:bookVariant', 'write:bookFormats']],
)]
class BookFormat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read:bookFormats', 'read:bookVariant', 'read:books'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:bookFormats', 'read:bookVariant', 'read:books'])]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: BookVariant::class, inversedBy: 'bookFormats')]
    // #[Groups([])]
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

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, BookVariant>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(BookVariant $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
        }

        return $this;
    }

    public function removeBook(BookVariant $book): self
    {
        $this->books->removeElement($book);

        return $this;
    }
}
